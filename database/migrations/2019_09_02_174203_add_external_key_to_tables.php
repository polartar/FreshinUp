<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalKeyToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_members', function (Blueprint $table) {
            $table->uuid('contractor_uuid')->nullable()->index();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('fleet_member_uuid')->nullable()->index();
            $table->uuid('location_uuid')->nullable()->index();
        });
        Schema::create('events_event_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('event_uuid')->index();
            $table->uuid('event_tag_uuid')->index();
            $table->timestamps();
        });
        Schema::create('locations_staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('location_uuid')->index();
            $table->uuid('staff_uuid')->index();
            $table->timestamps();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->uuid('location_uuid')->nullable()->index();
            $table->uuid('device_uuid')->nullable()->index();
            $table->uuid('payment_type_uuid')->nullable()->index();
            $table->uuid('customer_uuid')->nullable()->index();
            $table->uuid('event_uuid')->nullable()->index();
        });
        Schema::create('payments_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('payment_uuid')->index();
            $table->uuid('item_uuid')->index();
            $table->timestamps();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->uuid('category_uuid')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_members', function (Blueprint $table) {
            $table->dropColumn('contractor_uuid');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['fleet_member_uuid', 'location_uuid']);
        });
        Schema::dropIfExists('events_event_tags');
        Schema::dropIfExists('locations_staffs');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['location_uuid', 'device_uuid', 'payment_type_uuid', 'customer_uuid', 'event_uuid']);
        });
        Schema::dropIfExists('payments_items');
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('category_uuid');
        });
    }
}
