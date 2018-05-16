<?php

namespace App\Storage\Brand;

use App\Storage\Brand\Brand;
use League\Fractal\TransformerAbstract;

/**
 * Class BrandTransformer
 * @package namespace App\Storage\Brand;
 */
class BrandTransformer extends TransformerAbstract {

    /**
     * Transform the \Brand entity
     * @param \Brand $model
     *
     * @return array
     */
    public function transform(Brand $model) {
        return [
            'id'          => (int) $model->id,

            /* place your other model properties here */
            'name'        => $model->name,
            'slug'        => $model->slug,
            'wp_brand_id' => $model->wp_brand_id,
            'catalog_url' => $model->catalog_url,
            'image_url'   => $model->image_url,
            'image_link'  => $model->image_link,
            'image_text'  => $model->image_text,

            'parent_id'   => $model->parent_id,
            'created_at'  => $model->created_at,
            'updated_at'  => $model->updated_at,
            'category'    => $model->category,
        ];
    }
}
