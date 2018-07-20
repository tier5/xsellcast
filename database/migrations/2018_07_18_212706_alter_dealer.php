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
            $t->string('website')->nullable()->change();
            $t->string('phone')->nullable()->change();
            $t->string('state')->nullable()->change();
            $t->string('country')->nullable()->change();
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
