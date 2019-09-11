<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('amount_money')->nullable();
            $table->integer('tip_money')->nullable();
            $table->integer('total_money')->nullable();
            $table->integer('app_fee_money')->nullable();
            $table->integer('refunded_money')->nullable();
            $table->dateTime('square_created_at')->nullable();
            $table->dateTime('square_updated_at')->nullable();
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
            $table->dropColumn([
                'amount_money',
                'tip_money',
                'total_money',
                'app_fee_money',
                'refunded_money',
                'square_created_at',
                'square_updated_at'
            ]);
        });
    }
}
