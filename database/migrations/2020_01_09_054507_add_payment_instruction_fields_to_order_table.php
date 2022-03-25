<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentInstructionFieldsToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'payment_instruction')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('payment_instruction')->nullable()->after('shipping_address');
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
        if (Schema::hasColumn('orders', 'payment_instruction')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_instruction');
            });
        }
    }
}
