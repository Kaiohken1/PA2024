<?php

use App\Models\User;
use App\Models\Subscription;
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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('monthly_price');
            $table->integer('annual_price');
            $table->integer('permanent_discount');
            $table->integer('renewal_bonus');
            $table->string('logo');
            $table->timestamps();
        });

        Schema::create('users_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Subscription::class)->constrained()->cascadeOnDelete();
            $table->integer('free_service_count')->default(0);
            $table->timestamp('last_free_service_date')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};
