<?php namespace App\Storage\Page;

use Auth;

class AllowedRoles
{
	protected $only = [];

	protected $except = [];

	public function addOnly($role_name = null)
	{
		if(!$role_name)
		{
			return $this;
		}

		if(is_array($role_name))
		{
			foreach($role_name as $role)
			{
				$this->addOnly($role);
			}
			
		}else{
			$this->only[] = $role_name;
		}

		return $this;	
	}

	public function addExcept($role_name = null)
	{
		if(!$role_name)
		{
			return $this;
		}

		if(is_array($role_name))
		{
			foreach($role_name as $role)
			{
				$this->addExcept($role);
			}
			
		}else{
			$this->except[] = $role_name;
		}

		return $this;		
	}

	public function isCurrentAllowed()
	{
		$user         = Auth::user();
		$currentRoles = $user->roles->lists('name')->toArray();

		if(empty($this->only) && empty($this->except)){

			return true;
		}

		$onlyDiff = array_diff($this->only, $currentRoles);
		$exceptDiff = array_diff($this->except, $currentRoles);

		if(count($onlyDiff) < count($this->only) && !empty($this->only))
		{

			return true;
		}

		if(count($exceptDiff) < count($this->except) && !empty($this->except))
		{
			return false;
		}elseif(!empty($this->except))
		{
			return true;
		}

		return false;
	}
}