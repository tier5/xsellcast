<?php

use Illuminate\Database\Migrations\Migration;

class AlterCategoryBrandOffer extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('categories', function ($t) {
            $t->integer('status')->nullable()->default(0)->comment('0=Active,1=inactive');
        });
        Schema::table('brands', function ($t) {
            $t->integer('status')->nullable()->default(0)->comment('0=Active,1=inactive');
        });
        Schema::table('dealers', function ($t) {
            $t->integer('status')->nullable()->default(0)->comment('0=Active,1=inactive');
        });
        Schema::table('offers', function ($t) {
            $t->integer('is_active')->nullable()->default(0)->comment('0=Active,1=inactive');
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
