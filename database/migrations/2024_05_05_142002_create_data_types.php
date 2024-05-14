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
        Schema::create('data_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('validation_rule');
            $table->timestamps();
        });

        DB::table('data_types')->insert([
            ['name' => 'adresse de l\'appartement', 'validation_rule' => 'required|string'],
            ['name' => 'surface de l\'appartement', 'validation_rule' => 'required|string'],
            ['name' => 'nombre de chambres de l\'appartement', 'validation_rule' => 'required|string'],
            ['name' => 'texte', 'validation_rule' => 'required|string'],
            ['name' => 'numéro', 'validation_rule' => 'required|numeric'],
            ['name' => 'email', 'validation_rule' => 'required|email'],
            ['name' => 'téléphone', 'validation_rule' => 'required|string|phone'],
            ['name' => 'texte long', 'validation_rule' => 'required|string|max:255'],
            ['name' => 'date', 'validation_rule' => 'required|date'],
            ['name' => 'case à cocher', 'validation_rule' => 'required|boolean'],
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_types');
    }
};
