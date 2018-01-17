<?php

namespace App\Storage\UserActivations;

use League\Fractal\TransformerAbstract;
use App\Storage\UserActivations\UserActivations;

/**
 * Class UserActivationsTransformer
 * @package namespace App\Storage\UserActivations;
 */
class UserActivationsTransformer extends TransformerAbstract
{

    /**
     * Transform the \UserActivations entity
     * @param \UserActivations $model
     *
     * @return array
     */
    public function transform(UserActivations $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
