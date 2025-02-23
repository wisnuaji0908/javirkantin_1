<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['buyer_id', 'seller_id', 'order_id', 'product_id', 'quantity', 'toppings', 'total_price', 'status', 'order_status', 'payment_method', 'note'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductsAttribute()
    {
        return json_decode($this->toppings, true) ?? [];
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    // Relasi ke Seller (User)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}

