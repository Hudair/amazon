<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyChatToConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->boolean('enable_live_chat')->nullable()->default(true)->after('notify_new_disput');
            $table->boolean('notify_new_chat')->nullable()->default(true)->after('enable_live_chat');
            $table->bigInteger('support_agent')->unsigned()->nullable()->after('support_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('enable_live_chat');
            $table->dropColumn('notify_new_disput');
            $table->dropColumn('support_agent');
        });
    }
}
