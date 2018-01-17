<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesrepOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesrep_offers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('offer_id')->unsigned()->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');

            $table->integer('salesrep_id')->unsigned()->index();
            $table->foreign('salesrep_id')->references('id')->on('user_salesreps')->onDelete('cascade');

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
        //
    }
}
