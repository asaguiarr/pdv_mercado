<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        $product->sale_price = $product->cost_price * (1 + $product->profit_margin / 100);
    }

    public function updating(Product $product)
    {
        if ($product->isDirty('cost_price') || $product->isDirty('profit_margin')) {
            $product->sale_price = $product->cost_price * (1 + $product->profit_margin / 100);
        }
    }
}
