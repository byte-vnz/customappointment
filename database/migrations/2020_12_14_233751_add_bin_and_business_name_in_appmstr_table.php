<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinAndBusinessNameInAppmstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appmstr', function (Blueprint $table) {
            $table->string('bin');
            $table->string('business_name');
            $table->dropColumn('bussname');
            $table->dropColumn('remarks');
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
