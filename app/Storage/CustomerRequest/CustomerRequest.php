<?php namespace App\Storage\CustomerRequest;

use App\Storage\Messenger\ThreadRepositoryEloquent;
use App\Storage\Customer\CustomerRepositoryEloquent;
use App\Storage\Csr\CsrRepositoryEloquent;

class CustomerRequest
{

    protected $thread;

    protected $customer;

    protected $csr;

	public function __construct()
	{
		$app = app();
		$this->thread = new ThreadRepositoryEloquent($app);
                $this->customer = new CustomerRepositoryEloquent($app);
                $this->csr = new CsrRepositoryEloquent($app);
	}

    public function sendContactRequest($customer, $offer, $body, $phone_number = null)
    {
        if(!$phone_number || trim($phone_number) == '')
        {
            if($customer->cellphone != '')
            {
                $phone_number = $customer->cellphone;
            }elseif($customer->officephone != '')
            {
                $phone_number = $customer->officephone;
            }elseif($customer->homephone != '')
            {
                $phone_number = $customer->homephone;
            }
        }

        $thread = $this->sendRequest($customer, $offer, 'contact_me', $body, 'Contact Request');

        $thread->setMeta('phone_number', $phone_number);
        $thread->save();

        return $thread;
    }

    public function sendRequest($customer, $offer, $type, $body, $subject = null, $approved = false)
    {
        $cust_user_id     = $customer->user->id;
        $salesRep         = $this->customer->findNereastBAOfOffer($offer, $customer);
        $salesrep_user_id = ($salesRep ? $salesRep->user->id : null);
        $offer_id         = $offer->id;
        $hasAssign        = null;
        
        if(!$salesRep)
        {
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
        if($salesRep)
        {
            $hasAssign = $this->thread->assignCustToSalesRep($cust_user_id, $salesrep_user_id, $offer_id, $type, $approved);            
        }

        /**
         * Don't send new lead notification if direct approve.
         */
        if($hasAssign && !$approved && $salesRep)
        {
            //When assigned. This mean for a new lead for BA.
            $newLeadThread = $this->thread->createMessage($cust_user_id, $salesrep_user_id, 'New Lead', 'new_lead', 'New Lead', $offer_id);
            $newLeadThread->setMeta('parent_thread_type', $type);
            $newLeadThread->save();
        }

        $customer->updatedAtNow();
        
        return $thread;
    }        

}