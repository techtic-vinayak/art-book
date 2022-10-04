<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToReportAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_admin', function (Blueprint $table) {
             $table->enum('status', array(0, 1))->comment('0: reject, 1: approve')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_admin', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
