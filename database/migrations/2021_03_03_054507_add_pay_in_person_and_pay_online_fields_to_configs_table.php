<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayInPersonAndPayOnlineFieldsToConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            if (!Schema::hasColumn('configs', 'pay_online')) {
                $table->boolean('pay_online')->default(true);
                $table->boolean('pay_in_person')->default(false)->after('pay_online');
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
        Schema::table('configs', function (Blueprint $table) {
            //$table->dropColumn('vendor_order_cancellation_fee');
        });
    }
}
