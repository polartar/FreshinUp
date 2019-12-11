<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionToEventsStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_stores', function (Blueprint $table) {
            $table->integer('commission_rate')->nullable();
            $table->integer('commission_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_stores', function (Blueprint $table) {
            $table->dropColumn([
                'commission_rate',
                'commission_type'
            ]);
        });
    }
}
