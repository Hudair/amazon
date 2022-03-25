<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFulfilmentTypeFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'fulfilment_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('fulfilment_type', ['deliver', 'pickup'])
                    ->default('deliver')->after('shipping_address');
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
        if (Schema::hasColumn('orders', 'fulfilment_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('fulfilment_type');
            });
        }
    }
}
