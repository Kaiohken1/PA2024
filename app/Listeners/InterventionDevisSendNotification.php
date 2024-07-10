<?php

namespace App\Listeners;

use App\Models\User;
use MBarlow\Megaphone\Types\General;
use App\Events\InterventionDevisSend;
use MBarlow\Megaphone\Types\Important;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class InterventionDevisSendNotification
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
    public function handle(InterventionDevisSend $event): void
    {
        $user = User::findOrFail($event->intervention->user_id);
        $notification = new General(
            'Devis reçu',
            'Vous avez reçu un devis pour la demande d\'intervention #' . $event->intervention->id,
            url('https://www.paris-caretaker-services.store/interventions/client/' . $event->intervention->id),
            'Voir ma demande'
        );

        Notification::send($user, $notification);
    }
}
