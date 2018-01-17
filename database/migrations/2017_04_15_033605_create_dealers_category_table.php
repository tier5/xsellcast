<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealersCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers_category', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug');

            $table->timestamps();
        });

        Schema::create('dealers_category_relation', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('dealers_category')->onDelete('cascade');

            $table->integer('dealer_id')->unsigned()->index();
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');            

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
