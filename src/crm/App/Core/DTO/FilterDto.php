<?php

namespace App\Core\DTO;

final class FilterDto
{
    public static function make (string $name, array|null $options = null, string $type = 'select', string|null $label = null, string|null $placeholder = null): array
    {
        if (!$label) {
            $label = "filter[{$name}]";
        }
        
        if (!$placeholder) {
            $placeholder = "{$label}_{$type}";
        }
        
        if (is_array($options)) {
            $options = ['' => trans('filter', 'all'), ...$options];
        }
        
        return [
            'name' => $name,
            'type' => $type,
            'label' => trans('filter', $label),
            'options' => $options,
            'placeholder' => trans('filter', $placeholder)
        ];
    }
}