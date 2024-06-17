<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\EstimationCreated;
use MBarlow\Megaphone\Types\General;
use MBarlow\Megaphone\Types\Important;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class EstimationCreatedNotification
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
    public function handle(EstimationCreated $event): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('nom', 'admin');
        })->get();

        $notification = new General(
            'Nouvelle estimation - Intervention #' . $event->estimation->intervention->id,
            'Une nouvelle estimation de prestataire a été envoyée',
            url('/admin/interventions/' . $event->estimation->intervention->id),
            'Voir l\'intervention'
        );

        Notification::send($admins, $notification);   
    }
}
