<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class VerifiedListeners
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $user = $event->user;

        // Cek jika role masih null, tambahkan default role pembeli
        if (is_null($user->role)) {
            $user->role = 'pembeli';
            $user->save();
        }
    }
}
