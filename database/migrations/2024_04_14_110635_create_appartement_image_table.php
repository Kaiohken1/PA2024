<?php

use App\Models\Appartement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appartement_image', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->boolean('is_main')->default(false);
            $table->integer('main_order')->nullable();
            $table->timestamps();
            $table->foreignIdFor(Appartement::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartement_image');
    }
};
