<?php namespace App\Storage\Page;

use Menu;
use App\Storage\Page\AllowedRoles;

class Item
{

	protected $items;
	
	protected $builder;

	protected $route_name;

	protected $breadcrumb = null;

	protected $allowed_roles;

	protected $salesrep_restricted = false;

	public function __construct($builder, $route_name, $title)
	{
		$this->builder       = $builder;
		$this->title         = $title;
		$this->route_name    = $route_name;
		$this->allowed_roles = new AllowedRoles;
	}

	public function add($route_name,  $title = null)
	{

		return $this->builder->add($route_name, $title);
	}	

	public function breadcrumb($callback = null)
	{
		if(!is_callable($callback)){

			return $this;
		}

		/**
		 * Don't register breadcrumb items when not in current page.
		 */
		if(!$this->isCurrent())
		{
			return $this;
		}

		$this->breadcrumb = Menu::make('breadcrumb', function($m) use($callback){
			$m->Add('Home', ['route' => 'home']);

			call_user_func($callback, $m);
		});

		return $this;
	}

	public function setTitle($callback)
	{

		if($this->isCurrent()){
			$this->title = call_user_func($callback, $this);
		}
		

		return $this;
	}

	public function getBreadcrumb()
	{
		return $this->breadcrumb; //->get('breadcrumb');
	}

	/**
	 * Gets the route name.
	 *
	 * @return     String  The route name.
	 */
	public function getRouteName()
	{

		return $this->route_name;
	}

	/**
	 * Determines if current.
	 *
	 * @return     boolean  True if current, False otherwise.
	 */
	public function isCurrent()
	{
		$currentRouteName = \Request::route()->getName();

		return ($currentRouteName == $this->getRouteName());
	}

	public function  getTitle()
	{
		return $this->title;
	}

	public function allowRole($arr)
	{
		$except = null;
		$only = null;

		if(isset($arr['only'])){
			$only = (!is_array($arr['only']) ? [$arr['only']] : $arr['only']);
		}

		if(isset($arr['except'])){
			$except = (!is_array($arr['except']) ? [$arr['except']] : $arr['except']);
		}	

		$this->allowed_roles->addOnly($only);	
		$this->allowed_roles->addExcept($except);	

		return $this;
	}

	public function isCurrentUserAllowed()
	{
		return $this->allowed_roles->isCurrentAllowed();
	}

	/**
	 * Restric BA which is not accepting aggrement.
	 *
	 * @param      boolean  $bool   The bool
	 *
	 * @return     self
	 */
	public function toRestricSalesRep($bool = true)
	{

		$this->salesrep_restricted = $bool;

		return $this;
	}

	public function isRestricSalesRep()
	{

		return $this->salesrep_restricted;
	}
}

?>