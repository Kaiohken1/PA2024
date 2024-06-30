<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Intervention;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class InterventionsInvoices extends Command
{
    protected $signature = 'invoices:interventions';
    protected $description = 'Génère les factures des gains d\'interventions pour chaque utilisateur chaque mois';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->generateInvoice();
    }

    private function generateInvoice()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'prestataire');
        })->get();   


        foreach($users as $user) {
            if($user->provider !== NULL) {
                $month = Carbon::now()->month;
                $year = Carbon::now()->year;
                $interventions = Intervention::where('provider_id', $user->provider->id)
                                ->where('statut_id', 3)
                                ->whereMonth('created_at', $month)
                                ->whereYear('created_at', $year)
                                ->get();

                if($interventions->isNotEmpty()) {
    
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
            }
        }
    
        $this->info('Les PDF pour les interventions ont étés générés et stockées avec succès');
    }
}
