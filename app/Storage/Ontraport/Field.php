<?php

namespace App\Storage\Ontraport;

use Illuminate\Support\Collection;

class Field
{
	protected $builder, $alias, $key, $value;

	public function __construct($builder, $alias, $key)
	{
		$this->builder = $builder;
		$this->alias   = $alias;
		$this->key     = $key;
		$this->value   = null;
	}

	public function getAlias()
	{

		return $this->alias;
	}

	public function getKey()
	{

		return $this->key;
	}

	public function setValue($v)
	{
		$this->value = $v;

		return $this;
	}

	public function getValue()
	{

		return $this->value;
	}

	public function add($alias, $key)
	{
		return $this->builder->add($alias, $key);
	}	
}