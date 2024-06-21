<?php

namespace App\Providers;

use App\Events\Reservation;
use App\Events\Intervention;
use App\Events\ProviderCreated;
use App\Events\InterventionDevisSend;
use App\Events\InterventionPaid;
use App\Events\EstimationCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ReservationNotifcation;
use App\Listeners\InterventionNotification;
use App\Listeners\SendProviderCreatedNotifications;
use App\Listeners\InterventionDevisSendNotification;
use App\Listeners\InterventionPaidNotification;
use App\Listeners\EstimationCreatedNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProviderCreated::class => [
            SendProviderCreatedNotifications::class,
        ],
        Reservation::class => [
            ReservationNotifcation::class,
        ],
        Intervention::class => [
            InterventionNotification::class,
        ],
        InterventionDevisSend::class => [
            InterventionDevisSendNotification::class,
        ],

        InterventionPaid::class => [
            InterventionPaidNotification::class,
        ],

        EstimationCreated::class=> [
            EstimationCreatedNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
