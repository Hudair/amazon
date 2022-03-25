<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowItemConditionsToSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            if (!Schema::hasColumn('systems', 'show_item_conditions')) {
                $table->boolean('show_item_conditions')->nullable()
                    ->default(true)->after('max_number_of_inventory_imgs');
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
        Schema::table('systems', function (Blueprint $table) {
            if (Schema::hasColumn('systems', 'show_item_conditions')) {
                $table->dropColumn('show_item_conditions');
            }
        });
    }
}
