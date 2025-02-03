<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'seller_id', 'sender_id', 'message', 'is_read'];

    // Relasi ke user
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Pesan terbaru (digunakan di daftar chat)
    // Relasi pesan terbaru untuk Buyer
    // Relasi pesan terbaru untuk Buyer
    public function latestMessageForBuyer()
    {
        return $this->hasOne(self::class, 'id')
            ->whereColumn('seller_id', 'seller_id')
            ->whereColumn('buyer_id', 'buyer_id')
            ->latest('created_at');
    }

    // Relasi pesan terbaru untuk Seller
    public function latestMessageForSeller()
    {
        return $this->hasOne(self::class, 'id')
            ->whereColumn('seller_id', 'seller_id')
            ->whereColumn('buyer_id', 'buyer_id')
            ->latest('created_at');
    }
}
