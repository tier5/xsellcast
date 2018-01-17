<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OfferMakeWpidUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function($t) {
            $t->dropColumn('wpid');
        });

        Schema::table('offers', function($t) {
            $t->integer('wpid')->length(11)->unsigned()->nullable()->unique();
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
