<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->string('slug', 200)->unique();
            $table->string('excerpt')->nullable();
            $table->longtext('content')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('status')->default(1);
            $table->boolean('approved')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->integer('likes')->unsigned()->default(0);
            $table->integer('dislikes')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->longtext('content');
            $table->integer('blog_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('parent')->unsigned()->nullable();
            $table->boolean('approved')->default(1);
            $table->integer('likes')->unsigned()->default(0);
            $table->integer('dislikes')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blogs');
    }
}
