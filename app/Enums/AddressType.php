<?php

namespace App\Enums;

enum AddressType: int
{
    case DELIVERY = 1;
    case BILLING = 2;

    public function label(): string
    {
        return match($this) {
            AddressType::DELIVERY => 'Delivery',
            AddressType::BILLING  => 'Billing',
        };
    }
}
