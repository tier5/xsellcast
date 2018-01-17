<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_brands', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dealer_id')->length(11)->unsigned()->nullable();
            $table->foreign('dealer_id')
                ->references('id')->on('dealers')
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
