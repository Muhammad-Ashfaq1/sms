<?php

namespace App\Enums;

enum OrderPaidStatus: string
{
    case PAID = 'Paid';
    case NOT_PAID = 'Not Paid';
    case PARTIALLY_PAID = 'Partially Paid';

    public function label(): string
    {
        return match($this) {
            OrderPaidStatus::PAID => 'Paid',
            OrderPaidStatus::NOT_PAID => 'Not Paid',
            OrderPaidStatus::PARTIALLY_PAID => 'Partially Paid',
        };
    }
}
