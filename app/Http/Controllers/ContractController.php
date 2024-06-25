<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Provider;
use App\Models\Reservation;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function reservationsGains()
    {    
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'bailleur');
        })->get();   

        foreach($users as $user) {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $reservations = Reservation::where('user_id', $user->id)
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();

            $pdf = app('dompdf.wrapper');
            
            $pdf->loadView('pdf-models.reservations-gains', compact('reservations'));

            $filePath = 'factures/' . 'facture_reservation_' . $user->id . '_' . $year . '_' . $month . '.pdf';

            Storage::put($filePath, $pdf->output());
            
            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->pdf = $filePath;
            $invoice->role = "Bailleur";
            $invoice->save();
        }

        Log::info('Reservations gains PDF généré au ' . $month . '/' . $year);
    }

    public function interventionsGains()
    {   
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'prestataire');
        })->get();   

        foreach($users as $user) {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $interventions = Intervention::where('user_id', $user->id)
                            ->where('statut_id', 3)
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();

            $pdf = app('dompdf.wrapper');
            
            $pdf->loadView('pdf-models.interventions-gains', compact('interventions'));

            $filePath = 'factures/' . 'facture_interventions_' . $user->id . '_' . $year . '_' . $month . '.pdf';

            Storage::put($filePath, $pdf->output());
            
            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->provider_id = $user->provider->id;
            $invoice->pdf = $filePath;
            $invoice->role = "Prestataire";
            $invoice->save();
        }
        Log::info('Intervention gains PDF généré au ' . $month . '/' . $year);

    }

    public function downloadReservation($userId)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $filePath = 'factures/' . 'facture_reservation_' . $userId . '_' . $year . '_' . $month . '.pdf';

        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'Le fichier n\'existe pas.'], 404);
        }

        return Storage::download($filePath);
    }

    public function downloadIntervention($userId)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $filePath = 'factures/' . 'facture_interventions_' . $userId . '_' . $year . '_' . $month . '.pdf';

        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'Le fichier n\'existe pas.'], 404);
        }

        return Storage::download($filePath);
    }

    public function downloadInvoice($id)
    {
        $invoice = Invoice::findOrfail($id);


        return Storage::download($invoice->pdf);
    }
}
