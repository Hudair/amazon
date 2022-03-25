<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigPaypalExpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_paypal_express', function (Blueprint $table) {
            $table->integer('shop_id')->unsigned()->index();
            // $table->text('api_username')->nullable();
            // $table->text('api_password')->nullable();
            // $table->text('signature')->nullable();
            $table->text('account')->nullable();
            $table->text('client_id')->nullable();
            $table->text('secret')->nullable();
            $table->boolean('sandbox')->nullable()->default(true);
            $table->timestamps();

            $table->primary('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_paypal_express');
    }
}
