<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class School extends Model
{
    use HasDomains;

    protected $fillable = [
        'name',
        'address',
        'database',
        'domain',
        'admin_email',
        'tenant_id',
        'status'
    ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'tenant_id');
    }
}
