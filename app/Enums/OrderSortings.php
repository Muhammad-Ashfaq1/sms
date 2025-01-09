<?php

namespace App\Enums;

enum OrderSortings: string
{
    case CUSTOMER_NAME = 'customer_name';
    case CREATED_AT = 'created_at';
    case ORDER_ID = 'id';
    case TOTAL = 'total';

    public function label(): string
    {
        return match($this) {
            OrderSortings::CUSTOMER_NAME => 'customer_name',
            OrderSortings::CREATED_AT => 'created_at',
            OrderSortings::ORDER_ID => 'id',
            OrderSortings::TOTAL => 'total',
        };
    }
}
