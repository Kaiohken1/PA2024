<?php

use App\Models\User;
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
        Schema::create('appartement_avis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Appartement::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Reservation::class)->constrained()->cascadeOnDelete();
            $table->integer('rating_cleanness');
            $table->integer('rating_price_quality');
            $table->integer('rating_location');
            $table->integer('rating_communication');
            $table->string('comment');
            $table->timestamps();
        });

        Schema::table('appartement_avis', function (Blueprint $table) {
            $table->unique(['user_id', 'appartement_id']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartement_avis');
    }
};
