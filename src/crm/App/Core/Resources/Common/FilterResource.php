<?php

namespace App\Core\Resources\Common;

final class FilterResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'label' => $this->label,
            'type' => $this->type,
            'name' => $this->name,
            'options' => $this->options,
        ];
    }
}