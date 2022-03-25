<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationSentFieldToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumns('subscriptions', ['notification_sent_at'])) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->timestamp('notification_sent_at')->nullable()->after('ends_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumns('subscriptions', ['notification_sent_at'])) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('notification_sent_at');
            });
        }
    }
}
