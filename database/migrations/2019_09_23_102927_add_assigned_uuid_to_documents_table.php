<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignedUuidToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->integer('assigned_type')->default(1);
            $table->string('assigned_fleet_member_uuid')->nullable()->index();
            $table->string('assigned_event_uuid')->nullable()->index();
            $table->string('assigned_venue_uuid')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['assigned_type']);
            $table->dropColumn(['assigned_fleet_member_uuid']);
            $table->dropColumn(['assigned_event_uuid']);
            $table->dropColumn(['assigned_venue_uuid']);
        });
    }
}
