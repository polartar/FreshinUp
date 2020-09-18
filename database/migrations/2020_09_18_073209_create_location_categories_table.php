<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::table('locations', function (Blueprint $table) {
           $table->unsignedInteger('category_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_categories');

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
