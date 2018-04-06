<?php

namespace App\Storage\Appointment;

use League\Fractal\TransformerAbstract;
use App\Storage\Appointment\Appointment;

/**
 * Class AppointmentTransformer
 * @package namespace App\Storage\Appointment;
 */
class AppointmentTransformer extends TransformerAbstract
{

    /**
     * Transform the \Appointment entity
     * @param \Appointment $model
     *
     * @return array
     */
    public function transform(Appointment $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */
            'name'       => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'category'  => $model->category
        ];
    }
}
