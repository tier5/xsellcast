<?php

namespace App\Storage\SalesRep;

use League\Fractal\TransformerAbstract;
use App\Storage\SalesRep\SalesRep;

/**
 * Class SalesRepTransformer
 * @package namespace App\Storage\SalesRep;
 */
class SalesRepTransformer extends TransformerAbstract
{

    /**
     * Transform the \SalesRep entity
     * @param \SalesRep $model
     *
     * @return array
     */
    public function transform(SalesRep $model)
    {
        $user = $model->user;

        return [
            'id'                 => (int) $model->id,
            'firstname'          => $user->firstname,
            'lastname'           => $user->lastname,
            'email'              => $user->email,
            'user_id'            => $user->id,
            'show_cellphone'     => $model->show_cellphone,
            'show_officephone'   => $model->show_officephone,
            'show_email'         => $model->show_email,
            'cellphone'          => $model->cellphone,
            'officephone'        => $model->officephone,
            'facebook'           => $model->facebook,
            'twitter'            => $model->twitter,
            'linkedin'           => $model->linkedin,
            'created_at'         => $model->created_at,
            'updated_at'         => $model->updated_at,
            'agreement_accepted' => $model->is_agreement,
            'agreed_at'          => $model->local_agreed_at
        ];
    }
}
