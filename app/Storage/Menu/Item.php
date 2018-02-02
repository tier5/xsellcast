<?php namespace App\Storage\Menu;

use Lavary\Menu\Item as BaseItem;
use App\Storage\Menu\AllowedRoles;
use Auth;

class Item extends BaseItem
{
	protected $allowed_roles;

	protected $salesrep_restricted = false;

	protected $count_url = null;

	public function __construct($builder, $id, $title, $options)
	{
		parent::__construct($builder, $id, $title, $options);
		$this->allowed_roles = new AllowedRoles;
	}	

	public function forRoleOnly($roles)
	{	

		$this->allowed_roles->addOnly($roles);

		return $this;
	}

	public function forRoleExcept($roles)
	{	

		$this->allowed_roles->addExcept($roles);

		return $this;
	}		

	public function isVisibleForUser()
	{
		return $this->allowed_roles->isCurrentAllowed();
	}

	public function restricSalesRep()
	{
		$user                      = Auth::user();
		$isSalesRep                = $user->hasRole('sales-rep');
		$this->salesrep_restricted = ($isSalesRep && !$user->salesRep->is_agreement);

		if(!$this->salesrep_restricted && $isSalesRep)
		{	
			$this->salesrep_restricted = (!$user->salesRep->password_changed);
		}

		if($this->salesrep_restricted)
		{
			$this->attributes['class'] = " salesrep-disabled";
		}
		
		return $this;
	}

	public function setCountRoute($route_str)
	{
		$this->data('count_route', $route_str);

		$this->count_url = route($route_str);
		return $this;
	}

	public function getCountUrl()
	{
		return $this->count_url ;
	}

	public function isSalesRepRestricted()
	{
		return $this->salesrep_restricted;
	}
}