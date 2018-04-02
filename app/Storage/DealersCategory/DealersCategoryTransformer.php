<?php

namespace App\Storage\DealersCategory;

use League\Fractal\TransformerAbstract;
use App\Storage\DealersCategory\DealersCategory;

/**
 * Class DealersCategoryTransformer
 * @package namespace App\Storage\DealersCategory;
 */
class DealersCategoryTransformer extends TransformerAbstract
{

    /**
     * Transform the \DealersCategory entity
     * @param \DealersCategory $model
     *
     * @return array
     */
    public function transform(DealersCategory $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'slug'       => $model->slug,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}