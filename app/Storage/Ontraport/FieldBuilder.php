<?php

namespace App\Storage\Ontraport;

use App\Storage\Ontraport\Field;
use Illuminate\Support\Collection;

class FieldBuilder
{

	protected $fields;

	public function __construct()
	{
		$this->fields = new Collection;
	}

	public function add($alias, $key)
	{
		$field = new Field($this, $alias, $key);
                      
		$this->fields->push($field);
		
		return $field;
	}	

	public function getFields()
	{
		return $this->fields;
	}

	public function get($alias)
	{
		foreach($this->fields as $row)
		{
			if($row->getAlias() == $alias)
			{
				return $row;
			}
		}

		return null;
	}

	public function setValueByKey($key, $value)
	{
		foreach($this->fields as $k => $row)
		{
			if($row->getKey() == $key)
			{
				$this->fields[$k]->setValue($value);
			}
		}

		return $this;
	}
}

?>