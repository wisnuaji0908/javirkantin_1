<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URL yang dikecualikan dari CSRF protection
     *
     * @var array
     */
    protected $except = [
        'api/midtrans/callback', // Tambahin Midtrans Callback biar nggak kena CSRF
    ];
}
