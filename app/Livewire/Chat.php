<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Models\Provider;
use App\Models\Intervention;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use MBarlow\Megaphone\Types\General;

class Chat extends Component
{
    public User $user;
    public Intervention $intervention;

    private $layout;
    public $message = '';
    
    public function render()
    {
        if (Auth::user()->provider) {
            $this->layout = 'layouts.provider';
        } else {
            $this->layout = 'layouts.app'; 
        }

        $this->dispatch('messageReceived');

        return view('livewire.chat', [
            'messages' => Message::where(function ($query) {
                        $query->where('from_user_id', auth()->id())
                              ->orWhere('from_user_id', $this->user->id)
                              ->orWhere('to_user_id', auth()->id())
                              ->orWhere('to_user_id', $this->user->id);
                    })
                    ->where('intervention_id', $this->intervention->id)
                    ->get(),
                ])
                ->layout($this->layout);
    }

    public function sendMessage() {

        $this->validate([
            'message' => 'required|string|max:255',
        ], [
            'message.required' => 'Le message ne peut pas être vide.',
            'message.max' => 'Le message ne peut pas dépasser 255 caractères.',
        ]);

        Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $this->user->id,
            'message' => $this->message,
            'intervention_id' => $this->intervention->id,
        ]);

        $notification = new General(
            'Nouveau message - Intervention #' . $this->intervention->id,
            'Un nouveau message a été envoyé',
            url('/interventions/' . $this->intervention->id . '/chat/' . $this->user->id),
            'Voir la conversation'
        );

        $this->user->notify($notification);

        $this->dispatch('messageReceived');

        $this->reset('message');
    }
}
