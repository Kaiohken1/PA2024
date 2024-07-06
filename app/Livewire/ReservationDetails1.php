<?php

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Livewire\Component;

class ReservationDetails1 extends Component
{
    public $reservations = [];
    public $appartement_id = null;

    protected $listeners = ['showReservationDetails'];

    public function showReservationDetails(Request $request)
    {
        $this->appartement_id = $request->input('appartement_id'); 

        $reservationQuery = Reservation::query();

        if ($this->appartement_id !== null) {
            $reservationQuery->where('appartement_id', $this->appartement_id);
        }

        $this->reservations = $reservationQuery->get();

       
        return response()->json($this->reservations);
    }

    public function render()
    {
        return view('livewire.reservation-details1');
    }
}
