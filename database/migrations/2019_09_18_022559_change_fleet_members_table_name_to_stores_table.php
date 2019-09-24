<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFleetMembersTableNameToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('fleet_members', 'stores');
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('contractor_uuid');
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->uuid('supplier_uuid')->index()->nullable();
        });
        Schema::rename('fleet_members_staffs', 'stores_staffs');
        Schema::table('stores_staffs', function (Blueprint $table) {
            $table->dropColumn('fleet_member_uuid');
        });
        Schema::table('stores_staffs', function (Blueprint $table) {
            $table->uuid('store_uuid')->index()->nullable();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('fleet_member_uuid');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('host_uuid')->nullable()->index();
        });
        Schema::create('events_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('event_uuid')->index();
            $table->uuid('store_uuid')->index();
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
        Schema::rename('stores', 'fleet_members');
        Schema::table('fleet_members', function (Blueprint $table) {
            $table->dropColumn('supplier_uuid');
        });
        Schema::table('fleet_members', function (Blueprint $table) {
            $table->uuid('contractor_uuid')->index()->nullable();
        });
        Schema::rename('stores_staffs', 'fleet_members_staffs');
        Schema::table('fleet_members_staffs', function (Blueprint $table) {
            $table->dropColumn('store_uuid');
        });
        Schema::table('fleet_members_staffs', function (Blueprint $table) {
            $table->uuid('fleet_member_uuid')->index()->nullable();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('host_uuid');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('fleet_member_uuid')->nullable()->index();
        });
        Schema::dropIfExists('events_stores');
    }
}
