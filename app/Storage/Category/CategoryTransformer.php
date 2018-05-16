<?php

namespace App\Storage\Category;

use App\Storage\Category\Category;
use League\Fractal\TransformerAbstract;

/**
 * Class CategoryTransformer
 * @package namespace App\Storage\Category;
 */
class CategoryTransformer extends TransformerAbstract {

    /**
     * Transform the \Category entity
     * @param \Category $model
     *
     * @return array
     */
    public function transform(Category $model) {
        return [
            'id'             => (int) $model->id,
            'wp_category_id' => $model->wp_category_id,
            /* place your other model properties here */

            'created_at'     => $model->created_at,
            'updated_at'     => $model->updated_at,
        ];
    }
}
