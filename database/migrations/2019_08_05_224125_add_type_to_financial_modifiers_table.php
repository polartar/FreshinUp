<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToFinancialModifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_modifiers', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('filter')->nullable();
            $table->string('value_param')->nullable();
            $table->string('text_param')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_modifiers', function (Blueprint $table) {
            $table->dropColumn(['type', 'filter', 'value_param', 'text_param']);
        });
    }
}
