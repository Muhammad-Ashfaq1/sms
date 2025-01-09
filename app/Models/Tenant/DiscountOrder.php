<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DiscountOrder extends Model
{
    protected $table = 'discount_orders';

    protected $fillable = ['order_id', 'discountable_id', 'discountable_type', 'discount', 'discount_type'];

    public function discountable()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
