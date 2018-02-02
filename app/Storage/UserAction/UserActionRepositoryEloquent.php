<?php

namespace App\Storage\UserAction;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\UserAction\UserActionRepository;
use App\Storage\UserAction\UserAction;
use App\Storage\UserAction\UserActionValidator;
use App\Storage\UserAction\UserActionPresenter;
use App\Storage\Offer\Offer;
use App\Storage\Customer\CustomerRepositoryEloquent;
use App\Storage\Ontraport\CustomerObj;
use App\Storage\Messenger\Thread;

/**
 * Class UserActionRepositoryEloquent
 * @package namespace App\Storage\UserAction;
 */
class UserActionRepositoryEloquent extends BaseRepository implements UserActionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserAction::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserActionValidator::class;
    }

    public function presenter()
    {
        
        return UserActionPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getSalesRepCustomerActivities($salesrep, $include_added_offer = true, $include_request = true)
    {
        if($include_added_offer || $include_request){
            $dealer = $salesrep->dealers->first();
            $brand = $dealer->brands->first();
            $offers = Offer::whereHas('salesrep', function($q) use($salesrep){
                $q->where('salesrep_id', $salesrep->id);
            })->orWhere(function($q) use($dealer){

                $q->inDealers([$dealer->id]);
            });
            $offerIds    = $offers->get()->lists('id');
        }

        $this->model = $this->model->forSalesRepCustomer($salesrep);

        if($include_added_offer &&  $include_request)
        {
            $this->model = $this->model->forCustomerActivity();
        }elseif($include_added_offer)
        {
            $this->model = $this->model->forCustomerAddedOffer(); //->inOfferIds($offerIds);
        }elseif($include_request)
        {
            $this->model = $this->model->forCustomerRequest()->inOfferIds($offerIds);
        }

        return $this;
    }

    public function getActivities($include_added_offer = true, $include_request = true)
    {

        if($include_added_offer &&  $include_request)
        {
            $this->model = $this->model->forCustomerActivity();
        }elseif($include_added_offer)
        {
            $this->model = $this->model->forCustomerAddedOffer(); //->inOfferIds($offerIds);
        }elseif($include_request)
        {
            $this->model = $this->model->forCustomerRequest();
        }

        return $this;
    }    

    public function userActions($user_id)
    {
        $this->model = $this->model->forUser($user_id);

        return $this;
    }

    public function forCustomersSyncToOntraport()
    {
        $this->model = $this->model->forCustomerUser()->forSendingToOntraport()->where('type', '!=', 'added_offer');

        return $this;
    }

    public function customersSyncToOntraport($limit = null)
    {
        $this->skipPresenter();
        $this->forCustomersSyncToOntraport();

        $app          = app();
        $customerRepo = new CustomerRepositoryEloquent($app);
        $actions      = (!$limit ? $this->all() : $this->paginate($limit) );

        foreach($actions as $action)
        {
            $custUser = $action->user;
            $customer = $custUser->customer;
            $thread   = Thread::find($action->getMeta('thread_id'));

            if(!$customer->opid)
            {
                $customer = $customerRepo->updateOne($customer);
            }

            $offerId      = $action->getMeta('offer_id');
            $offer        = Offer::find($offerId);
            $custObj      = new CustomerObj();
            $salesRepUser = null;

            if($thread)
            {
                $salesRepUser = $thread->users()->where('users.id', '!=', $custUser->id)->first();
            }

            if(!$offer && $action->type!= 'direct_message')
            {
                continue;
            }

            $custObj->actionRequest($action, $customer, $offer, $salesRepUser->salesRep);
        }
    }

}
