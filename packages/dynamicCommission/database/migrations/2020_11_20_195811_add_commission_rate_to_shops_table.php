<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionRateToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            if (!Schema::hasColumns('shops', ['periodic_sold_amount', 'commission_rate'])) {
                $table->decimal('periodic_sold_amount', 20, 6)->after('address_verified')->default(0);
                $table->decimal('commission_rate', 8, 2)->nullable()->default(Null)->after('periodic_sold_amount');
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
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumns('shops', ['periodic_sold_amount', 'commission_rate'])) {
                $table->dropColumn('commission_rate');
                $table->dropColumn('periodic_sold_amount');
            }
        });
    }
}
