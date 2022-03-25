<?php

use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        if (! Schema::hasTable('transfers')) {
            Schema::create('transfers', function (Blueprint $table) {
                $enums = [
                    Transfer::STATUS_EXCHANGE,
                    Transfer::STATUS_TRANSFER,
                    Transfer::STATUS_PAID,
                    Transfer::STATUS_REFUND,
                    Transfer::STATUS_GIFT,
                ];

                $table->bigIncrements('id');
                $table->morphs('from');
                $table->morphs('to');
                $table->enum('status', $enums)->default(Transfer::STATUS_TRANSFER);
                $table->enum('status_last', $enums)->nullable();
                $table->unsignedBigInteger('deposit_id');
                $table->unsignedBigInteger('withdraw_id');
                $table->decimal('discount', 64, 6)->default(0);
                $table->decimal('fee', 64, 6)->default(0);
                $table->uuid('uuid')->unique();
                $table->timestamps();

                $table->foreign('deposit_id')->references('id')->on('transactions')->onDelete('cascade');
                $table->foreign('withdraw_id')->references('id')->on('transactions')->onDelete('cascade');
            });
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
}
