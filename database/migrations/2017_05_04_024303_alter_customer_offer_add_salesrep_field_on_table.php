<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerOfferAddSalesrepFieldOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_offers', function($t) {
            $t->integer('salesrep_id')->unsigned()->nullable();
            $t->foreign('salesrep_id')->references('id')->on('user_salesreps')->onDelete('cascade');
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
