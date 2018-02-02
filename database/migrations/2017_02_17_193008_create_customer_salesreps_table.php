<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSalesrepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_salesreps', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('salesrep_id')->length(11)->unsigned()->nullable();
            $table->foreign('salesrep_id')
                ->references('id')->on('user_salesreps');
            
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
