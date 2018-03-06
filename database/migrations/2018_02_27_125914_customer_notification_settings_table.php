<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_notification_news', function (Blueprint $table) {
            $table->increments('id');

            // $table->enum('notification_type',['1','2','3'])->comment('1 = new features and news, 2= National Offers , 3 = Brand Associates Offer');
            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');
            $table->boolean('status')->deafult('1');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_notification_brand', function (Blueprint $table) {
            $table->increments('id');

            // $table->integer('notification_id')->length(11)->unsigned()->nullable();
            // $table->foreign('notification_id')
            //     ->references('id')->on('customer_notification_settings');
            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');

            $table->integer('brand_id')->length(11)->unsigned()->nullable();
            $table->foreign('brand_id')
                ->references('id')->on('brands');


            // $table->boolean('status')->deafult('1');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_notification_salesrep', function (Blueprint $table) {
            $table->increments('id');

            // $table->integer('notification_id')->length(11)->unsigned()->nullable();
            // $table->foreign('notification_id')
            //     ->references('id')->on('customer_notification_settings');

            $table->integer('customer_id')->length(11)->unsigned()->nullable();
            $table->foreign('customer_id')
                ->references('id')->on('user_customer');

            $table->integer('salesrep_id')->length(11)->unsigned()->nullable();
            $table->foreign('salesrep_id')
                ->references('id')->on('user_salesreps');

            // $table->boolean('status')->deafult('1');

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
        Schema::drop('customer_notification_brand');
        Schema::drop('customer_notification_salesrep');
        Schema::drop('customer_notification_news');
    }
}
