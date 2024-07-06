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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attributed_user_id')->nullable()->constrained('users');
            $table->foreignId('attributed_role_id')->constrained('roles');
            $table->foreignId('asker_user_id')->constrained('users');
            $table->String('subject');
            $table->text('description');
            $table->integer('priority')->default(0);
            $table->String('status')->default('En attente');
            $table->text('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
