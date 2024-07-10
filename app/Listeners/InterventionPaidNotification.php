<?php

namespace App\Listeners;

use App\Models\Provider;
use App\Events\InterventionPaid;
use App\Models\User;
use MBarlow\Megaphone\Types\Important;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use MBarlow\Megaphone\Types\General;

class InterventionPaidNotification
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
    public function handle(InterventionPaid $event): void
    {
        $user = User::findOrFail($event->intervention->provider->user_id);
        $notification = new General(
            'Intervention confirmée',
            'L\'intervention #' . $event->intervention->id . ' a été confirmée et payée par le client.',
            url('https://prestataire.paris-caretaker-services.store/providers/interventions/' . $event->intervention->id),
            'Voir l\'intervention'
        );

        Notification::send($user, $notification);
    }
}
