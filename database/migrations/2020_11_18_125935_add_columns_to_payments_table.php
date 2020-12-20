<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->date('due_date')->nullable();
            $table->string('description')->nullable();
            $table->integer('status_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('name', 'due_date', 'description', 'status_id');
        });
    }
}
