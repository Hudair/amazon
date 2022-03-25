<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug');
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->string('compatible')->nullable();
                $table->string('dependency')->nullable();
                $table->string('version')->default('1');
                $table->boolean('active')->nullable()->default(false);
                $table->string('icon')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
