<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealers', function($t) {
            $t->string('county')->nullable();
            $t->string('country_code')->nullable();
            $t->string('outlet')->nullable();
            $t->string('distributor_name')->nullable();
            $t->string('rep_name')->nullable();
            $t->string('rep_email')->nullable();
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
        //
    }
}
