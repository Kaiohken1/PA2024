<?php

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
        Schema::create('hidden_interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Intervention::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hidden_interventions');
    }
};
