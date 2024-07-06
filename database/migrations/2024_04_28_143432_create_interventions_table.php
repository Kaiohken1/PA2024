<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Reservation;
use App\Models\Statut;
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
            $table->foreignIdFor(Provider::class)->nullable()->constrained();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->enum('user_type', ['voyageur', 'bailleur']);
            $table->text("description")->nullable();
            $table->integer('price')->nullable();
            $table->decimal('commission', 8, 2)->nullable();
            $table->foreignIdFor(Statut::class)->constrained()->cascadeOnDelete()->default(1);
            $table->text('comment')->nullable();
            $table->text('fiche')->nullable();
            $table->integer('service_version');
            $table->dateTime('planned_date')->nullable();
            $table->dateTime('planned_end_date')->nullable();
            $table->dateTime('max_end_date')->nullable();
            $table->softDeletes();
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
