<?php

namespace App\Storage\NotificationSetting;

use League\Fractal\TransformerAbstract;
use App\Storage\NotificationSetting\NotificationSetting;

/**
 * Class BrandTransformer
 * @package namespace App\Storage\NotificationSetting;
 */
class NotificationSettingTransformer extends TransformerAbstract
{

    /**
     * Transform the \Brand entity
     * @param \Brand $model
     *
     * @return array
     */
    public function transform(Brand $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */
            'name'       => $model->name,
            'wpid'       => $model->wpid,
            'parent_id'  => $model->parent_id,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'category'  => $model->category
        ];
    }
}
