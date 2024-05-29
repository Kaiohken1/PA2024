<?php

use App\Models\Statut;
use App\Models\Provider;
use App\Models\Intervention;
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
        Schema::create('intervention_estimations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Intervention::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Statut::class)->constrained()->cascadeOnDelete()->default(1);
            $table->string('estimate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_estimations');
    }
};
