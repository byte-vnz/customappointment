<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bin_schedules', function (Blueprint $table) {
            $table->uuid('bin_schedule_uuid')->primary();
            $table->string('name')->nullable()->default(null);
            $table->string('key');
            $table->string('value')->nullable()->default(null);
            $table->date('date_from');
            $table->date('date_to');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bin_schedules');
    }
}
