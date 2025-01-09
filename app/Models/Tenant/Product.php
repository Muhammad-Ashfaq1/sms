<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'price',
        'discount',
        'quantity_in_stock',
        'images',
        'status',
        'purchased_units',
        'cost_price',
        'current',
        'receive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Get the category that the product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function productVolumes()
    {
        return $this->hasMany(ProductVolume::class);
    }

    public function getPriceForQuantity($quantity)
    {
        return $this->productVolumes()
            ->where('quantity', '<=', $quantity)
            ->orderBy('quantity', 'desc')
            ->value('price') ?? $this->price;
    }

    public function scopeSearch($query, $search = null)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('alu', 'like', '%'.$search.'%')
                ->orWhere('sku', 'like', '%'.$search.'%')
                ->orWhere('barcode', 'like', '%'.$search.'%');
        })->orderBy('created_at', 'desc');
    }
}
