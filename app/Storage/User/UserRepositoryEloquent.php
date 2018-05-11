<?php

namespace App\Storage\User;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\SalesRep\SalesRep;
use App\Storage\User\User;
use App\Storage\User\UserRepository;
use App\Storage\User\UserValidator;
use App\Storage\User\UserPresenter;
use App\Storage\Role\Role;
use Snowfire\Beautymail\Beautymail;
use App\Storage\UserActivations\UserActivations;
use App\Storage\SalesRep\SalesRepRepositoryEloquent;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Storage\User;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserValidator::class;
    }

    public function presenter()
    {

        return UserPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Creates a sales rep.
     *
     * @param      Array  $data   The data
     *
     * @return     User
     */
    public function createSalesRep($data)
    {
        $salesrepRepo = new SalesRepRepositoryEloquent(app());
        $dealerId     = (isset($data['dealer']) ? $data['dealer'] : null);
        $salesrep     = $salesrepRepo->createOne($data, $dealerId, false);
        $user         = $salesrep->user;

        //////////
    //    $userData = array(
    //        'firstname' => (isset($data['firstname']) ? $data['firstname'] : ''),
    //        'lastname' => (isset($data['lastname']) ? $data['lastname'] : ''),
    //    );

    //    if(isset($data['email'])){
    //        $userData['email'] = $data['email'];
    //    }

    //    if(isset($data['password'])){
    //        $userData['password'] = bcrypt($data['password']);
    //    }

        /**
         * Create user data.
         */
    //    $user = $this->skipPresenter()->create($userData);

        if(isset($data['meta'])){
            $user->setMeta($data['meta']);
            $user->save();
        }

        $salesRepData = array(
         //   'user_id'          => $user->id,
            'cellphone'        => (isset($data['cellphone']) ? $data['cellphone'] : ''),
            'officephone'      => (isset($data['officephone']) ? $data['officephone'] : ''),
            'show_cellphone'   => (isset($data['show_cellphone']) ? $data['show_cellphone'] : 0),
            'show_email'       => (isset($data['show_email']) ? $data['show_email'] : 0),
            'show_officephone' => (isset($data['show_officephone']) ? $data['show_officephone'] : 0),
            'facebook'         => (isset($data['facebook']) ? $data['facebook'] : ''),
            'twitter'          => (isset($data['twitter']) ? $data['twitter'] : ''),
            'linkedin'         => (isset($data['linkedin']) ? $data['linkedin'] : ''),
            'job_title'        => (isset($data['jobtitle']) ? $data['jobtitle'] : ''),
        );

        $salesrepRepo->updateOne($salesrep, $salesRepData);

        /**
         * Create activation.
         */
        $this->createActivation($user);

        /**
         * Create sales rep data.
         */
     //   $salesRep = $user->salesRep()->create($salesRepData);

        /**
         * Set role
         */
      //  $salesRepRole = Role::where('name', 'sales-rep')->first();
      //  $user->attachRole($salesRepRole);

        return $user;
    }

    public function createActivation($user)
    {

        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);

    }

    public function getActivation($user)
    {
        $model = new UserActivations();
        return $model->where('user_id', $user->id)->first();
    }

    private function createToken($user)
    {
        $token = $this->getToken();
        $activation = UserActivations::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        return $token;
    }

    /**
     * Gets user by activation.
     *
     * @param      String  $token  The token
     *
     * @return     User\User  The by activation.
     */
    public function getByActivation($token)
    {
        return $this->model->getByActivation($token)->first();
    }

    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * Send mail for after account created.
     */
    public function mailSalesRepAcctCreated($sales_rep)
    {
        $user       = $sales_rep->user()->first();
        $activation = $user->accountActivation()->first();
        $token      = ($activation ? $activation->token : $this->createActivation($user));
        // $beautymail = app()->make(Beautymail::class);

        // $beautymail->send('emails.salesrep.welcome', compact('sales_rep', 'user', 'token'), function($message) use($sales_rep, $user)
        // {

        //     $message
        //         ->from('admin@xsellcast.com')
        //         ->to($user->email, $user->firstname . ' ' . $user->lastname)
        //         ->subject('Welcome Brand Associate!');
        // });
    }

    /**
     * Send email confirmation for invited BA.
     */
    public function mailSalesRepInvited($sales_rep, $password = null)
    {
        $user = $sales_rep->user()->first();
        $token = $user->accountActivation()->first()->token;
        // $beautymail = app()->make(Beautymail::class);
        // $beautymail->send('emails.salesrep.invitation', compact('sales_rep', 'user', 'token', 'password'), function($message) use($user)
        // {

        //     $message
        //         ->from('admin@xsellcast.com')
        //         ->to($user->email, $user->firstname . ' ' . $user->lastname)
        //         ->subject('Your Xsellcast Invitation');
        // });
    }

    /**
     * @param      \App\Storage\SalesRep\SalesRep  $salesrep  The salesrep
     *
     * @return     $this
     */
    public function customerHasSalesRep(SalesRep $salesrep)
    {
        $model = $this->model->whereHas('customer', function($query) use($salesrep){
            $query->whereHas('salesReps', function($query) use($salesrep){
                $query->where('user_salesreps.id', $salesrep->id);
            });
        });

        $this->model = $model;

        return $this;
    }

    /**
     * @param      string  $key    The key
     *
     * @return     $this
     */
    public function whereName($key)
    {
        $model = $this->model->where(function($q) use($key){
            $q->where('firstname', 'like', '%' . $key . '%')->orWhere('lastname', 'like', '%' . $key . '%');
        });

        $this->model = $model;

        return $this;
    }

    public function csrContactList($except_user_id)
    {
        $this->model = $this->model->forCsrContact()->where('id', '!=', $except_user_id);

        return $this;
    }

    public function salesRepContactList($user)
    {
        $excerptId   = $user->id;
        $salesrep    = $user->salesRep;
        $this->model = $this->model->where(function($query) use($salesrep, $excerptId){
            $query->whereHas('roles', function($query){
                $query->whereIn('name', ['csr']);
            })->orWhere(function($q) use($salesrep){
                $q->whereHas('roles', function($query){
                    $query->where('name', 'customer');
                })
                ->whereHas('customer', function($q) use($salesrep){
                    $q->whereHas('salesRepsPivot', function($q) use($salesrep){

                        $q->where('customer_salesreps.salesrep_id', $salesrep->id);
                        $q->where('approved', 1);
                        $q->where('rejected', 0);
                    });
                });
            })->where('id', '!=', $excerptId);
        });

        return $this;
    }

    public function search($search)
    {
        $this->model = $this->model->where(function($query) use($search){
            $query->where('firstname', 'like', '%' . $search . '%')->orWhere('lastname', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
        });

        return $this;
    }
}
