<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOfferActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_offer_action', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('customer_offer_id')->unsigned()->index();
            $table->foreign('customer_offer_id')->references('id')
                ->on('customer_offers')
                ->onDelete('cascade');

            $table->enum('action', ['ADDED', 'REQUESTED_INFO', 'REQUESTED_APPT']);
            $table->text('note')->nullable();

            $table->softDeletes();
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
