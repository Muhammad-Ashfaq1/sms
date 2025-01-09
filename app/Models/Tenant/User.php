<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, TenantConnection;

    protected $guard = 'tenant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $like = "%{$search}%";

            return $query
                ->where('users.id', 'like', $like)
                ->orWhere('users.name', 'like', $like)
                ->orWhere('users.email', 'like', $like)
                ->orWhereHas('roles', function (Builder $query) use ($like) {
                    $query->where('name', 'like', $like);
                });
        });
    }

    public function cart()
    {
        return $this->hasMany(UserCart::class);
    }

    public function rewardCards()
    {
        return $this->hasMany(RewardCard::class);
    }

    public function giftCards()
    {
        return $this->hasMany(GiftCard::class);
    }
}
