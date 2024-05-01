<?php

use App\Models\Provider;
use App\Models\Service;
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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->boolean('flexPrice')->default(false);
            $table->string('habilitationImg');
            $table->text('description');
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_services');
        Schema::dropIfExists('services');
    }
};
