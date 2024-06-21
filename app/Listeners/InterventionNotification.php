<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\Intervention;
use MBarlow\Megaphone\Types\General;
use MBarlow\Megaphone\Types\Important;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class InterventionNotification
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
    public function handle(Intervention $event): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('nom', 'admin');
        })->get();

        $notification = new General(
            'Nouvelle demande d\'intervention de prestataire',
            'Vous avez reçu une demande d\'intervention prestataire',
            url('/admin/interventions/' . $event->intervention->id),
            'Voir la demande'
        );

        Notification::send($admins, $notification);    
    }
}
