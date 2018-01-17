<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSalesrepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_salesrep', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dealer_id')->length(11)->unsigned()->nullable();
            $table->foreign('dealer_id')
                ->references('id')->on('dealers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->integer('salesrep_id')->length(11)->unsigned()->nullable();
            $table->foreign('salesrep_id')
                ->references('id')->on('user_salesreps')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
