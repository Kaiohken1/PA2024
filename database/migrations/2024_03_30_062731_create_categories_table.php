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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('categories')->insert([
            ['name' => 'Services de ménage'],
            ['name' => 'Transport et mobilité'],
            ['name' => 'Livraison et courses'],
            ['name' => 'Maintenance et réparation'],
            ['name' => 'Conciergerie et assistance'],
            ['name' => 'Services de bien-être'],
            ['name' => 'Services de sécurité'],
            ['name' => 'Services familiaux'],
            ['name' => 'Location de matériel et équipements'],
            ['name' => 'Services d\'événementiel'],
            ['name' => 'Autre'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
