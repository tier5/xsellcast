<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerMediaRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('customer_medias', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('media_id')->length(11)->unsigned()->nullable();
            $table->foreign('media_id')
                ->references('id')->on('media');

            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_medias');
    }
}
