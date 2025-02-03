<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\Shop;

class CartSeeder extends Seeder
{
    public function run()
    {
        $buyer = User::where('role', 'buyer')->first();
        $shop = Shop::first();
        $product = Product::first();

        if ($buyer && $shop && $product) {
            Cart::create([
                'buyer_id' => $buyer->id,
                'shop_id' => $shop->id,
                'product_id' => $product->id,
                'quantity' => 2
            ]);
        }
    }
}
