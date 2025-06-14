<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product)
    {
        if (!$product->auto_code) {
            $category = $product->category;
            if ($category) {
                $product->auto_code = $category->generateProductCode();
            }
        }
    }
}
