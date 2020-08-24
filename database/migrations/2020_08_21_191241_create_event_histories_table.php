<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_uuid')->index();
            $table->integer('status_id')->index();
            $table->string('description');
            $table->dateTime('date');
            $table->boolean('completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_histories');
    }
}
