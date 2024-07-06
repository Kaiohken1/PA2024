<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\FermetureController;
use App\Models\Appartement;

class GenerateRecurringClosures extends Command
{
    protected $signature = 'fermetures:generate {appartementId}';
    protected $description = 'Fermetures ponctuelles';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $appartementId = $this->argument('appartementId');
        $appartement = Appartement::find($appartementId);

        if (!$appartement) {
            $this->error('Appartement non trouvé');
            return 1;
        }

        $controller = app(FermetureController::class);
        $controller->generateRecurring($appartement);

        $this->info('Fermetures ponctuelles générées avec succès');
        return 0;
    }
}
