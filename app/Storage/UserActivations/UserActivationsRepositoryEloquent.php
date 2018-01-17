<?php

namespace App\Storage\UserActivations;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\UserActivations\UserActivationsRepository;
use App\Storage\UserActivations\UserActivations;
use App\Storage\UserActivations\UserActivationsValidator;
use Carbon\Carbon;

/**
 * Class UserActivationsRepositoryEloquent
 * @package namespace App\Storage\UserActivations;
 */
class UserActivationsRepositoryEloquent extends BaseRepository implements UserActivationsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserActivations::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserActivationsValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createActivation($user)
    {

        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);

    }

    private function regenerateToken($user)
    {

        $token = $this->getToken();
        $this->model->where('user_id', $user->id)->update([
            'token' => $token
        ]);
        return $token;
    }

    private function createToken($user)
    {
        $token = $this->getToken();

        $this->create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        return $token;
    }

    public function getActivation($user)
    {
        return $this->model->where('user_id', $user->id)->first();
    }

    public function getActivationByToken($token)
    {
        return $this->model->where('token', $token)->first();
    }

    public function deleteActivation($token)
    {
        $row = $this->model->where('token', $token)->first();
        
        if(!$row)
        {
            return null;
        }

        return $this->delete($row->id);
    }     
}
