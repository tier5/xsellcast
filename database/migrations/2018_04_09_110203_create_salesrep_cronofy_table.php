<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesrepCronofyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesrep_cronofy', function($t) {
            $t->increments('id');
            $t->integer('salesrep_id')->unsigned()->index();
            $t->foreign('salesrep_id')->references('id')->on('user_salesreps')->onDelete('cascade');
            $t->string('client_id')->nullable();
            $t->string('client_secret')->nullable();
            $t->string('token')->nullable();
            $t->string('calendar_name')->nullable();
            $t->string('calendar_id')->nullable();
            $t->timestamps();
            $t->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('salesrep_cronofy');
    }
}
