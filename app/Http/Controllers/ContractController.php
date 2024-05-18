<?php

namespace App\Http\Controllers;

use App\Models\Provider;
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
}
