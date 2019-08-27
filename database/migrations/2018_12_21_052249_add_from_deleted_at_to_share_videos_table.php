<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFromDeletedAtToShareVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('share_videos', function (Blueprint $table) {
            $table->timestamp('to_deleted_at')->nullable()->after('from_user_id');
            $table->timestamp('from_deleted_at')->nullable()->after('to_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share_videos', function (Blueprint $table) {
            //
        });
    }
}
