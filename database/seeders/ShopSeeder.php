<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;

class ShopSeeder extends Seeder
{
    public function run()
    {
        // Ambil seller pertama
        $seller = User::where('role', 'seller')->first();

        if ($seller) {
            Shop::create([
                'seller_id' => 1, // Sesuaikan dengan seller yang ada
                'name' => 'Warung Kantin ABC',
                'description' => 'Kantin yang menjual berbagai makanan',
                'image' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
