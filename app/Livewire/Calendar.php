<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use App\Models\Appartement;
use App\Models\Reservation;
use Illuminate\Support\Arr;

class Calendar extends Component
{
    public $fermetures = [];
    public $reservations = [];
    public $appartementId;

    public function mount($appartementId)
    {
        $this->appartementId = $appartementId;
        $this->fermetures = Fermeture::where('appartement_id', $appartementId)->get();
        $this->reservations = Reservation::where('appartement_id', $appartementId)->with('user')->get();
    }

    public function eventChange($fermetures)
    {
        if (!is_array($fermetures) || !Arr::exists($fermetures, 'id')) {
            return $this->errorResponse(1001, 'Données de fermeture invalides.');
        }

        $e = Fermeture::find($fermetures['id']);
        if (!$e) {
            return $this->errorResponse(1002, 'Fermeture non trouvée.');
        }

        $e->start = $this->formatDate($fermetures['start']);
        if (Arr::exists($fermetures, 'end')) {
            $e->end = $this->formatDate($fermetures['end']);
        }

        $e->save();
        return $this->successResponse('Fermeture mise à jour avec succès.');
    }

    private function formatDate($date)
    {
        return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function eventAdd($fermetures)
    {
        if ($this->isDateConflict($fermetures['start'], $fermetures['end'])) {
            return $this->errorResponse(1003, 'La fermeture chevauche une réservation existante.');
        }

        $fermetures['start'] = $this->formatDate($fermetures['start']);
        if (Arr::exists($fermetures, 'end')) {
            $fermetures['end'] = $this->formatDate($fermetures['end']);
        }

        $fermetures['appartement_id'] = $this->appartementId;
        Fermeture::create($fermetures);
        return $this->successResponse('Fermeture ajoutée avec succès.');
    }

    private function isDateConflict($newStart, $newEnd)
    {
        foreach ($this->reservations as $reservation) {
            if (($newStart <= $reservation->end_time && $newStart >= $reservation->start_time) ||
                ($newEnd <= $reservation->end_time && $newEnd >= $reservation->start_time) ||
                ($newStart <= $reservation->start_time && $newEnd >= $reservation->end_time)) {
                return true;
            }
        }
        return false;
    }

    public function render()
    {
        return view('livewire.calendar', [
            'fermetures' => json_encode($this->fermetures),
            'reservations' => json_encode($this->reservations)
        ]);
    }

    private function errorResponse($code, $message)
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
        ]);
    }

    private function successResponse($message)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
