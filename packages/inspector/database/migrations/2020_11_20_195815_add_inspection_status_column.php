<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Incevio\Package\Inspector\Models\InspectorModel;

class AddInspectionStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (config('inspector.models', []) as $model) {
            $tableName = (new $model['class'])->getTable();

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'inspection_status')) {
                    $table->tinyInteger('inspection_status')->default(InspectorModel::INSPECTION_STATUS_APPROVED);
                }
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
        foreach (config('inspector.models', []) as $model) {
            $tableName = (new $model['class'])->getTable();

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'inspection_status')) {
                    $table->dropColumn('inspection_status');
                }
            });
        }
    }
}
