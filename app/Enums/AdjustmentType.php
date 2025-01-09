<?php

namespace App\Enums;

enum AdjustmentType: string
{
    case ADD = 'add';
    case SUBTRACT = 'subtract';

    public function label(): string
    {
        return match($this) {
            AdjustmentType::ADD => 'add',
            AdjustmentType::SUBTRACT => 'subtract',
        };
    }
}
