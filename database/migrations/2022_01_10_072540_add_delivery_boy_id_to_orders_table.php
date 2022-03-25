<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryBoyIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'delivery_boy_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedInteger('delivery_boy_id')->nullable()->after('shop_id');
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
        if (Schema::hasColumn('orders', 'delivery_boy_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('delivery_boy_id');
            });
        }
    }
}
