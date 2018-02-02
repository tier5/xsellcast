<?php 
namespace App\Storage\Ontraport;

use GuzzleHttp\Client;
use App\Storage\Ontraport\Object;

/**
 * Class for SalesRepObj.
 */
class TagObj extends Object
{
    public function setObjectId()
    {
        $this->object_id = 14; #Default Tag object ID
    }

	public function findByName($tag_name)
	{
		$conditionSearch = '[{ "field":{"field":"tag_name"}, "op":"=", "value":{"value":"' . $tag_name . '"} }]';
		$objData         = ['objectID' => $this->object_id, 'condition' => $conditionSearch];

		return $this->httpd->kzap()->getObjects($objData);
	}    
}