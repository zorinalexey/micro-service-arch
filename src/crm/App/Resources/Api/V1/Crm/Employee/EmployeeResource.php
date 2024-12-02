<?php

namespace App\Resources\Api\V1\Crm\Employee;

use App\Core\Resources\Common\AbstractResource;
use DateMalformedStringException;
use DateTime;

final class EmployeeResource extends AbstractResource
{
    /**
     * @return array
     * @throws DateMalformedStringException
     */
    protected function toArray (): array
    {
        return [
            'id' => $this->id,
            'int_id' => $this->int_id,
            'login' => $this->login,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth ? new DateTime($this->date_of_birth) : null,
            'call_number' => $this->call_number,
            'virtual_number' => $this->virtual_number,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'links' => $this->links('api/v1/crm/employee', $this->id),
            'city' => CityResource::make($this->data),
            'region' => RegionResource::make($this->data),
            'country' => CountryResource::make($this->data),
            'role' => RoleResource::make($this->data),
        ];
    }
}