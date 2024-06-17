<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Fermeture;
use App\Models\Reservation;
use Illuminate\Support\Facades\Request;

class CalendarComponent extends Component
{
    public $closureTitle;
    public $closureStart;
    public $closureDays;
    public $appartement_id;

    protected $rules = [
        'closureTitle' => 'required|string|max:255',
        'closureStart' => 'required|date',
        'closureDays' => 'required|integer|min:1',
    ];

    public function mount()
    {
        // Récupérer l'ID de l'appartement à partir de l'URL
        $this->appartement_id = Request::query('appartement_id');
    }

    public function render()
    {
        $fermetures = Fermeture::where('appartement_id', $this->appartement_id)->get();
        $reservations = Reservation::with('user')->where('appartement_id', $this->appartement_id)->get();

        return view('livewire.calendar-component', [
            'fermetures' => $fermetures,
            'reservations' => $reservations,
            'appartement_id' => $this->appartement_id,
        ]);
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
            'appartement_id' => $this->appartement_id, 
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

