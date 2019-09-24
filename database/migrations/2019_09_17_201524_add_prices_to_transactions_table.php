<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricesToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('total_money')->nullable();
            $table->integer('total_tax_money')->nullable();
            $table->integer('total_discount_money')->nullable();
            $table->integer('total_service_charge_money')->nullable();
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'total_money',
                'total_tax_money',
                'total_discount_money',
                'total_service_charge_money',
                'square_created_at',
                'square_updated_at'
            ]);
        });
    }
}
