<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsOwnerModelAndOwnerUuidColumnInAppmstrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appmstr', function (Blueprint $table) {
            $table->string('settings_owner_model');
            $table->uuid('owner_uuid');
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
