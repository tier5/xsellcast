<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomersalesrepUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('customer_salesrep_info');

        Schema::table('customer_salesreps', function($t) {
            $t->boolean('approved')->default(false);
            $t->boolean('rejected')->default(false);
        });     

        Schema::table('customer_offers', function($t) {
            $t->boolean('added')->default(false);
            $t->boolean('requested')->default(false);
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
