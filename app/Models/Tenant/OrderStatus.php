<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';

    protected $fillable = ['name'];

    const PAID = 'Paid';

    const PARTIALLY_PAID = 'Partially Paid';

    const NOT_PAID = 'Not Paid';
}
