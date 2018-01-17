<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCustomerOffersFieldsSrOnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_offers', function($t) {
            $t->dropForeign(['salesrep_id']);
            $t->dropColumn('salesrep_id');
            $t->dropColumn('salesrep_approved');
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
