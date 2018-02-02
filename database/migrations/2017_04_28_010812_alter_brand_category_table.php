<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBrandCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brand_categories', function($t) {
            $t->integer('media_logo_id')->unsigned()->nullable();
            $t->foreign('media_logo_id')->references('id')->on('media')->onDelete('cascade');

            $t->longText('description');
            $t->string('catalog_url');

            $t->string('media_ids');

            $t->dropColumn(['wpid', 'op_tag_id']);
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
