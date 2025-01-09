<?php

namespace App\Enums;

enum OrderListingStatus: string
{
    case TODAY = 'today'; 
    case PENDING = 'pending';
    case ALL = 'all';
  
    public function label(): string
    {
        return match($this) {
            OrderListingStatus::TODAY => 'Today',
            OrderListingStatus::PENDING => 'Pending',
            OrderListingStatus::ALL => 'All',
        };
    }
}

