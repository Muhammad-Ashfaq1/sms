<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'customer_group_id',
    ];

    public function getNameAttribute()
    {
        return ucwords($this->first_name.' '.$this->last_name);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $like = "%{$search}%";

            return $query
                ->where('customers.id', 'like', $like)
                ->orWhere(DB::raw("CONCAT(customers.first_name, ' ', customers.last_name)"), 'like', $like)
                ->orWhere('customers.email', 'like', $like)
                ->orWhere('customers.phone', 'like', $like);
        });
    }

    public function parent()
    {
        return $this->belongsTo(Customer::class, 'parent_id');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function deliveryAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 1);
    }

    // Relationship to get the billing address
    public function billingAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 2);
    }

    // Relationship to get the default address
    public function defaultAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('is_default', true);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
}
