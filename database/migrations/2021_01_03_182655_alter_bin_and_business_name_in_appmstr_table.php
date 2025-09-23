<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBinAndBusinessNameInAppmstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appmstr', function (Blueprint $table) {
            $table->string('bin')->nullable()->change();
            $table->string('business_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appmstr', function (Blueprint $table) {
            //
        });
    }
}
