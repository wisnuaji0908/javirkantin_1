<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class CustomVerifyEmail extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        // Generate URL verifikasi menggunakan bawaan Laravel
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address') // Judul email
            ->view('auth.verify-email', [         // View Blade kustom
                'user' => $notifiable,            // Data pengguna
                'url' => $verificationUrl,        // URL verifikasi
            ]);
    }
}
