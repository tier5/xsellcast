<?php

namespace App\Storage\Customer;

use League\Fractal\TransformerAbstract;
use App\Storage\Customer\Customer;
use App\Storage\Customer\CustomerTransformer;

/**
 * Class CustomerTransformer
 * @package namespace App\Storage\Customer;
 */
class CustomerCompleteTransformer extends CustomerTransformer
{

    /**
     * Transform the \Customer entity
     * @param \Customer $model
     *
     * @return array
     */
    public function transform(Customer $customer)
    {

        $baseInfo       = parent::transform($customer);
        $custOffeModel  = $customer->pivotOffers();
        $latestOfferAct = $customer->activities()->forCustomerActivity()->orderBy('created_at', 'desc')->first();
        $lookbook_count = $custOffeModel->count();    
        $avatar         = $customer->user->avatar();

        /**
         * Set info
         */
        $baseInfo['date_joined']            = $customer->created_at;
        $baseInfo['last_activity_human']    = ($latestOfferAct ? $latestOfferAct->created_at : '' );
        $baseInfo['lookbook_count']         = $custOffeModel->count();
        $baseInfo['avatar_orig_url']        = ($avatar ? $avatar->getOrigUrl() : null);
        $baseInfo['company']                = '';
        $baseInfo['fb_id']                  = $customer->user->fb_id;
        $baseInfo['activity_count']         = $customer->activities()->forCustomerActivity()->count();

        return $baseInfo;         
    }
}
