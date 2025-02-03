<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseChat extends Model
{
    protected $fillable = ['buyer_id', 'seller_id', 'message', 'is_read', 'created_at'];
}
