<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CalendarComponent extends Component
{
    public $reservation;

    protected $listeners = ['loadReservation', 'createClosure'];

    public function loadReservation($id)
    {
        $this->reservation = Reservation::findOrFail($id);

    }

    public function render()
    {
        return view('livewire.calendar-component');
    }
}

