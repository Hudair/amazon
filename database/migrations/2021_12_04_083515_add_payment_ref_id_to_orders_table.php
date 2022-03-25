<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentRefIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'payment_ref_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_ref_id')->nullable()->after('payment_status');
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
        if (Schema::hasColumn('orders', 'payment_ref_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_ref_id');
            });
        }
    }
}
