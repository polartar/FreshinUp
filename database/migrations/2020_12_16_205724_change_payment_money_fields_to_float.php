<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaymentMoneyFieldsToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->float('amount_money')->change();
            $table->float('processing_fee_money')->change();
            $table->float('tip_money')->change();
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
            $table->unsignedInteger('amount_money')->change();
            $table->unsignedInteger('processing_fee_money')->change();
            $table->unsignedInteger('tip_money')->change();
        });
    }
}
