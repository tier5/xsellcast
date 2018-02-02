<?php

namespace App\Storage\Crud;

class Box
{
	protected $info;

	protected $form;

	protected $table;

	public function __construct($info = null)
	{
		$this->info = $info;
	}

	public function setForm($form)
	{
		$this->form = $form;

		return $this;
	}

	public function setTable($table)
	{
		$this->table = $table;

		return $this;
	}

	public function setInfo($info)
	{
		$this->info = $info;

		return $this;
	}

	public function getForm()
	{
		return $this->form;
	}

	public function getInfo()
	{
		return $this->info;
	}

	public function getTable()
	{
		return $this->table;
	}
}