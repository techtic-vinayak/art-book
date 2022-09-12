<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('art_id')->unsigned();
            $table->integer('to_user_id')->unsigned();
            $table->integer('from_user_id')->unsigned();
            $table->boolean('is_payment_done')->default(0)->nullable();
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
        Schema::dropIfExists('art_payments');
    }
}
