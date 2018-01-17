<?php 
namespace App\Storage\Ontraport;

use GuzzleHttp\Client;
use App\Storage\Customer\Customer;
use App\Storage\Ontraport\Object;
use App\Storage\Offer\Offer;
use App\Storage\SalesRep\SalesRep;

/**
 * Class for SalesRepObj.
 */
class CustomerObj extends Object
{

    public function setObjectId()
    {
        $this->object_id = config('lbt.ontraport_cust_object_id');
    }

	/**
	 * Update or insert
	 * If opid field is not null then udpate if null insert.
	 */
	public function upsert(Customer $cust)
	{
		$usr        = $cust->user;
		$opEmailKey = $this->fields()->get('Email')->getKey();
        $ontraData  = [
            'Email'        => $usr->email,
            'First Name'   => $usr->firstname,
            'Last Name'    => $usr->lastname,
            'Office Phone' => $cust->officephone,
            'Address'      => $cust->address1,
            'Address 2'    => $cust->address2,
            'City'         => $cust->city,
            'State'        => $cust->state,
            'Country'      => $cust->country,
            'contact_cat'  => '*/*' . config('lbt.ontraport_tags.lc') . '*/*'
        ];

        /**
         * First check if email already exists to any.
         *
         * @var        array
         */
		$findByEmail = $this->findByEmail($usr->email, $opEmailKey);

        if(isset($findByEmail->data[0]))
        {
        	/**
        	 * if email is found set the found ontraport ID to BA.
        	 */
        	$cust->opid = $findByEmail->data[0]->id;
        	$cust->save();
        }

        if($cust->opid)
        {
            $opidExists = false;
        	//Update here
            try
            {        
                $this->update($cust->opid, $ontraData);
            }catch(\Exception $e){

            }
        	
            if(!$opidExists)
            {
                $cust->opid = null;
                $cust->save();
                $cust = $this->upsert($cust);
            }

        }else
        {
        	//Do insert
        	$fields = $this->create($ontraData);
            $cust->opid = $fields->get($this->getIdKey())->getValue();
            $cust->save();
        }

        return $cust;
	}

    /**
     * Format array of OP tag ids to valid listing of tags.
     */
    public function arrayToTagsList($ids)
    {
        $str = '*';
        foreach($ids as $id)
        {
            $str .= '/*' . $id . '*';
        }
        $str .= '/*';

        return $str;
    }

    public function updateTag($opid, $tag_ids)
    {
        $tags = $this->arrayToTagsList($tag_ids);
        $this->update($cust->opid, ['Contact Tags' => $tags]);

        return true;
    }

    public function actionRequest($action, Customer $customer, Offer $offer = null, $salesrep = null)
    {
        $typeOpKey = config('lbt.user_action_types.' . $action->type . '.op_key');
        $request_type = $action->type;
        $brand        = ($offer ? $offer->brands->first() : null);
        $brandCat     = ($brand ? $brand->categories()->first() : null);
        $dealer       = ($brand ? $brand->dealers->first() : null);
        $objFields    = [
            "Posted Item Title for LC msg" => ($offer ? $offer->title : ''),
            "Posted Item title for BA msg" => ($offer ? $offer->title : ''),
            "Type of Request from LC"      => $typeOpKey,
            "BA First Name"                => ($salesrep ? $salesrep->user->firstname : '' ),
            "BA Last Name"                 => ($salesrep ? $salesrep->user->lastname : '' ),
            "BA Phone (cell)"              => ($salesrep ? $salesrep->cellphone : '' ),
            "BA Email"                     => ($salesrep ? $salesrep->user->email : '' ),
            "BA Direct Department Ph"      => ($salesrep ? $salesrep->user->officephone : '' ),
            "Dealership Name"              => ($dealer ? $dealer->name : '' ),
            "Dealership Phone"             => ($dealer ? $dealer->phone : '' ),
            "Dealership Street Address"    => ($dealer ? $dealer->street : '' ),
            "Dealership City"              => ($dealer ? $dealer->city : '' ),
            "Dealership State"             => ($dealer ? $dealer->state : '' ),
            "LC First Name"                => $customer->user->firstname,
            "LC Last Name"                 => $customer->user->lastname,
            "LC Look Book Link"            => '',
            "LC Phone"                     => $customer->cellphone,
            "Type of Request for BA"       => $typeOpKey
        ];

        if($brand && $brand->opid)
        {
            $tagIds[] = $brand->opid;
        }

        if($brandCat && $brandCat->opid)
        {
            $tagIds[] = $brandCat->opid;
        }        

        switch ($request_type) {
            case 'direct_message':
                $tagIds[] = config('lbt.ontraport_tags.req_message');
                break;
            case 'offer_request_appt':
                $tagIds[] = config('lbt.ontraport_tags.req_appt');
                break;
            case 'offer_request_info':
                $tagIds[] = config('lbt.ontraport_tags.req_info');
                break;
            case 'offer_request_price':
                $tagIds[] = config('lbt.ontraport_tags.req_price');
                break;
            case 'offer_request_contact_me':
                $tagIds[] = config('lbt.ontraport_tags.req_contact_me');
                break;                
            default:
                
                break;
        }     

        $opidExists = false;

        try
        {
            $this->find($customer->opid);
            $opidExists = true;
        }
        catch(\Exception $e){
          
        } 

        if(!$opidExists)
        {
            $customer->opid = null;
            $customer->save();
            $this->upsert($customer);
        }

        $objFields['contact_cat'] =  $this->arrayToTagsList($tagIds);

        try
        {
            $this->update($customer->opid, $objFields);
            /**
             * Mark action has sent
             */
            $action->is_op_sent = true;
            $action->save();            

        }catch(\Exception $e){
            
            echo 'Failed OP Contact ID: ' . $customer->opid . PHP_EOL;
        }
    }
}