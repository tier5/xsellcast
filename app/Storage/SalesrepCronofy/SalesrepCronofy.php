<?php

namespace App\Storage\SalesrepCronofy;

use Illuminate\Database\Eloquent\Model;

class SalesrepCronofy extends Model
{
    protected $fillable = ['salesrep_id',  'client_id', 'client_secret', 'token', 'calendar_name', 'calendar_id'];
    protected $table = 'salesrep_cronofy';


    public function salesrep()
    {
        return $this->belongsTo('App\Storage\SalesRep\SalesRep', 'salesrep_id', 'id');
    }
}