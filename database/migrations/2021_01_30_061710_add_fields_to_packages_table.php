<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('installation_key', 'installation_hash')) {
                $table->string('license_key')->after('version');
                $table->string('installation_key')->after('license_key');
                $table->string('installation_hash')->after('installation_key');
                $table->string('lcd')->after('installation_hash');
                $table->string('lrd')->after('lcd');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
}
