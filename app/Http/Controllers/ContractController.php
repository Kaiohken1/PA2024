<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Provider;
use App\Models\Reservation;
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



    public function generateInvoice($interventionId)
    {        
        $intervention = Intervention::findOrFail($interventionId);

        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('invoices.invoice-model', compact('intervention'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'facture_intervention_#' . $intervention->id . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function reservationInvoice($reservationId)
    {        
        $reservation = Reservation::findOrFail($reservationId);

        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('invoices.reservation-model', compact('reservation'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'facture_reservation#' . $reservation->id . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function generateFiche($interventionId)
    {        
        $intervention = Intervention::findOrFail($interventionId);

        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('pdf-models.intervention-model', compact('intervention'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'fiche_intervention_#' . $intervention->id . '.pdf', ['Content-Type' => 'application/pdf']);
    }
}
