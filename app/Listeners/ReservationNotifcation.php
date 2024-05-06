<?php

namespace App\Listeners;

use App\Events\Reservation;
use App\Models\Reservation as ModelsReservation;
use App\Notifications\NewReservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class ReservationNotifcation
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
    public function handle(Reservation $event): void
    {
        $reservationId = $event->reservation->id;
    
        $reservation = ModelsReservation::findOrFail($reservationId);
    
        $user = $reservation->appartement->user;
    
        Notification::send($user, new NewReservation($event->reservation));    
    }

}
