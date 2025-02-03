<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected
    $fillable = ['name', 'description', 'price', 'stock', 'image', 'seller_id',];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id'); // 'seller_id' adalah foreign key di tabel products
    }
}
