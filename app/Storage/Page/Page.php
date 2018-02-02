<?php namespace App\Storage\Page;

use Closure;
use Auth;
use Illuminate\Support\Collection;
use App\Storage\Page\Builder;

class Page{

	protected $pages = null;

	public function __construct()
	{
		//$this->collection = new Collection();
	}

	public static function load()
	{
		$s = new Page();

		return $s->init();
	}

	public function make($callback = null)
	{
		if(!is_callable($callback))
		{
			return null;
		}

		$this->pages = new Builder();

		// Registering the items
		call_user_func($callback, $this->pages);

		// Storing each menu instance in the collection
	//	$this->collection->put($name, $this->menu[$name]);
		
		return $this->pages;		
	}

	public function getCurrent()
	{
		return $this->pages->getCurrent();
	}
}