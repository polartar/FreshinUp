<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkStaffsToFleetMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('locations_staffs');
        Schema::create('fleet_members_staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('fleet_member_uuid')->index();
            $table->uuid('staff_uuid')->index();
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
        Schema::create('locations_staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('location_uuid')->index();
            $table->uuid('staff_uuid')->index();
            $table->timestamps();
        });
        Schema::dropIfExists('fleet_members_staffs');
    }
}
