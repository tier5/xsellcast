<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationOnCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_customer', function($t) {
            $t->text('address1')->nullable();
            $t->text('address2')->nullable();
            $t->text('zip')->nullable();
            $t->text('city')->nullable();
            $t->text('state')->nullable();
            $t->text('country')->nullable();
            $t->text('geo_long')->nullable();
            $t->text('geo_lat')->nullable();
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
