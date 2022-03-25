<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id')->unsigned()->nullable();
            $table->bigInteger('order_id')->unsigned();
            $table->boolean('order_fulfilled')->nullable();
            $table->boolean('return_goods')->nullable();
            $table->decimal('amount', 20, 6)->default(0);
            $table->longtext('description')->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
