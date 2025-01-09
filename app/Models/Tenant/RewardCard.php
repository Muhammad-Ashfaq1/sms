<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RewardCard extends Model
{
    protected $table = 'reward_cards';

    protected $casts = [
        'off_percentage' => 'float',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'off_percentage',
        'spend_limit',
        'valid_date',
        'discount_type',
        'unique_number',
        'qr_code',
        'availed',
    ];

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code ? Storage::url($this->qr_code) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_reward_card');
    }

    public function scopeOwnedByUser($query)
    {
        return $query->where('user_id', auth()->id())->orderBy('id', 'DESC');
    }

    public function scopeNotAvailed($query)
    {
        return $query->where('availed', 0);
    }

    public function discountables()
    {
        return $this->morphMany(DiscountOrder::class, 'discountable');
    }

    // Add validation method
    public static function validateRewardCard($uniqueNumber)
    {
        $rewardCard = self::where('unique_number', $uniqueNumber)
            ->where('valid_date', '>=', now())
            ->where('availed', false)
            ->first();

        return $rewardCard;
    }
}
