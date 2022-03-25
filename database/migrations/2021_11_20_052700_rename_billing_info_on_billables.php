<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBillingInfoOnBillables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('shops', 'card_brand')) {
            Schema::table('shops', function (Blueprint $table) {
                $table->renameColumn('card_brand', 'pm_type');
                $table->renameColumn('card_last_four', 'pm_last_four');
            });
        }

        if (Schema::hasColumn('customers', 'card_brand')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->renameColumn('card_brand', 'pm_type');
                $table->renameColumn('card_last_four', 'pm_last_four');
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
        if (Schema::hasColumn('customers', 'pm_type')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->renameColumn('pm_type', 'card_brand');
                $table->renameColumn('pm_last_four', 'card_last_four');
            });
        }

        if (Schema::hasColumn('shops', 'pm_type')) {
            Schema::table('shops', function (Blueprint $table) {
                $table->renameColumn('pm_type', 'card_brand');
                $table->renameColumn('pm_last_four', 'card_last_four');
            });
        }
    }
}
