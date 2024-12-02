<?php

namespace App\Core\Resources\Common;

final class PaginateResource extends AbstractResource
{
    
    protected function toArray (): array
    {
        $page = $this->page;
        $total = $this->total;
        $limit = $this->limit;
        $nextPage = $page + 1;
        $prevPage = $page - 1;
        $lastPage = (int) ceil($total / ($limit ?: 1));
        $from = ($page - 1) * $limit;
        $to = $from + $limit;
        
        if ($from <= 1) $from = 1;
        if ($to >= $total) $to = $total;
        if ($to === 0) $to = $total;
        if ($from > $total) $from = $total;
        if ($nextPage > $lastPage) $nextPage = null;
        if ($prevPage < 1) $prevPage = null;
        if ($lastPage == 0) $lastPage = 1;
        
        if ($from === $total && $to === $total) {
            $to = 0;
            $from = 0;
        }
        
        return [
            'current_page' => $page,
            'per_page' => $limit,
            'last_page' => $lastPage,
            'next_page' => $nextPage,
            'prev_page' => $prevPage,
            'total' => $total,
            'from' => $from,
            'to' => $to,
        ];
    }
}