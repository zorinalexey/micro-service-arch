<?php

namespace App\Core\Service;

use App\Core\Helpers\Arr;
use App\Core\Request\Request;
use App\Core\Resources\Common\PaginateResource;
use App\Core\SqlBuilder\CRUD\Select;
use App\Core\SqlBuilder\SqlBuilder;
use PDO;
use Ramsey\Uuid\Uuid;

abstract class AbstractService implements ServiceInterface, ControllerInterface
{
    public int $httpCode = 200;
    
    protected array $errors = [];
    
    protected string|null $message = null;
    
    protected string $tableName = '';
    
    protected array $builderFields = [];
    protected array $fillable = [];
    
    final public function getErrors (): array
    {
        return $this->errors;
    }
    
    final public function getMessage (): string|null
    {
        return $this->message;
    }
    
    final public function getBuilderFields (): array
    {
        return $this->builderFields;
    }
    
    final public function list (Request $request): array
    {
        $data = $request->validated();
        
        return cache()->getStore()->remember($this->getTableName() . ':' . md5(serialize($data)), $this->getCacheCollection($request));
    }
    
    final public function getTableName (): string
    {
        return $this->tableName;
    }
    
    final protected function getCacheCollection (Request $request): array
    {
        [$collection, $total, $limit, $page] = $this->collection($request);
        
        return [
            'data' => $this->resource::collection($collection),
            'paginate' => PaginateResource::make(compact('total', 'limit', 'page')),
            'filters' => $this->filter::getFilters(),
            'sorts' => $this->filter::getSorts(),
            'links' => $this->getLinks($request),
        ];
    }
    
    final protected function collection (Request $request): array
    {
        $data = $request->validated();
        [$collection, $paginate['total'], $limit, $page] = $this->paginate($this->getBuilder($data), $data);
        
        return [$collection, $paginate['total'], $limit, $page];
    }
    
    private function paginate (Select $builder, array $params): array
    {
        $checkPaginate = false;
        $page = (int) Arr::pull($params, 'page', 1);
        
        if (($limit = (int) Arr::pull($params, 'per_page', 50)) && $limit > 0) {
            $builder->paginate($limit, $page);
            $checkPaginate = true;
        }
        
        $connection = db()->getConnection();
        
        // collection
        $prepared = $connection->prepare((string) $builder);
        $prepared->execute($builder->getBinds());
        $collection = $prepared->fetchAll(PDO::FETCH_ASSOC) ?: [];
        
        if ($checkPaginate) {
            // pagination
            $paginateSql = $builder->getPaginateSql();
            $paginatePrepared = $connection->prepare($paginateSql);
            $paginatePrepared->execute($builder->getBinds());
            $paginate = $paginatePrepared->fetch(PDO::FETCH_ASSOC);
        } else {
            $paginate['total'] = count($collection);
        }
        
        return [$collection, $paginate['total'], $limit, $page ?? null];
    }
    
    protected function getBuilder (array $data = []): Select
    {
        $builder = SqlBuilder::select()->table($this->getTableName());
        
        $this->filter::filter($builder, $this, $data);
        
        return $builder;
    }
    
    private function getLinks (Request $request): array
    {
        return [
            'group_restore' => $this->getLink($request, 'PUT', '/group'),
            'group_delete_soft' => $this->getLink($request, 'DELETE', '/group/soft'),
            'group_delete_hard' => $this->getLink($request, 'DELETE', '/group/hard'),
            'list' => $this->getLink($request, 'GET'),
            'create' => $this->getLink($request, 'POST'),
        ];
    }
    
    private function getLink (Request $request, string $request_method, string|null $path = null): array
    {
        $patch = $request->get('path') . $path;
        $link = $_SERVER['HTTP_HOST'] . '/' . $patch;
        
        return [
            'url' => $link,
            'method' => $request_method,
            'permission_key' => str_replace('/', '.', $patch),
            'title' => trans('routes', str_replace('/', '_', $patch))
        ];
    }
    
