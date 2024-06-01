<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('statuts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });

        DB::table('statuts')->insert([
            ['nom' => 'En attente'],
            ['nom' => 'En cours'],
            ['nom' => 'Terminée'],
            ['nom' => 'Annulée'],
            ['nom' => 'Payée'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuts');
    }
};
