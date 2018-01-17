<?php

namespace App\Storage\SalesRep;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\SalesRep\SalesRep;
use App\Storage\SalesRep\SalesRepValidator;
use App\Storage\SalesRep\SalesRepPresenter;
use Auth;
use App\Storage\User\User;
use App\Storage\Role\Role;
use App\Storage\Dealer\Dealer;
use App\Storage\Ontraport\SalesRepObj;

/**
 * Class SalesRepRepositoryEloquent
 * @package namespace App\Storage\SalesRep;
 */
class SalesRepRepositoryEloquent extends BaseRepository implements SalesRepRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SalesRep::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return SalesRepValidator::class;
    }

    public function presenter()
    {
        
        return SalesRepPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Gets the by dealer.
     *
     * @param      integer  $dealer_id  The dealer identifier
     *
     * @return     $this  The by dealer.
     */
    public function getByDealer($dealer_id)
    {

        $model = $this->model
            ->whereHas('dealers', function($query) use($dealer_id){
                $query->where('dealer_id', $dealer_id);
            });

        $this->model = $model;

        return $this;
    }

    /**
     * Get many by customer id.
     *
     * @param      integer  $customer_id  The dealer identifier
     *
     * @return     $this  The by dealer.
     */
    public function getByCustomer($customer_id, $show_approved = true, $show_rejected = true)
    {

        $model = $this->model
            ->whereHas('customersPivot', function($query) use($customer_id, $show_approved, $show_rejected){
                $query->where('customer_id', $customer_id);

                if(!$show_approved || !$show_rejected)
                {
                    $query->where(function($q) use($show_approved, $show_rejected){
                        $q->where('approved', $show_approved);
                        $q->where('rejected', $show_rejected);
                    });                    
                }
            });

        $this->model = $model;

        return $this;
    }    

    public function filter($where)
    {

        $this->model = $this->model->where($where);

        return $this;
    }      
    
    /**
     *
     * @param      integer|null  $salesrep_id  The salesrep identifier
     *
     * @return     SalesRep|null
     */
    public function currentUser($salesrep_id = null)
    {
        if(!Auth::check()){

            return null;
        }

        $user = Auth::user();

        if($salesrep_id){

            $salesRep = $this->skipPresenter()->find($salesrep_id);

            if($user->id != $salesRep->user_id)
            { 
                return null;
            }

        }else{

            $salesRep = $this->skipPresenter()
                ->filter(['user_id' => $user->id])
                ->first();              
        }
 
            
        return $salesRep;     
    }   

    /**
     * -1 = completed
     *  0 = Create Account
     *  1 = Company info
     *  2 = Social profile
     * 
     *
     * @param      SalesRep   $sales_rep  The sales rep
     *
     * @return     Array
     */
    public function registrationLevel($sales_rep)
    {   
        if(!$sales_rep){

            return array();
        }

        $user = $sales_rep->user()->first();
        
        if($user->firstname == '')
        {
            return array(0);
        }

        $dealers = $sales_rep->dealers()->get();

        if($dealers->count() == 0)
        {

            return array(0, 1);
        }

        if(empty($sales_rep->facebook) && empty($sales_rep->twitter) && empty($sales_rep->linkedin)){

            return array(0, 1, 2);
        }       

        return array(-1, 0, 1, 2);
    }

    public function maxLevelRegistration($sales_rep)
    {
        $levels = $this->registrationLevel($sales_rep);

        return end($levels);
    }

    public function createOne($data, $dealer = null, $ontraport = true)
    {
        $role = Role::where('name', 'sales-rep')->first();
        $dealerModel       = ($dealer ? Dealer::find($dealer) : null);
        $user         = User::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'password'  => bcrypt($data['password']),
            'email'     => $data['email'] ]);      

        $salesrep = $this->model->create([
            'user_id' => $user->id]);

        if($dealerModel)
        {
            $salesrep->dealers()->save($dealerModel);
        }

        $user->roles()->save($role);
        $user->salesRep()->save($salesrep);

        if($ontraport){
            $sro = new SalesRepObj();
            $sro->upsert($salesrep);
        }
        
        return $salesrep;
    }

    public function updateOne(SalesRep $sr, $data)
    {
        $user       = $sr->user;
        $userFields = \Schema::getColumnListing($sr->user->getTable());
        $srFields   = \Schema::getColumnListing($sr->getTable());

        foreach($userFields as $field)
        {
            if($field == 'id' || $field == 'password')
            {
                continue;
            }

            if(isset($data[$field]) && $field == 'email' && trim($data[$field]) == ''){

                continue;
            }

            if(isset($data[$field]))
            {
                $user->{$field} = $data[$field];
            }
        }

        $user->save();

        foreach($srFields as $field)
        {
            if($field == 'id' || $field == 'password')
            {
                continue;
            }
            
            if(isset($data[$field]))
            {
                $sr->{$field} = $data[$field];
            }            
        }

        $sr->save();

        $sro = new SalesRepObj();
        $sro->upsert($sr);

        return $sr;
    }

    public function orderByName($order = 'desc')
    {   
        $this->joinToUsers();
        $this->model = $this->model->orderBy('users.lastname', $order);

        return $this;
    }

    public function orderByEmail($order = 'desc')
    {   
        $this->joinToUsers();
        $this->model = $this->model->orderBy('users.email', $order);

        return $this;
    }  

    public function joinToUsers()
    {
        $this->model = $this->model->join('users', 'user_salesreps.user_id', '=', 'users.id')
        ->select('user_salesreps.*');

        return $this;
    }  

    public function orderByAgreement($order = 'desc')
    {
        $this->joinToUsers();
        $this->joinToUserMetaAgreement();
        $this->model = $this->model->orderBy('users_meta.updated_at', $order);

        return $this;
    }

    public function joinToUserMetaAgreement()
    {
        $this->model = $this->model->leftJoin('users_meta', 'users_meta.user_id', '=', 'users.id')->where('key', 'salesrep_agreement');

        return $this;
    }
}
