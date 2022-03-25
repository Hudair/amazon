<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvgFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avg_feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('rating')->nullable();
            $table->bigInteger('count')->unsigned()->nullable();
            $table->bigInteger('feedbackable_id')->unsigned();
            $table->string('feedbackable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avg_feedback');
    }
}
