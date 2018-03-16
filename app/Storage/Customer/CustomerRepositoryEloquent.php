<?php

namespace App\Storage\Customer;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Customer\Customer;
use App\Storage\Customer\CustomerValidator;
use App\Storage\Dealer\Dealer;
use App\Storage\User\User;
use App\Storage\Role\Role;
use App\Storage\CustomerOffer\CustomerOffer;
use App\Storage\Ontraport\CustomerObj;

/**
 * Class CustomerRepositoryEloquent
 * @package namespace App\Storage\Customer;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Customer::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CustomerValidator::class;
    }

    public function presenter()
    {
        return CustomerPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getBySalesRep($salesrep_id, $only_approve = false)
    {
        $this->model = $this->model->whereHas('salesReps', function($query) use($salesrep_id){
                $query->where('salesrep_id', $salesrep_id);
            });

        if($only_approve)
        {
            $this->model = $this->model->whereHas('salesRepsPivot', function($query) use($salesrep_id){

                $query->withApproved();
                $query->where('salesrep_id', $salesrep_id);

            });
        }

        return $this->scopeToUser();
    }

    public function scopeToUser()
    {
        $this->model = $this->model->join('users', 'users.id' , '=', 'user_customer.user_id')->select('*', 'users.id as base_user_id', 'user_customer.id');

        return $this;
    }

    /**
     * Sets the offer to customer.
     *
     * @param      integer  $offer_id     The offer identifier
     * @param      App\Storage\Customer\Customer  $customer  The customer identifier
     * @param      string  $action        The action
     *
     * @return     App\Storage\Customer\Customer
     */
    public function setOfferToCustomer($offer_id, $customer, $is_added = true, $request_type = null)
    {
        if(is_integer($customer))
        {
            $customerId = $customer;
            $customer = $this->model->find($customerId);
        }

        /**
         * Return null if offer already exists.
         */
        $offer = $customer->offers()->find($offer_id);

        if($offer)
        {
          $customerOffer = $customer->pivotOffers()->where('offer_id',$offer_id)->first();

        }else{

          $customerOffer = $customer->setOffer($offer_id);
        }


      //  $co                       = $customerOffer->first();
        $customerOffer->added     = $is_added;
// dd($customerOffer);
        if($request_type && $request_type == 'appt')
        {
          $customerOffer->is_appt = true;
        }elseif($request_type && $request_type == 'price')
        {
          $customerOffer->is_price = true;
        }elseif($request_type && $request_type == 'info')
        {
          $customerOffer->is_info = true;
        }
        //$customerOffer->requested = $is_requested;

        $customerOffer->setUpdatedAt($this->model->freshTimestamp());
        $customerOffer->save();

        /**
         * Set action
         */
        $action = new \App\Storage\UserAction\UserAction();

        if($is_added){
          $action->addCustomerOffer($customer->user->id, $offer_id);
        }

        //End set action

        return $customerOffer;
    }

    public function completePresenter()
    {
        $this->setPresenter('App\Storage\Customer\CustomerCompletePresenter');

        return $this;
        //return $this->find($customer_id);
    }

    /**
     * @param      string  $key    The key
     *
     * @return     $this
     */
    public function whereName($key)
    {
        $model = $this->model->where(function($q) use($key){
            $q->where('users.firstname', 'like', '%' . $key . '%')->orWhere('users.lastname', 'like', '%' . $key . '%');
        });

        $this->model = $model;

        return $this;
    }

    public function noAssignedSalesrep()
    {
        $this->model = $this->model->whereHas('salesRepsPivot', function($query){
            $query->withApproved();
        }, '<=', 0);

        return $this;
    }

    public function orderBySalesRepsPivot($order)
    {
      $this->model = $this->model->leftJoin('customer_salesreps', 'user_customer.id', '=', 'customer_salesreps.customer_id')->orderBy('customer_salesreps.updated_at', $order)->select('user_customer.*');

      return $this;
    }

    public function orderByRejected($order = 'desc')
    {
        $this->model = $this->model->leftJoin('customer_salesreps', 'user_customer.id', '=', 'customer_salesreps.customer_id')->orderBy('customer_salesreps.rejected_at', $order)->select('user_customer.*');

        return $this;
    }

    /**
     *
     * @param      Offer  $offer     The offer
     * @param      Customer  $customer  The customer
     *
     * @return     SalesRep
     */
    public function findNereastBAOfOffer($offer, $customer)
    {
        $brands = $offer->brands;
        $brand = ($brands ? $brands->first() : null);

        if(!$brand)
        {
          return null;
        }

        $dealers = $brand->dealers;
        $nearestDis = 99999999999;
        $nearestDealer = $dealers->first();

        foreach($dealers as $dealer)
        {
            $distance = $this->distance($customer->geo_lat, $customer->geo_long, $dealer->geo_lat, $dealer->geo_long);

            if($nearestDis > $distance)
            {
                $nearestDis = $distance;
                $nearestDealer = $dealer;
            }
        }

        $salesrep = $nearestDealer->salesReps->shuffle()->first();

        return $salesrep;
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
      $lat1 = (float)$lat1;
      $lon1 = (float)$lon1;
      $lat2 = (float)$lat2;
      $lon2 = (float)$lon2;

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }

    public function createOne($data, $ontraport = true)
    {
      $roleObj      = new Role();
      $customerRole = $roleObj->where('name', 'customer')->first();

      $user               = User::create([
        'firstname'       => $data['firstname'],
        'lastname'        => $data['lastname'],
        'password'        => bcrypt($data['password']),
        'email'           => $data['email'],
        'provider'        => $data['provider'],
        'provider_token'  => $data['provider_token'],

      ]);

      $user->roles()->save($customerRole);

      $customer = $this->model->create([
       'address1'         => $data['address1'],
       'address2'         => $data['address2'],
       'homephone'         => $data['homephone'],
       'cellphone'         => $data['cellphone'],
       'officephone'         => $data['officephone'],

        'zip'             => $data['zip'],
        'city'            => $data['city'],
        'state'           => $data['state'],
        'country'         => $data['country'],
        'geo_long'        => $data['geo_long'],
        'geo_lat'         => $data['geo_lat'],

        'user_id'         => $user->id,
        'wp_userid'       => (isset($data['wp_userid']) ? $data['wp_userid'] : null)
      ]);

      $user->customer()->save($customer);

      //$customer->user()->save($user);

      if($ontraport)
      {
        $opcust = new CustomerObj();
        $opcust->upsert($user->customer);
      }

      return $customer;
    }

    public function updateOne(Customer $cust, $data = [])
    {
        $user       = $cust->user;
        $userFields = \Schema::getColumnListing($cust->user->getTable());
        $srFields   = \Schema::getColumnListing($cust->getTable());
      // dd( $srFields);
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
                $cust->{$field} = $data[$field];
            }
        }

        $cust->save();
        if($cust->opid){
        $custop = new CustomerObj();
        $custop->upsert($cust);
        }


        return $cust;
    }

    public function countResult()
    {
      return $this->model->count();
    }

    public function orderByName($order)
    {
      $this->model = $this->model->joinUser()->orderBy('users.firstname', $order);

      return $this;
    }

    public function media(){
    exit;
    return $this->model->media;
    }

    /**
     *
     * @param      Offer  $offer     The offer
     * @param      Customer  $customer  The customer
     *
     * @return     offer
     */
    public function findNereastOffer($customer)
    {


        $dealers = $dealers;
        $nearestDis = 99999999999;
        $nearestDealer = $dealers->first();

        foreach($dealers as $dealer)
        {
            $distance = $this->distance($customer->geo_lat, $customer->geo_long, $dealer->geo_lat, $dealer->geo_long);

            if($nearestDis > $distance)
            {
                $nearestDis = $distance;
                $nearestDealer = $dealer;
            }
        }

        $salesrep = $nearestDealer->salesReps->shuffle()->first();

        return $salesrep;
    }

}
