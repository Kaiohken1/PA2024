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
        $users = User::whereHas('roles', function ($query) {
            $query->where('nom', 'bailleur');
        })->get();   

        foreach ($users as $user) {
            $this->generateInvoice($user->id);
        }
    }

    private function generateInvoice($userId)
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $reservations = Reservation::where('user_id', $userId)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->get();

        $pdf = app('dompdf.wrapper');
        
        $pdf->loadView('pdf-models.reservations-gains', compact('reservations'));

        $filePath = 'factures/' . 'facture_reservation_' . $userId . '_' . $year . '_' . $month . '.pdf';

        Storage::put($filePath, $pdf->output());
        
        $invoice = new Invoice();
        $invoice->user_id = $userId;
        $invoice->pdf = $filePath;
        $invoice->role = "Bailleur";
        $invoice->save();
    
        $this->info('Le PDF pour les réservations a été généré et stocké avec succès pour l\'utilisateur ' . $userId);
    }
}
