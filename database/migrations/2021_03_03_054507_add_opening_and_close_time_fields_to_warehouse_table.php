<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOpeningAndCloseTimeFieldsToWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouses', 'opening_time')) {
                $table->string('opening_time', 20)->default(0)->after('description');
                $table->string('close_time', 20)->default(0)->after('opening_time');
                $table->string('business_days', 200)->nullable()->after('close_time');
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
        Schema::table('warehouses', function (Blueprint $table) {
            //$table->dropColumn('vendor_order_cancellation_fee');
        });
    }
}
