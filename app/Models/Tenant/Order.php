<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'total',
        'sub_total',
        'discount',
        'tax',
        'amount_paid',
        'balance_due',
        'order_status_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(DiscountOrder::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function scopeOwnedBy(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithAggregates(Builder $query)
    {
        return $query->select(
            'orders.id as order_id',
            'orders.*',
            'orders.created_at as order_created_at',
            DB::raw('GROUP_CONCAT(order_items.id) as order_item_ids'),
            DB::raw('GROUP_CONCAT(order_items.product_id) as order_item_product_ids'),
            DB::raw('GROUP_CONCAT(products.name) as product_names')
        )
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('orders.id');
    }

    public function scopeSearchProducts(Builder $query, $search)
    {
        return $query
            ->where('orders.order_number', 'like', "%{$search}%")
            ->orWhereHas('customer', function (Builder $customerQuery) use ($search) {
                $customerQuery->where('customers.id', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(customers.first_name, ' ', customers.last_name)"), 'like', "%{$search}%")
                    ->orWhere('customers.email', 'like', "%{$search}%")
                    ->orWhere('customers.phone', 'like', "%{$search}%");
            })
            ->orWhereHas('orderStatus', function (Builder $orderStatusQuery) use ($search) {
                $orderStatusQuery->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('orderItems.product', function (Builder $productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('alu', 'like', "%{$search}%");
            });
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereDate('orders.created_at', now());
    }
}
