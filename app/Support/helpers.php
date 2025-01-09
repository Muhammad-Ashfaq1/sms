<?php

use App\Models\Tenant;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

// use App\Models\Tenant\RewardCard;
// use App\Enums\DiscountTypes;

if (! function_exists('centralDomain')) {
    function centralDomain(): string
    {
        return parse_url(config('app.url'), PHP_URL_HOST);
    }
}

if (! function_exists('subdomain')) {
    function subdomain(string $subdomain)
    {
        return $subdomain.'.'.centralDomain();
    }
}

if (! function_exists('guard')) {
    function guard(): string
    {
        return tenant('id') ? 'tenant' : 'web';
    }
}

if (! function_exists('generateUniqueTenant')) {

    function generateUniqueTenant()
    {
        $uniqueStr = Uuid::uuid4()->toString();
        while (Tenant::where('id', $uniqueStr)->exists()) {
            $uniqueStr = Uuid::uuid4()->toString();
        }

        return $uniqueStr;
    }
}

/*
if (!function_exists('notify')) {
    function notify(array|string $notification, string $type = 'success', bool $returnArray = false)
    {
        $text         = is_string($notification) ? $notification : $notification['text'];
        $notification = Arr::wrap($notification);
        $type         = in_array('type', $notification) ? $notification['type'] : $type;

        $title = match ($type) {
            'info'   => 'You must know!',
            'danger' => 'Oops...',
            default  => 'Success!'
        };
        if (isset($notification['title'])) {
            $title = $notification['title'];
        }

        $notification = [
            'icon'        => data_get($notification, 'icon', true),
            'close'       => data_get($notification, 'close', true),
            'progressBar' => data_get($notification, 'progressBar', true),
            'type'        => $type,
            'title'       => $title,
            'text'        => $text,
            'timeout'     => data_get($notification, 'timeout', 3000),
        ];

        if ($returnArray) {
            return $notification;
        }

        session()->flash('notification', $notification);
    }
}

if (!function_exists('calculateDiscountedTotal')) {
    function calculateDiscountedTotal($total, $customer) {

        if ($customer?->customerGroup) {
            $discount = $customer->customerGroup->discount;
            $discountType = $customer->customerGroup->discount_type;

            if ($discountType === 'percentage') {
                $total -= ($total * ($discount / 100));
            } elseif ($discountType === 'fixed') {
                $total -= $discount;
            }
        }
        return $total;
    }
}

if (!function_exists('calculateFinalTotal')) {
    function calculateFinalTotal($total, $customer, $rewardCard = null) {
        $total = max(0, $total);
        $afterCustomerDiscount = calculateDiscountedTotal($total, $customer);

        if (!$rewardCard || $rewardCard->valid_date < now() || $rewardCard->availed) {
            return $afterCustomerDiscount;
        }

        if ($rewardCard->products->count() > 0) {
            $discountAmount = 0;
            $rewardCardProductIds = $rewardCard->products->pluck('id')->toArray();
            $cartProductIds = auth()->user()->cart->pluck('product_id')->toArray();

            // Check if there's any overlap between reward card products and cart products
            $matchingProducts = array_intersect($rewardCardProductIds, $cartProductIds);

            // If no matching products found, apply discount to total amount
            if (empty($matchingProducts)) {
                $rewardCardDiscount = $rewardCard->discount_type === DiscountTypes::PERCENTAGE->value
                    ? ($afterCustomerDiscount * $rewardCard->off_percentage / 100)
                    : min($rewardCard->off_percentage, $afterCustomerDiscount);

                return max(0, $afterCustomerDiscount - $rewardCardDiscount);
            }

            // If matching products found, apply discount only to matching products
            foreach (auth()->user()->cart as $cartItem) {
                if (in_array($cartItem->product_id, $rewardCardProductIds)) {
                    $itemTotal = $cartItem->quantity * $cartItem->product->price;
                    $discountAmount += $rewardCard->discount_type === DiscountTypes::PERCENTAGE->value
                        ? ($itemTotal * $rewardCard->off_percentage / 100)
                        : min($rewardCard->off_percentage, $itemTotal);
                }
            }

            return max(0, $afterCustomerDiscount - $discountAmount);
        }

        // If no specific products attached to reward card, apply to total amount
        $rewardCardDiscount = $rewardCard->discount_type === DiscountTypes::PERCENTAGE->value
            ? ($afterCustomerDiscount * $rewardCard->off_percentage / 100)
            : min($rewardCard->off_percentage, $afterCustomerDiscount);

        return max(0, $afterCustomerDiscount - $rewardCardDiscount);
    }
} */
