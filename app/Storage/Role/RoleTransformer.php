<?php

namespace App\Storage\Role;

use League\Fractal\TransformerAbstract;
use App\Storage\Role\Role;

/**
 * Class RoleTransformer
 * @package namespace App\Storage\Role;
 */
class RoleTransformer extends TransformerAbstract
{

    /**
     * Transform the \Role entity
     * @param \Role $model
     *
     * @return array
     */
    public function transform(Role $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
