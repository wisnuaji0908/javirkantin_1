<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Notifications\CustomVerifyEmail;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Barang' => 'App\Policies\BarangPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Custom email verification
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new CustomVerifyEmail())->toMail($notifiable);
        });
    }
}
