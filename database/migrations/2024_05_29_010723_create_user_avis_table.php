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
        Schema::create('user_avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')->constrained('users');
            $table->foreignId('receiver_user_id')->constrained('users');
            $table->integer('rating');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::table('user_avis', function (Blueprint $table) {
            $table->unique(['sender_user_id', 'receiver_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_avis');
    }
};
