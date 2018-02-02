<?php 
namespace App\Storage\Ontraport;

use GuzzleHttp\Client;
use App\Storage\Ontraport\OntraportHttpd;
use App\Storage\Ontraport\FieldBuilder;

/**
 * Class for Ontraport Object.
 */
class Object
{
	protected $object_id = null;

	protected $fields = null;

	protected $httpd = null;

	public function __construct()
	{
		$this->httpd = new OntraportHttpd();
		$this->setObjectId();
		$this->setFields();
	}

	public function fields()
	{

		return $this->fields;
	}

	protected function setFields()
	{

		$d = $this->httpd->kzap()->getObjectTypes(['objectID' => $this->object_id]);
		$this->fields = new FieldBuilder();

		foreach($d->data->{$this->object_id}->fields as $k => $row)
		{
			$this->fields->add($row->alias, $k);
		}

		return $this;
	}

	/**
	 * TODO: this is not yet in use. Properly show data.
	 *
	 * @param      <type>  $id     The identifier
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	public function find($id)
	{
		$data = $this->httpd->kzap()->getObject(['objectID' => $this->object_id, 'id' => $id]);

		return $data;
	}

	/**
	 *
	 * @return     Collection
	 */
	public function objects()
	{
		$data = $this->httpd->kzap()->getObjects(['objectID' => $this->object_id]);

		return collect($data->data);		
	}

	/**
	 * Create new Ontraport Object
	 *
	 * @param      array   $data   The data
	 *
	 * @return     FieldBuilder
	 */
	public function create($data = [])
	{
		$objData = [];

		if(!isset($data['Email']))
		{
			abort(422, 'Email field is required.');
		}

		if(!isset($data['First Name']))
		{
			abort(422, 'First name field is required.');
		}	

		if(!isset($data['Last Name']))
		{
			abort(422, 'Last name field is required.');
		}				

		foreach($this->fields()->getFields() as $field)
		{

			if(isset($data[$field->getAlias()]))
			{
				$objData[$field->getKey()] = $data[$field->getAlias()];
			}
		}

		if(isset($data['contact_cat']))
		{
			$objData['contact_cat'] = $data['contact_cat'];
		}		
		
		if(empty($objData))
		{
			abort(422, 'No valid data.');		
		}

		$objData['objectID'] = $this->object_id;
		$ret                 = $this->httpd->kzap()->createObject($objData);

		foreach($ret->data as $k => $v)
		{
			$this->fields()->setValueByKey($k, $v);
		}

		return $this->fields();
	}

	public function update($id, $data = [])
	{
		$objData = [];			

		foreach($this->fields()->getFields() as $field)
		{

			if(isset($data[$field->getAlias()]))
			{
				$objData[$field->getKey()] = $data[$field->getAlias()];
			}
		}

		if(isset($data['contact_cat']))
		{
			$objData['contact_cat'] = $data['contact_cat'];
		}
		
		if(empty($objData))
		{
			abort(422, 'No valid data.');		
		}

		$objData['objectID'] = $this->object_id;
		$objData['id']       = $id; 
		$ret                 = $this->httpd->kzap()->updateObject($objData);

		foreach($ret->data as $k => $v)
		{
			$this->fields()->setValueByKey($k, $v);
		}

		return $this->fields();
	}

	public function findByEmail($email, $key)
	{
		$conditionSearch = '[{ "field":{"field":"' . $key . '"}, "op":"=", "value":{"value":"' . $email . '"} }]';
		$objData         = ['objectID' => $this->object_id, 'condition' => $conditionSearch];

		return $this->httpd->kzap()->getObjects($objData);
	}

    public function getIdKey()
    {
        return config('lbt.ontraport_contact_id_key');
    }	
}