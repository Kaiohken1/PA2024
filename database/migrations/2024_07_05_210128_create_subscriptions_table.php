<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('name')->default('default');
            $table->string('type')->default('default');
            $table->string('stripe_id'); 
            $table->string('stripe_status'); 
            $table->string('stripe_price')->nullable(); 
            $table->integer('quantity')->nullable(); 
            $table->timestamp('trial_ends_at')->nullable(); 
            $table->timestamp('ends_at')->nullable(); 
            $table->integer('free_service_count')->default(0); 
            $table->timestamp('last_free_service_date')->nullable();
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
