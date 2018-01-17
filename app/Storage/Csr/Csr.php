<?php

namespace App\Storage\Csr;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Builder;

class Csr extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_csr', function (Builder $builder) {
            $builder->whereHas('user', function($q){
            	$q->whereHas('roles', function($q){
            		$q->where('name', 'csr');
            	});
            });
        });
    }

    public function user()
    {
    	return $this->belongsTo('App\Storage\User\User', 'id', 'id');
    }
}
