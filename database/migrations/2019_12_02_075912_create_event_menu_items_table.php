<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->index();
            $table->uuid('event_uuid');
            $table->uuid('store_uuid');
            $table->uuid('menu_uuid');
            $table->string('item');
            $table->integer('servings')->nullable();            
            $table->integer('cost')->nullable();
            $table->text('description')->nullable();
            $table->integer('flag')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_menu_items');
    }
}
