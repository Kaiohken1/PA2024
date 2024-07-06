<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStartTimeAndEndTimeInFermeturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fermetures', function (Blueprint $table) {
            $table->renameColumn('start_time', 'start');
            $table->renameColumn('end_time', 'end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fermetures', function (Blueprint $table) {
            $table->renameColumn('start', 'start_time');
            $table->renameColumn('end', 'end_time');
        });
    }
}
