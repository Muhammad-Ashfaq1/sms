<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_groups';

    protected $fillable = [
        'user_id',
        'title',
        'discount',
        'discount_type',
    ];

    public function discountables()
    {
        return $this->morphMany(DiscountOrder::class, 'discountable');
    }

    public function scopeOwnedByUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
