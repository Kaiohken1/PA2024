<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use Illuminate\Support\Arr;

class Calendar extends Component
{
    public $fermetures = [];

    public function eventChange($fermetures)
    {
        // Vérifier que $fermetures est un tableau et qu'il contient la clé 'id'
        if (!is_array($fermetures) || !Arr::exists($fermetures, 'id')) {
            return $this->errorResponse(1001, 'Données de fermeture invalides.');
        }

        // Trouver l'instance Fermeture correspondante
        $e = Fermeture::find($fermetures['id']);
        
        // Vérifier si l'instance Fermeture a été trouvée
        if (!$e) {
            return $this->errorResponse(1002, 'Fermeture non trouvée.');
        }

        // Mettre à jour les dates de l'instance Fermeture
        $e->start = $fermetures['start'];
        
        if (Arr::exists($fermetures, 'end')) {
            $e->end = $fermetures['end'];
        }

        // Enregistrer les modifications
        $e->save();

        return $this->successResponse('Fermeture mise à jour avec succès.');
    }

    public function render()
    {
        $this->fermetures = json_encode(Fermeture::all());
        return view('livewire.calendar');
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

    public function eventAdd($fermetures)
{

    $appartement_id = $this->getAppartementId();

    // Ajoutez l'ID de l'appartement aux données de fermeture
    $fermetures['appartement_id'] = $appartement_id;
    Fermeture::create($fermetures);
}

private function getAppartementId()
{
    
    return 1; 
}
}
