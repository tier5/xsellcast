<?php namespace App\Storage\Menu;

use Lavary\Menu\Builder as BaseBuilder;
use App\Storage\Menu\Item;

class Builder extends BaseBuilder
{

	/**
	 * Initializing the menu manager
	 *
	 * @return void
	 */
	public function __construct($name, $conf)
	{
		parent::__construct($name, $conf);

	}

	/**
	 * Adds an item to the menu
	 *
	 * @param  string  $title
	 * @param  string|array  $acion
	 * @return Lavary\Menu\Item $item
	 */
	public function add($title, $options = '')
	{
	
		$id = isset($options['id']) ? $options['id'] : $this->id();

		$item = new Item($this, $id, $title, $options);
                      
		$this->items->push($item);
		
		return $item;
	}

}