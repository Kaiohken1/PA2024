<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\PrestataireCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PrestataireCreatedNotification;

class ValidatePrestataire
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PrestataireCreated $event): void
    {
        $prestaire = $event->prestataire;

        $admins = User::whereHas('roles', function ($query) {
            $query->where('nom', 'admin');
        })->get();

        Notification::send($admins, new PrestataireCreatedNotification($prestaire));
    }
}
