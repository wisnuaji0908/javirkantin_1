<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id_profile',
        'qr_login_token',
        'email_verified_at',
        'is_blocked',
        'review_notification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'qr_login_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship with Profile (Toko).
     */
    public function toko()
    {
        return $this->belongsTo(Profile::class, 'id_profile', 'id');
    }

    /**
     * Relationship with Staff (if applicable).
     */
    // User.php
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'id'); // 'seller_id' adalah foreign key di tabel products
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }
}
