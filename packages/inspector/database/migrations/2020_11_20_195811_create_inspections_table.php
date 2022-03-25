<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Incevio\Package\Inspector\Models\InspectorModel;

class CreateInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('inspections')) {
            Schema::create('inspections', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('inspectable');
                $table->longText('caught');
                $table->tinyInteger('status')->default(InspectorModel::INSPECTION_STATUS_PENDING);
                $table->integer('attempts')->default(1);
                $table->timestamps();
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
        Schema::dropIfExists('inspections');
    }
}
