<?php namespace App\Storage\Page;

use App\Storage\Page\Item;
use Illuminate\Support\Collection;

class Builder
{

	protected $items;

	public function __construct()
	{
		$this->items = new Collection;
	}

	/**
	 * Adds an item to the menu
	 *
	 * @param  string  $title
	 * @param  string|array  $acion
	 * @return Lavary\Menu\Item $item
	 */
	public function add($route_name,  $title = null)
	{
	
		$item = new Item($this, $route_name, $title);
                      
		$this->items->push($item);
		
		return $item;
	}	

	public function getItems()
	{
		return $this->items;
	}

	public function getCurrent()
	{
		$currentRouteName = \Request::route()->getName();

		foreach($this->getItems() as $item)
		{
			if($item->isCurrent())
			{
				return $item;
			}
		}

		return null;
	}
}

?>