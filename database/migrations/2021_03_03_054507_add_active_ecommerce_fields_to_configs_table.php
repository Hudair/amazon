<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveEcommerceFieldsToConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            if (!Schema::hasColumn('configs', 'active_ecommerce')) {
                $table->boolean('active_ecommerce')->default(true);
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
            if (Schema::hasColumn('configs', 'active_ecommerce')) {
                $table->dropColumn('active_ecommerce');
            }
        });
    }
}
