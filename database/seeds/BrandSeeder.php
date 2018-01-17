<?php

use Illuminate\Database\Seeder;
use App\Storage\Brand\BrandRepository;
use App\Storage\Category\Category;

class BrandSeeder extends Seeder
{
	protected $brand;

	public function __construct(BrandRepository $brand)
	{
		$this->brand = $brand;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $caegories = [];
        foreach(Category::get() as $category)
        {
            $caegories[$category->name] = $category;
        }

        $default = [
            "Audi"                    => "Automotive",
            "BMW"                     => "Automotive",
            "Cadillac"                => "Automotive",
            "Ferrari"                 => "Automotive",
            "Lexus"                   => "Automotive",
            "Mercedes-Benz"           => "Automotive",
            "Bloomingdale's"          => "Fashion",
            "Brooks Brothers"         => "Fashion",
            "Neiman Marcus"           => "Fashion",
            "Nordstrom"               => "Fashion",
            "Saks 5th Avenue"         => "Fashion",
            "Tiffany & Co"            => "Fashion",
            "Ethan Allen"             => "Home Decor",
            "Havertys"                => "Home Decor",
            "Christies International" => "Properties",
            "Sothebys International"  => "Properties"];

    	foreach($default as $name => $catName)
    	{
            $foundBrand = $this->brand->skipPresenter()->findByField('name', $name);

            if($foundBrand && $foundBrand->count() < 1){
                $brand = $this->brand->skipPresenter()->create([
                    'name' => $name ]);
                
                $brand->categories()->save($caegories[$catName]);
            }
    	}
    }	
}