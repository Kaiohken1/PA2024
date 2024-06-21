<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Intervention;

class RedirectAfterPayment extends Component
{
    public Intervention $intervention;
    public $token;

    public function mount(Intervention $intervention, $token)
    {
        $this->intervention = $intervention;
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.redirect-after-payment');
    }

    public function redirectToPlan()
    {
        return redirect()->route('interventions.plan', ['id' => $this->intervention->id, 'token' => $this->token]);
    }

    public function hydrate()
    {
        $this->redirectToPlan();
    }
}
