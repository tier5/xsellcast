<?php namespace App\Storage\CustomerRequest;

use App\Storage\Csr\CsrRepositoryEloquent;
use App\Storage\Customer\CustomerRepositoryEloquent;
use App\Storage\Messenger\ThreadRepositoryEloquent;

class CustomerRequest {

    protected $thread;

    protected $customer;

    protected $csr;

    public function __construct() {
        $app            = app();
        $this->thread   = new ThreadRepositoryEloquent($app);
        $this->customer = new CustomerRepositoryEloquent($app);
        $this->csr      = new CsrRepositoryEloquent($app);
    }

    public function sendContactRequest($customer, $offer, $body, $phone_number = null, $brand = null) {
        if (!$phone_number || trim($phone_number) == '') {
            if ($customer->cellphone != '') {
                $phone_number = $customer->cellphone;
            } elseif ($customer->officephone != '') {
                $phone_number = $customer->officephone;
            } elseif ($customer->homephone != '') {
                $phone_number = $customer->homephone;
            }
        }

        $subject  = 'Contact Request';
        $approved = false;

        $thread = $this->sendRequest($customer, $offer, 'contact_me', $body, $subject, $approved, $brand);

        $thread->setMeta('phone_number', $phone_number);
        $thread->save();

        return $thread;
    }

    public function sendRequest($customer, $offer, $type, $body, $subject = null, $approved = false, $brand = false) {
        // dd($brand);
        $cust_user_id = $customer->user->id;
        $hasAssign    = null;
        if (!empty($offer)) {
            $salesRep = $this->customer->findNereastBAOfOffer($offer, $customer);

            $salesrep_user_id = ($salesRep ? $salesRep->user->id : null);
            $offer_id         = $offer->id;

            if (!$salesRep) {
                //Send notification to csr.
                $this->csr->sendUnmatchLeadNotify();

                /**
                 * Assign offer to customer. Since there is no BA match found
                 * this will show "ASSIGN NOW!" on "Brand Associate Assignments" section.
                 */
                $this->customer->setOfferToCustomer($offer->id, $customer, false, $type);
                // return null;
            }

            $thread = $this->thread->createMessage($cust_user_id, $salesrep_user_id, $body, $type, $subject, $offer_id);

            /**
             * assign customer to a salesrep if user is salesrep and customer.
             */
            if ($salesRep) {
                $hasAssign = $this->thread->assignCustToSalesRep($cust_user_id, $salesrep_user_id, $offer_id, $type, $approved);
            }

            /**
             * Don't send new lead notification if direct approve.
             */
            if ($hasAssign && !$approved && $salesRep) {
                //When assigned. This mean for a new lead for BA.
                $newLeadThread = $this->thread->createMessage($cust_user_id, $salesrep_user_id, 'New Lead', 'new_lead', 'New Lead', $offer_id);
                $newLeadThread->setMeta('parent_thread_type', $type);
                $newLeadThread->save();
            }

            $customer->updatedAtNow();
            return $thread;
        } else {
            $salesRep = $this->customer->findNereastBAOfBrand($brand, $customer);

            $salesrep_user_id = ($salesRep ? $salesRep->user->id : null);
            $brand_id         = $brand->id;
            if (!$salesRep) {
                // dd($salesRep);
                //Send notification to csr.
                $this->csr->sendUnmatchLeadNotify();

                /**
                 * Assign offer to customer. Since there is no BA match found
                 * this will show "ASSIGN NOW!" on "Brand Associate Assignments" section.
                 */
                $this->customer->setBrandToCustomer($brand->id, $customer, false, $type);

            }
            $offer_id = null;
            $thread   = $this->thread->createMessage($cust_user_id, $salesrep_user_id, $body, $type, $subject, $offer_id, $brand_id);

            /**
             * assign customer to a salesrep if user is salesrep and customer.
             */
            if ($salesRep) {
                $hasAssign = $this->thread->assignCustToSalesRep($cust_user_id, $salesrep_user_id, $offer_id, $type, $approved, $brand_id);
            }

            /**
             * Don't send new lead notification if direct approve.
             */
            if ($hasAssign && !$approved && $salesRep) {
                //When assigned. This mean for a new lead for BA.
                $newLeadThread = $this->thread->createMessage($cust_user_id, $salesrep_user_id, 'New Lead', 'new_lead', 'New Lead', $offer_id, $brand_id);
                $newLeadThread->setMeta('parent_thread_type', $type);
                $newLeadThread->save();
            }

            $customer->updatedAtNow();
            return $thread;
        }

    }

}