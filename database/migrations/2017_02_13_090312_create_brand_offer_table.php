<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_offers', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('offer_id')->length(11)->unsigned()->nullable();
            $table->foreign('offer_id')
                ->references('id')->on('offers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->integer('brand_id')->length(11)->unsigned()->nullable();
            $table->foreign('brand_id')
                ->references('id')->on('brand_categories')
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