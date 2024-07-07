<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReservationsInvoices extends Command
{
    protected $signature = 'invoices:reservations';
    protected $description = 'Génère les factures des gains de réservations pour chaque utilisateur chaque mois';

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
            $query->where('nom', 'bailleur');
        })->get();  

        foreach($users as $user) {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $reservations = Reservation::whereHas('appartement', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();
            if($reservations->isNotEmpty()) {
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
        }
    
        $this->info('Les PDF pour les factures de réservations ont étés générés et stockées avec succès');
    }
}
