<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timezones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();
            $table->string('abbr')->nullable();
            $table->integer('offset')->nullable();
            $table->string('text')->nullable();
            $table->string('utc')->nullable();
            $table->boolean('dst')->nullable()->default(false);
            // $table->json('utc')->nullable();
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 3);
            $table->string('iso_numeric', 3)->nullable();
            $table->string('name', 255);
            $table->string('symbol', 255)->nullable();
            $table->string('disambiguate_symbol', 255)->nullable();
            // $table->string('alternate_symbols', 255)->nullable();
            $table->string('subunit', 255)->nullable();
            $table->integer('subunit_to_unit')->default(100);
            $table->boolean('symbol_first')->default(1);
            $table->string('html_entity', 255)->nullable();
            $table->string('decimal_mark', 25)->nullable();
            $table->string('thousands_separator', 25)->nullable();
            $table->integer('smallest_denomination')->default(1);
            $table->integer('priority')->nullable()->default(100);
            $table->boolean('active')->nullable()->default(false);
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('full_name', 255)->nullable();
            $table->string('capital', 255)->nullable();
            $table->integer('currency_id')->unsigned()->nullable();
            $table->integer('timezone_id')->unsigned()->nullable();
            $table->string('citizenship', 255)->nullable();
            $table->string('iso_code', 2);
            $table->string('iso_numeric', 3)->nullable();
            $table->string('calling_code', 3)->nullable();
            $table->string('flag', 6)->nullable();
            $table->boolean('eea')->nullable()->default(false);
            $table->boolean('active')->nullable()->default(false);
            $table->timestamps();

            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });

        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->string('iso_code');
            $table->string('iso_numeric')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('calling_code', 5)->nullable();
            $table->boolean('active')->nullable()->default(1);
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('timezones');
    }
}
