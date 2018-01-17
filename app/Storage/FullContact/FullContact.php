<?php namespace App\Storage\FullContact;

use App\Storage\FullContact\ContactInfo;

class FullContact
{

	protected $api_key;

	public function __construct()
	{
		$this->api_key = config('lbt.fullcontact_key');
	}

	public function person()
	{
		return new \Services_FullContact_Person($this->api_key);
	}

	public function personLookupByEmail($email)
	{
		$person   = $this->person()->lookupByEmail($email);

		return new ContactInfo($person);
	}
} 

?>