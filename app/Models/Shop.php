<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'name', 'description', 'image'];

    // Relasi ke Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Relasi ke Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
