<?php

namespace App\Storage\Customer;

use League\Fractal\TransformerAbstract;
use App\Storage\Customer\Customer;

/**
 * Class CustomerTransformer
 * @package namespace App\Storage\Customer;
 */
class CustomerTransformer extends TransformerAbstract
{

    /**
     * Transform the \Customer entity
     * @param \Customer $model
     *
     * @return array
     */
    public function transform(Customer $model)
    {
        $user = $model->user;

        $name = (empty($user->firstname) && empty($user->lastname) ? $model->user->name
            : $user->firstname . ' ' . $user->lastname);

        return [
            'id'           => (int) $model->id,
            'name'         => $name,
            'created_at'   => $model->created_at,
            'updated_at'   => $model->updated_at,
            'firstname'    => $user->firstname,
            'lastname'     => $user->lastname,
            'email'        => $user->email,
            'user_id'      => $user->id,
            'ontraport_id' => $model->ontraport_id,
            'address1'     => $model->address1,
            'address2'     => $model->address2,
            'zip'          => $model->zip,
            'city'         => $model->city,
            'state'        => $model->state,
            'geo_long'     => $model->geo_long,
            'geo_lat'      => $model->geo_lat,
            'homephone'    => $model->homephone,
            'officephone'  => $model->officephone,
            'cellphone'    => $model->cellphone
        ];
    }
}
