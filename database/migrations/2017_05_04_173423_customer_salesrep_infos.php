<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerSalesrepInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_salesrep_info', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('customer_salesrep_id')->unsigned()->index();
            $table->foreign('customer_salesrep_id')->references('id')->on('customer_salesreps')->onDelete('cascade');

            $table->integer('offer_id')->unsigned()->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');

            $table->string('request_type')->nullable();
            $table->boolean('salesrep_approved')->default(false);

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
