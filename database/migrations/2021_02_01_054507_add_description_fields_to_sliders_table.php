<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionFieldsToSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('sliders', ['description', 'description_color', 'alt_color', 'text_position'])) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->string('description', 255)->nullable()->after('sub_title_color');
                $table->string('description_color', 20)->default('#868E8E')->after('description');
                $table->string('alt_color', 20)->default('#FED700')->after('description_color');
                $table->string('text_position', 10)->default('right')->after('order');
            });
        }
    }

    /**
     * Reverse the migrations.
     *h
     * @return void
     */
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            //$table->dropColumn('vendor_order_cancellation_fee');
        });
    }
}
