<?php namespace App\Storage\Customer;

use League\Fractal\TransformerAbstract;
use App\Storage\Customer\Customer;

/**
 * Class CustomerAutoCompleteTransformer
 * @package namespace App\Storage\Customer;
 */
class CustomerAutoCompleteTransformer extends TransformerAbstract
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
            'label'         => $name . '(' . $user->email . ')',
            'value'         => $user->email
        ];
    }
}