    final protected function get (string|int $id): array
    {
        $builder = $this->getBuilder(['id' => $id, 'trashed' => Request::getInstance()->get('trashed') ?: 'default']);
        
        // entity data
        $prepared = db()->getConnection()->prepare((string) $builder);
        $prepared->execute($builder->getBinds());
        
        return $prepared->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    
    public function createInfo (?Request $request = null): array
    {
        return $this->actionInfo($request);
    }
    
    public function actionInfo (?Request $request = null): array
    {
        return [];
    }
    
    public function updateInfo (?Request $request = null): array
    {
        return $this->actionInfo($request);
    }
    
    final public function softGroupDelete (Request $request): array
    {
        $results = [];
        
        foreach ($request->validated()['ids'] ?? [] as $id) {
            $results[$id] = $this->softDelete($id)['data'];
        }
        
        return ['data' => $results];
    }
    
    final public function softDelete (int|string $id): array
    {
        $request = Request::getInstance();
        $request->trashed = 'all';
        $request->id = $id;
        $request->deleted_at = date('Y-m-d H:i:s');
        
        if ($result = $this->update($id, $request)['data'] ?? false) {
            $this->clearCache($id);
        }
        
        return ['data' => (bool) $result];
    }
    
    public function update (int|string $id, Request $request): array
    {
        $params = [];
        $this->setAddParams($params, $request);
        $this->setQueryParams($params, $request);
        
        if ($params) {
            $params['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $builder = SqlBuilder::update()->table($this->getTableName())->values($params)->where(['id', $id]);
        
        if ($request->has('trashed')) {
            $where[] = ['trashed', $request->trashed];
            $this->filter::filter($builder, $this, $where);
        }
        
        $prepared = db()->getConnection()->prepare((string) $builder);
        
        if ($prepared->execute($builder->getBinds())) {
            $this->clearCache($id);
            
            return $this->view($id);
        }
        
        $this->httpCode = 444;
        
        return [];
    }
    
    protected function setAddParams (array &$params, Request|array|null $request = null): void
    {
    
    }
    
    final protected function setQueryParams (array &$params, Request $request): void
    {
        foreach ($request->validated() as $key => $value) {
            if (in_array($key, $this->getFields(), false)) {
                $params[$key] = $value;
            }
        }
    }
    
    final public function getFields (): array
    {
        return [
            'id',
            'int_id',
            'created_at',
            'updated_at',
            'deleted_at',
            ...$this->fillable,
        ];
    }
    
    protected function clearCache (int|string|array $ids, array $keys = []): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        $table = $this->getTableName();
        $store = cache()->getStore();
        
        if (!$keys) {
            $keys = $store->keys("{$table}:*");
        }
        
        foreach ($ids as $id) {
            foreach ($keys as $key) {
                if (str_contains($key, $id) || !preg_match("~^{$table}:id:(.+)$~ui", $key)) {
                    $store->delete($key);
                }
            }
        }
    }
    
    final public function view (string|int $id): array
    {
        return cache()->getStore()->remember($this->getTableName() . ":id:$id", $this->getCacheEntityById($id));
    }
    
    final protected function getCacheEntityById ($id): array
    {
        if (!($data = $this->get($id))) {
            header('HTTP/1.1 404 Not Found');
            exit;
        }
        
        return [
            'data' => $this->resource::make($data),
        ];
    }
    
    final public function groupRestore (Request $request): array
    {
        $results = [];
        
        foreach ($request->validated()['ids'] ?? [] as $id) {
            $results[$id] = $this->restore($id);
        }
        
        return $results;
    }
    
    final public function restore (int|string $id): array
    {
        $params = [
            'deleted_at' => null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $builder = SqlBuilder::update()->table($this->getTableName())
            ->values($params)
            ->where(['id', $id])
            ->where(['deleted_at', null, 'IS NOT NULL'], ['deleted_at', null, 'IS NULL']);
        $prepared = db()->getConnection()->prepare((string) $builder);
        $prepared->execute($builder->getBinds());
        
        if ($prepared->execute($builder->getBinds()) && (($result = $this->view($id)) && $result['data'])) {
            $this->clearCache($id);
            
            return $result;
        }
        
        $this->httpCode = 444;
        
        return ['data' => false];
    }
    
    final public function hardGroupDelete (Request $request): array
    {
        $results = [];
        
        foreach ($request->validated()['ids'] ?? [] as $id) {
            $results[$id] = $this->hardDelete($id);
        }
        
        return ['data' => $results];
    }
    
    final public function hardDelete (int|string $id): bool
    {
        $builder = SqlBuilder::delete()->table($this->getTableName())->where(['id', $id]);
        
        if ($result = db()->getConnection()->prepare((string) $builder)->execute()) {
            $this->clearCache($id);
        }
        
        return $result;
    }
    
    public function create (Request $request): array
    {
        $params = [];
        $this->setAddParams($params, $request);
        $this->setQueryParams($params, $request);
        
        if ($params) {
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['updated_at'] = null;
            $params['deleted_at'] = null;
            $params['id'] = Uuid::uuid7()->toString();
        }
        
        $builder = SqlBuilder::insert()->table($this->getTableName())->values($params);
        $prepared = db()->getConnection()->prepare((string) $builder);
        
        if ($prepared->execute($builder->getBinds())) {
            $this->clearCache($params['id']);
            $this->httpCode = 201;
            
            return $this->view($params['id']);
        }
        
        $this->httpCode = 444;
        
        return [];
    }
}