<?php

namespace App\Models\Tenant;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    //

    protected $fillable = [
        'address',
        'country',
        'city',
        'state',
        'zip_code',
        'is_default'
    ];

    protected $casts = [
        'type' => AddressType::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isDefault()
    {
        return $this->is_default;
    }

    public function getFullAddressAttribute()
    {
        return $this->address.' '.$this->city.' '.$this->state.' '.$this->zip_code.' '.$this->country;
    }
}
