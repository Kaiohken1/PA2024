<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Presta\Prestataire;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PrestataireCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Prestataire $prestataire)
    {
        $this->prestataire = $prestataire;
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
                    ->action('Notification Action', url('/'))
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
            'prestataire_id' => $this->prestataire->id,
            'prestataire_nom' => $this->prestataire->nom,
            'message' => 'Vous avez reçu une nouvelle demande d\'inscription de prestataire.',
        ];
    }
}
