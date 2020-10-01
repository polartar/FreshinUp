<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDocumentTypeToTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('type', 'type_id');
            $table->index('type_id');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('status', 'status_id');
            $table->index('status_id');
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
            $table->renameColumn('type_id', 'type');
            $table->dropIndex(['documents_type_id_index']);
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->renameColumn('status_id', 'status');
            $table->dropIndex(['documents_status_id_index']);
        });
    }
}
