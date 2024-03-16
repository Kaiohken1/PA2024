<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('iban')->nullable();
            $table->string('abonnement')->default('Gratuit');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('adresse');
            $table->dropColumn('code_postal');
            $table->dropColumn('ville');
            $table->dropColumn('iban');
            $table->dropColumn('abonnement');

        });
    }
};
