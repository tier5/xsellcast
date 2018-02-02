<?php

namespace App\Storage\CityState;

use Illuminate\Database\Eloquent\Model;

class CityState extends Model
{
    protected $fillable = [ 'city', 'state', 'zip', 'geo_lat', 'geo_long' ];

    protected $table = 'city_states';

    public function scopeZipLook($query, $zip)
    {

        return $query->where(function($query) use($zip){

            $len =  5 - strlen($zip);
            $leadingZero = '';

            for($i = 1; $i <= $len; $i++)
            {
                $leadingZero .= '0';
            }
            
            $query->where('zip', $zip);
            $query->orWhere('zip', $leadingZero . $zip);
        });
    }
}
