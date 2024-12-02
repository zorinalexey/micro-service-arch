<?php

namespace App\Core\Resources\Common;

final class SortResource extends AbstractResource
{
    protected function toArray (): array
    {
        return [
            'type' => $this->type,
            'label' => $this->label,
            'options' => $this->options,
            'name' => $this->name,
        ];
    }
}