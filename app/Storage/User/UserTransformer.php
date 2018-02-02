<?php

namespace App\Storage\User;

use League\Fractal\TransformerAbstract;
use App\Storage\User\User;

/**
 * Class UserTransformer
 * @package namespace App\Storage\User;
 */
class UserTransformer extends TransformerAbstract
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
