<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTotalAppFeeAndRefundedMoneyAttributesFromPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['total_money', 'app_fee_money', 'refunded_money']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('processing_fee_money')->nullable();
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
            $table->dropColumn('processing_fee_money');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('total_money')->nullable();
            $table->integer('app_fee_money')->nullable();
            $table->integer('refunded_money')->nullable();
        });
    }
}
