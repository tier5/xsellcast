<?php namespace App\Storage\User;

use League\Fractal\TransformerAbstract;
use App\Storage\User\User;

/**
 * Class UserAutoCompleteTransformer
 * @package namespace App\Storage\User;
 */
class UserAutoCompleteTransformer extends TransformerAbstract
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        $user = $model;
        $name = (empty($user->firstname) && empty($user->lastname) ? $user->name
            : $user->firstname . ' ' . $user->lastname);

        return [
            'label'         => $name . '(' . $user->email . ')',
            'value'         => $user->email,
            'role'          => $user->roles
        ];
    }
}
