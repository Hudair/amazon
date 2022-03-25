<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigAuthorizeNetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_authorize_net', function (Blueprint $table) {
            $table->integer('shop_id')->unsigned()->index();
            $table->text('api_login_id')->nullable();
            $table->text('transaction_key')->nullable();
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
        Schema::dropIfExists('config_authorize_net');
    }
}
