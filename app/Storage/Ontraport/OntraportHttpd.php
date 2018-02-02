<?php 
namespace App\Storage\Ontraport;

use GuzzleHttp\Client;
//use Kzap\Ontraport\Api\Sdk as OntraportKzap;
use App\Storage\Ontraport\Sdk as OntraportKzap;

/**
 * Class for Ontraport httpd.
 */
class OntraportHttpd
{

	protected $api_key;

	protected $api_id;

	protected $ontraport_kzap;

	/**
	 * Fields for customers
	 * @var array
	 */
	protected $customer_op = array(
		'key' => 'Customers',
		'fields' => array(
			'email' 				=> 'f1628',
			'customer_id'			=> 'f1652',
			'firstname' 			=> 'f1626',
			'lastname' 				=> 'f1627'
	));

	/**
	 * Fields for salesrep(Contacts)
	 *
	 * @var        array
	 */
	protected $salesrep_op = array(
		'key' => 'Brand Associates',
		'fields' => array(
			'email'       => 'email',
			'firstname'   => 'firstname',
			'lastname'    => 'lastname',
			'salesrep_id' => 'xsellcast_id'
	));

	/**
	 * [$offer_customer description]
	 * @var array
	 */
	protected $offer_customer_op = array(
		'key' => 'Offer + Customers',
		'fields' => array(
			'count' => 'f1651',
			'customer_id' => 'f1650'
	));

	public function __construct()
	{	
		$this->api_key = config('lbt.ontraport_key');
		$this->api_id = config('lbt.ontraport_id');
		$this->ontraport_kzap = new OntraportKzap($this->api_id, $this->api_key);
	}

	public function kzap()
	{

		return $this->ontraport_kzap;
	}

	public function saveOrUpdateCustomer($args = array())
	{
		return $this->objectsSaveOrUpdate($args, $this->customer_op['key'], $this->customer_op['fields']);
	}

	public function saveOrUpdateSalesRep($args = array())
	{
		return $this->objectsSaveOrUpdate($args, $this->salesrep_op['key'], $this->salesrep_op['fields']);
	}

	public function saveOrUpdateOfferCustomer($args = array())
	{
		return $this->objectsSaveOrUpdate($args, $this->offer_customer_op['key'], $this->offer_customer_op['fields']);
	}

	/**
	 * 
	 * @param  Array 	$args
	 * @param  String 	$obj_key
	 * @param  Array 	$fields
	 * @return Array
	 */
	public function objectsSaveOrUpdate($args, $obj_key, $fields)
	{
		$objectId = $this->ontraport_kzap->getObjectTypeByName($obj_key);
		$param = array('objectID' => $objectId);

		foreach ($fields as $key => $val) {
			if(isset($args[$key])){
				$param[$val] = $args[$key];
			}
		}

		if(isset($args['ids'])){
			$param['ids'] = (is_array($args['ids']) ? implode(',', $args['ids']) : $args['ids'] );
		}

		$obj = $this->ontraport_kzap->upsertObject($param);
		
		return $obj;
	}

	public function apiKey()
	{
		return $this->api_key;
	}

}

?>