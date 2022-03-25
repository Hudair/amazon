<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        if (! Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('payable');
                $table->unsignedBigInteger('wallet_id')->nullable();
                $table->enum('type', ['deposit', 'withdraw'])->index();
                $table->decimal('amount', 64, 6);
                $table->decimal('balance', 64, 6);
                $table->boolean('confirmed');
                $table->boolean('approved')->nullable();
                $table->json('meta')->nullable();
                $table->uuid('uuid')->unique();
                $table->timestamps();

                $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');

                $table->index(['payable_type', 'payable_id', 'type'], 'payable_type_ind');
                $table->index(['payable_type', 'payable_id', 'confirmed'], 'payable_confirmed_ind');
                $table->index(['payable_type', 'payable_id', 'type', 'confirmed'], 'payable_type_confirmed_ind');
            });
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
