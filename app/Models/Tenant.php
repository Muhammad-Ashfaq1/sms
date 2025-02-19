<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use CentralConnection,HasDatabase,HasDomains,HasFactory,HasUuids;

    protected $fillable = [
        'id',
        'data',
        'org_name',
        'email',
        'phone',
        'status',
        'address'
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'org_name',
            'email',
            'status',
            'address',
        ];
    }

    public function getDomainAttribute(): ?string
    {
        return $this->domains()->first()?->domain;
    }

    public function getSubdomainAttribute(): ?string
    {
        return Str::of($this->domain)->before('.'.centralDomain());
    }

    public function route(string $route, mixed $parameters = [], bool $absolute = true): string|array
    {
        return tenant_route($this->domains->first()->domain, $route, $parameters, $absolute);
    }

    public static function databases(): array
    {
        return Cache::remember('tenants::databases', 10 * 60, function () {
            return self::query()
                ->select('data')
                ->get()
                ->pluck('tenancy_db_name')
                ->toArray();
        });
    }
}
