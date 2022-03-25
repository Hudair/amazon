<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSplitMoneyToPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'split_money')) {
                $table->boolean('split_money')->default(false)->after('type');
            }
        });

        if (DB::table('payment_methods')->where('code', 'stripe')->first()) {
            DB::table('payment_methods')->where('code', 'stripe')->update(['split_money' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('split_money');
        });
        // DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
