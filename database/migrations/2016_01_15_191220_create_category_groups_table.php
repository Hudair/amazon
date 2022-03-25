<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 100)->default('cube')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order')->default(100)->nullable();
            $table->text('meta_title')->nullable();
            $table->longtext('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('category_sub_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_group_id')->unsigned();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order')->default(100)->nullable();
            $table->text('meta_title')->nullable();
            $table->longtext('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_group_id')->references('id')->on('category_groups')->onDelete('cascade');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_sub_group_id')->unsigned();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('featured')->nullable();
            $table->integer('order')->default(100)->nullable();
            $table->text('meta_title')->nullable();
            $table->longtext('meta_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_sub_group_id')->references('id')->on('category_sub_groups')->onDelete('cascade');
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->bigInteger('product_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_sub_groups');
        Schema::dropIfExists('category_groups');
    }
}
