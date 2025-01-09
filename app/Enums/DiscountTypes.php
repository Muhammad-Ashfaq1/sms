<?php

namespace App\Enums;

enum DiscountTypes: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function label(): string
    {
        return match ($this) {
            DiscountTypes::PERCENTAGE => 'percentage',
            DiscountTypes::FIXED => 'fixed',
        };
    }
}
