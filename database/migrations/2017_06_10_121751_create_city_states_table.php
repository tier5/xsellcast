<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Storage\CityState\CityState;

class CreateCityStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      ini_set('memory_limit','1000M');
      $csv = \Excel::load('etc/zip_code_database.csv')->get();

      Schema::create('city_states', function (Blueprint $table) {
            $table->increments('id');

            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->decimal('geo_lat', 11, 8)->nullable();
            $table->decimal('geo_long', 11, 8)->nullable();

            $table->timestamps();
      });

      foreach($csv as $row)
      {
            $cityState           = new CityState();
            $cityState->city     = $row['city'];
            $cityState->state    = $row['state'];
            $cityState->zip      = $row['zip_code'];
            $cityState->geo_lat  = $row['latitude'];
            $cityState->geo_long = $row['longitude'];

            $cityState->save();
      }
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
