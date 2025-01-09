<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'parent_id', 'status'];

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->whereStatus(true);
    }

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $like = "%{$search}%";

            // Search for categories with a name match or their subcategories and products
            $query->where('name', 'like', $like)
                ->orWhereHas('subcategories', function (Builder $query) use ($like) {
                    $query->WhereHas('products', function (Builder $query) use ($like) {
                        $query->where('barcode', 'like', $like);
                        $query->orWhere('sku', 'like', $like);
                        $query->orWhere('alu', 'like', $like);
                    });
                });
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
