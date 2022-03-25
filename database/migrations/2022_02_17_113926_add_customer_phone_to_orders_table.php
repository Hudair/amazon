<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerPhoneToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('orders', ['customer_phone_number'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_phone_number')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumns('orders', ['customer_phone_number'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('customer_phone_number');
            });
        }
    }
}
