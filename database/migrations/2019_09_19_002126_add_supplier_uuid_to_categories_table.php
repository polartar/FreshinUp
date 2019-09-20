<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierUuidToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('square_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('supplier_uuid')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('square_id')->nullable();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('supplier_uuid');
        });
    }
}
