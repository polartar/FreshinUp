<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasicInformationToStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('owner_uuid')->nullable()->index();
            $table->string('pos_system')->nullable();
            $table->unsignedInteger('size_of_truck_trailer')->nullable();
            $table->string('state_of_incorporation')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('staff_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('owner_uuid',
                'pos_system',
                'size_of_truck_trailer',
                'state_of_incorporation',
                'facebook',
                'twitter',
                'instagram',
                'staff_notes'
            );
        });
    }
}
