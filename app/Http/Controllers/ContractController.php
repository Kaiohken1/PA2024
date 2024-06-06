<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Provider;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class ContractController extends Controller
{
    public function generateContract($providerId)
    {
        $provider = Provider::findOrFail($providerId);
        
        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('provider.contract-template', compact('provider'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'contrat_prestataire_' . $provider->name . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateIntervention($providerId)
    {
        $provider = Provider::findOrFail($providerId);
        
        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('provider.contract-template', compact('provider'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'contrat_prestataire_' . $provider->name . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function store(Request $request) {

        $validatedData = $request->validate([
            'intervention_id' => ['required', 'exists:interventions,id'],
            'fiche' => ['required', 'image'],
            'comment' => ['nullable', 'string'],
        ]);

        $doc = $request->file('fiche');
        $path = $doc->store('InterventionsFiche', 'public');
        $validatedData['fiche'] = $path;

        $intervention = Intervention::findOrFail($validatedData['intervention_id']);

        isset($validatedData['comment']) ? $intervention->comment = $validatedData['comment'] : '';

        $intervention->fiche = $path;
        $intervention->statut_id = 3;

        $intervention->save();


        return back()->with('success','Fiche enregistrée');
    }
}
