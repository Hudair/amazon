<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        if (! Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('holder');
                $table->string('name');
                $table->string('slug')->index();
                $table->string('description')->nullable();
                $table->json('meta')->nullable();
                $table->decimal('balance', 64, 6)->default(0);
                $table->boolean('blocked')->nullable()->default(Null);
                $table->timestamps();

                $table->unique(['holder_type', 'holder_id', 'slug']);
            });
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
}
