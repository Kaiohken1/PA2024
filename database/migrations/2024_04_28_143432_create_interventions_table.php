<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Reservation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Appartement::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Reservation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Provider::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->enum('user_type', ['voyageur', 'bailleur']);            
            $table->text("description");
            $table->integer('price');
            $table->enum('statut', ['en_attente', 'en_cours', 'terminée', 'annulée'])->default('en_attente');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
