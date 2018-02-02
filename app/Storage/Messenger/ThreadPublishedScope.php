<?php namespace App\Storage\Messenger;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ThreadPublishedScope
 * 
 * Get published thread only.
 *
 * @package namespace App\Storage\Messenger;
 */
class ThreadPublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', config('lbt.message_stat')['publish']['key']);
    }
}
