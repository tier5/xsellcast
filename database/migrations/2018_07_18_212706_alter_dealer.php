<?php

use Illuminate\Database\Migrations\Migration;

class AlterDealer extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::table('dealers', function ($t) {
            $t->integer('website')->nullable()->change();
            $t->integer('phone')->nullable()->change();
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
