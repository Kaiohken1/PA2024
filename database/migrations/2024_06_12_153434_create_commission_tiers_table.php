<?php

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
        Schema::create('commission_tiers', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_amount', 8, 2);
            $table->decimal('max_amount', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2);
            $table->timestamps();
        });

        DB::table('statuts')->insert([
            ['min_amount' => 0, 'max_amount' => 5, 'percentage' => 20],
            ['min_amount' => 5, 'max_amount' => 45, 'percentage' => 15],
            ['min_amount' => 45, 'max_amount' => 75, 'percentage' => 11],
            ['min_amount' => 75, 'max_amount' => 140, 'percentage' => 9],
            ['min_amount' => 140, 'max_amount' => null, 'percentage' => 7],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_tiers');
    }
};
