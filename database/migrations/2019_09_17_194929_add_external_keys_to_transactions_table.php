<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalKeysToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payments_items');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['customer_uuid', 'event_uuid', 'square_updated_at']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->uuid('transaction_uuid')->nullable()->index();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->uuid('customer_uuid')->nullable()->index();
            $table->uuid('event_uuid')->nullable()->index();
        });
        Schema::create('transactions_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('transaction_uuid')->index();
            $table->uuid('item_uuid')->index();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_items');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_uuid', 'event_uuid']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_uuid']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dateTime('square_updated_at')->nullable();
            $table->uuid('customer_uuid')->nullable()->index();
            $table->uuid('event_uuid')->nullable()->index();
        });
        Schema::create('payments_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('payment_uuid')->index();
            $table->uuid('item_uuid')->index();
            $table->timestamps();
        });
    }
}
