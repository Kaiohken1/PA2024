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
        Schema::create('intervention_refusals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Intervention::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Statut::class)->constrained()->cascadeOnDelete();
            $table->text('refusal_reason');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('estimate');
            $table->timestamp('planned_date')->nullable();
            $table->timestamp('planned_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_refusals');
    }
};
