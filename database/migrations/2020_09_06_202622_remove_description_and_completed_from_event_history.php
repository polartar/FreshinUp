<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDescriptionAndCompletedFromEventHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_histories', function (Blueprint $table) {
            $table->dropColumn('description', 'completed', 'date');
        });
        Schema::table('event_histories', function (Blueprint $table) {
            $table->timestamp('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_histories', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->boolean('completed')->default(false);
            $table->dateTime('date')->nullable()->change();
        });
    }
}
