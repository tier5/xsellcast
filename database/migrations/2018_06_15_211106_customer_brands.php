<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CustomerBrands extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('customer_brands', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');
            $table->integer('brand_id')->length(11)->unsigned()->nullable();
            $table->foreign('brand_id')
                ->references('id')->on('brands');
            $table->boolean('added')->default(false);
            $table->boolean('is_appt')->default(false);
            $table->boolean('is_price')->default(false);
            $table->boolean('is_info')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //

    }
}
