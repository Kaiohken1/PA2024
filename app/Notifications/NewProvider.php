<?php

namespace App\Notifications;

use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProvider extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Vous avez reçu une nouvelle demande d\'inscription de prestataire.')
                    ->action('Voir la demande', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'prestataire_id' => $this->provider->id,
            'prestataire_nom' => $this->provider->nom,
            'message' => 'Vous avez reçu une nouvelle demande d\'inscription de prestataire.',
        ];
    }
}
