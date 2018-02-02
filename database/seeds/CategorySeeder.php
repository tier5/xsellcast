<?php

use Illuminate\Database\Seeder;
use App\Storage\Category\CategoryRepository;

class CategorySeeder extends Seeder
{
	protected $category;

	public function __construct(CategoryRepository $category)
	{
		$this->category = $category;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$default  = ['Automotive', 'Fashion', 'Home Decor', 'Properties'];

    	foreach($default as $name)
    	{
    		$this->category->create([
    			'name' => $name]);
    	}
    }	
}