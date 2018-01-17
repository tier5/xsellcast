<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerWpidUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_customer', function($t) {
            $t->dropColumn('wp_userid');
        });

        Schema::table('user_customer', function($t) {
            $t->integer('wp_userid')->length(11)->unsigned()->nullable()->unique();
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
