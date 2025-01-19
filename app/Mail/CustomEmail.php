<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Tetap gunakan $data untuk keperluan lain
    public $user; // Tambahkan $user untuk akses lebih spesifik
    public $resetLink; // Tetap gunakan resetLink

    /**
     * Create a new message instance.
     */
    public function __construct($data, $user, $resetLink = null)
    {
        $this->data = $data; // Tetap kirim $data
        $this->user = $user; // Inisialisasi data user
        $this->resetLink = $resetLink; // Optional: untuk reset password
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Cek apakah ini reset password atau verifikasi email
        if ($this->resetLink) {
            // Reset Password Email
            return $this->subject('Reset Your Password')
                ->view('auth.reset-password-email') // View khusus reset password
                ->with([
                    'user' => $this->user, // Data user
                    'reset_link' => $this->resetLink, // Reset link
                ]);
        } else {
            // Verify Email
            return $this->subject('Verify Your Email')
                ->view('auth.verify-email') // View khusus verifikasi email
                ->with([
                    'user' => $this->user, // Data user
                    'verification_link' => $this->data['reset_link'], // Verification link
                ]);
        }
    }
}
