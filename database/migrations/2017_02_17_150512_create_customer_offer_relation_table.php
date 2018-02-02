<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOfferRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_offers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('offer_id')->length(11)->unsigned()->nullable();
            $table->foreign('offer_id')
                ->references('id')->on('offers');
            
            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');

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
