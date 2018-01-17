<?php 
namespace App\Storage\Ontraport;

use GuzzleHttp\Client;
use App\Storage\SalesRep\SalesRep;
use App\Storage\Ontraport\Object;
use App\Storage\Ontraport\TagObj;

/**
 * Class for SalesRepObj.
 */
class SalesRepObj extends Object
{
    public function setObjectId()
    {
        $this->object_id = config('lbt.ontraport_ba_object_id');
    }    

	/**
	 * Update or insert
	 * If opid field is not null then udpate if null insert.
	 */
	public function upsert(SalesRep $sr)
	{
        $usr        = $sr->user;
        $opEmailKey = $this->fields()->get('Email')->getKey();
        $dealer     = $sr->dealers->first();
		$ontraData  = [
            'Email'        => $usr->email,
            'First Name'   => $usr->firstname,
            'Last Name'    => $usr->lastname,
            'Office Phone' => $sr->officephone,
            'Company'      => ($dealer ? $dealer->name : ''),
            'contact_cat'  => '*/*' . config('lbt.ontraport_tags.ba') . '*/*'
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
        	$sr->opid = $findByEmail->data[0]->id;
        	$sr->save();
        }

        if($sr->opid)
        {
        	//Update here
        	//$this->update($sr->opid, $ontraData);

            $opidExists = false;
            //Update here
            try
            {        
                $this->update($sr->opid, $ontraData);
            }catch(\Exception $e){

            }
            
            if(!$opidExists)
            {
                $sr->opid = null;
                $sr->save();
                $sr = $this->upsert($sr);
            }

        }else
        {
        	//Do insert
        	$fields = $this->create($ontraData);
            $sr->opid = $fields->get($this->getIdKey())->getValue();
            $sr->save();        	
        }

        return $sr;
	}
}