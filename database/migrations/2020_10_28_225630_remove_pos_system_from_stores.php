<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePosSystemFromStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('pos_system');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->string('square_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('pos_system')->nullable();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->integer('square_id')->nullable()->change();
        });
    }
}
