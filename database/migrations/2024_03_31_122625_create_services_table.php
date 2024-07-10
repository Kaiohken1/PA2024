<?php

use App\Models\Role;
use App\Models\Service;
use App\Models\Category;
use App\Models\Provider;
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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price')->nullable();
            $table->boolean('flexPrice')->default(false);
            $table->text('description');
            $table->boolean('active_flag')->default(false);
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Role::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('provider_services', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('price_scale')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('provider_services');
        Schema::dropIfExists('services');
    }
};
