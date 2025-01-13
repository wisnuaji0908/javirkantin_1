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
    public function __construct($data, $user, $resetLink)
    {
        $this->data = $data; // Tetap kirim $data
        $this->user = $user; // Inisialisasi data user
        $this->resetLink = $resetLink; // Inisialisasi reset link
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Reset Your Password')
            ->view('auth.verify-email') // Pastikan path view sesuai
            ->with([
                'data' => $this->data, // Kirim data tambahan
                'user' => $this->user, // Kirim data user
                'reset_link' => $this->resetLink, // Kirim reset link
            ]);
    }
}
