<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use App\Models\Appartement;
use App\Models\Intervention;
use App\Models\InterventionEvent;
use App\Models\Reservation;
use Illuminate\Support\Arr;

class Calendar extends Component
{
    public $fermetures = [];
    public $reservations = [];
    public $interventions = [];
    public $appartementId;

    protected $listeners = ['createClosure', 'updateClosure'];


    public function createClosure($date, $reason, $appartementId)
    {
        Fermeture::create([
            'comment' => $reason,
            'start' => $date,
            'end' => $date,
            'appartement_id' => $appartementId, 
        ]);

        return redirect(request()->header('Referer'));
    }

    public function updateClosure($id, $start, $end)
    {
        $fermeture = Fermeture::find($id);
        if ($fermeture) {
            $fermeture->start = date('Y-m-d', strtotime($start . ' +1 day'));
            $fermeture->end = date('Y-m-d', strtotime($end . ' +1 day'));;
            $fermeture->save();
        }

        return redirect(request()->header('Referer'));

    }


    public function render()
    {
        $fermetures = Fermeture::where('appartement_id', $this->appartementId)->get();
        $reservations = Reservation::where('appartement_id', $this->appartementId)->with('user')->get();
        $interventions = InterventionEvent::where('appartement_id', $this->appartementId)->get();

        $formatFermeture = [];
        foreach($fermetures as $fermeture) {
            $formatFermeture[] = [
                'id' => $fermeture->id,
                'title' => $fermeture->comment,
                'start' => date('Y-m-d', strtotime($fermeture->start)),
                'end' => date('Y-m-d', strtotime($fermeture->end)),
                'color' => '#FF0000',
                'type' => "closure",
            ];
        }


        $formatReservation = [];
        foreach($reservations as $reservation) {
            $start = date('Y-m-d', strtotime($reservation->start_time));
            $end = date('Y-m-d', strtotime($reservation->end_time . ' +1 day'));
        
            $formatReservation[] = [
                'id' => $reservation->id,
                'title' => 'Reservation #' . $reservation->id,
                'start' => $start,
                'end' => $end,
                'color' => '#00AA00',
                'type' => "reservation",
            ];
        }

        $formatInterventions = [];
        foreach($interventions as $intervention) {
            $formatInterventions[] = [
                'id' => $intervention->id,
                'title' => $intervention->title,
                'start' => date('Y-m-d H:i:s', strtotime($intervention->start)),
                'end' => date('Y-m-d H:i:s', strtotime($intervention->end)),
                'type' => "intervention",
                'intervention_id' => $intervention->intervention_id,
            ];
        }

        $this->fermetures = json_encode($formatFermeture);
        $this->reservations = json_encode($formatReservation);
        $this->interventions = json_encode($formatInterventions);
        return view('livewire.calendar', ['appartementId' => $this->appartementId]);
    }
}
