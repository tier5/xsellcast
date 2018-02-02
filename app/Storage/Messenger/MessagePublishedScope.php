<?php namespace App\Storage\Messenger;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MessagePublishedScope
 * 
 * Get published thread only.
 *
 * @package namespace App\Storage\Messenger;
 */
class MessagePublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('thread', function($q){
        	//$q->where('messenger_threads.status', 'publish');
        });
    }
}