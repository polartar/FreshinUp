<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveSquareTokenToStores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('square_access_token')->nullable();
            $table->string('square_refresh_token')->nullable();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('square_access_token', 'square_refresh_token');
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
            $table->dropColumn('square_access_token', 'square_refresh_token');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('square_access_token')->nullable();
            $table->string('square_refresh_token')->nullable();
        });
    }
}
