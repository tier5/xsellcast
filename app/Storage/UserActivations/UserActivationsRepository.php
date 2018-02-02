<?php

namespace App\Storage\UserActivations;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserActivationsRepository
 * @package namespace App\Storage\UserActivations;
 */
interface UserActivationsRepository extends RepositoryInterface
{

	public function createActivation($user);

	public function getActivation($user);

	public function getActivationByToken($token);

	public function deleteActivation($token);
}
