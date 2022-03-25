<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->text('message')->nullable();
            $table->integer('status')->default(1);
            $table->boolean('private')->default(true);
            $table->boolean('read')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_conversations');
    }
}
