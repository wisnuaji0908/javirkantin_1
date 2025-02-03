<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'product_id', 'quantity'];

    // Relasi ke Buyer
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Relasi ke Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
