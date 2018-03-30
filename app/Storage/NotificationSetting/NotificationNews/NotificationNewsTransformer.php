<?php

namespace App\Storage\NotificationSetting\NotificationNews;

use League\Fractal\TransformerAbstract;
use App\Storage\NotificationSetting\NotificationNews\NotificationNews;

/**
 * Class BrandTransformer
 * @package namespace App\Storage\NotificationSetting\NotificationNews;
 */
class NotificationNewsTransformer extends TransformerAbstract
{

    /**
     * Transform the \Brand entity
     * @param \Brand $model
     *
     * @return array
     */
    public function transform(NotificationNews $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */
            // 'notification_type'       => $model->notification_type,
            'wp_customer_id'  => $model->customer->wp_userid,
            'status' => $model->status,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,

        ];
    }
}
