<?php

namespace App\Core\DTO;

final class SortDto
{
    public static function make (string $name, string|null $label = null): array
    {
        if (!$label) {
            $label = "sort[{$name}]";
        }
        
        return [
            'name' => "sort[{$name}]",
            'type' => 'select',
            'label' => trans('sorts', $label),
            'options' => [
                '' => trans('sorts', 'default'),
                'asc' => trans('sorts', 'asc'),
                'desc' => trans('sorts', 'desc'),
            ],
        ];
    }
}