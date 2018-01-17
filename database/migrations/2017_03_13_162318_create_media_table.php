<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('path');
            $table->longText('slug');
            $table->string('extension');
            $table->timestamps();
        });

        Schema::create('media_meta', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('media_id')->unsigned()->index();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

            $table->string('type')->default('null');
            $table->string('key')->index();
            $table->text('value')->nullable();

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
