<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $table = 'gift_cards';

    protected $fillable = [
        'user_id',
        'title',
        'value',
        'valid_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedByUser($query)
    {
        return $query->where('user_id', auth()->id())->orderBy('id', 'DESC');
    }
}
