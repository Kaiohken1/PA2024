<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Fermeture;
use App\Models\Reservation;

class CalendarComponent extends Component
{
    public $closureTitle;
    public $closureStart;
    public $closureDays;

    protected $rules = [
        'closureTitle' => 'required|string|max:255',
        'closureStart' => 'required|date',
        'closureDays' => 'required|integer|min:1',
    ];

    public function render()
    {
        $fermetures = Fermeture::all(); // Retrieve closures from the database
        $reservations = Reservation::with('user')->get(); // Retrieve reservations from the database

        return view('livewire.calendar-component', compact('fermetures', 'reservations'));
    }

    public function createClosure()
    {
        $this->validate();

        $startDate = Carbon::createFromFormat('Y-m-d', $this->closureStart);
        $endDate = $startDate->copy()->addDays($this->closureDays - 1);

        // Save the closure to the database
        Fermeture::create([
            'title' => $this->closureTitle,
            'start' => $startDate,
            'end' => $endDate,
        ]);

        // Trigger the event to update the calendar
        $this->emit('closureAdded');

        // Reset the form fields
        $this->closureTitle = '';
        $this->closureStart = '';
        $this->closureDays = '';

        // Close the modal
        $this->dispatchBrowserEvent('close-modal');
    }
}
