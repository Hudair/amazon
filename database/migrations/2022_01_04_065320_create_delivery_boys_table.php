<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryBoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id')->unsigned()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('nice_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->boolean('status')->default(false);
            $table->date('dob')->nullable();
            $table->string('sex')->nullable();
            $table->string('verification_token', 100)->nullable();
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')
                ->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_boys');
    }
}
