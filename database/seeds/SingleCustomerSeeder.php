<?php use Illuminate\Database\Seeder;

class SingleCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roleObj = new App\Storage\Role\Role();
    	$customerRole = $roleObj->where('name', 'customer')->first();

    	$cities = collect(SeederHelpers::cities('IL')->splice(0, 20)->toArray())
    		->merge(SeederHelpers::cities('TX')->splice(0, 20)->toArray())
    		->shuffle()->splice(0, 20);

		factory(App\Storage\User\User::class, 'customer', 1)->create()->each(function ($user) use($customerRole, $cities){
			$loc                = $cities->shuffle()->first();
			$customer           = $user->customer()->save(factory(App\Storage\Customer\Customer::class)->make());
			$customer->zip      = 78704; //$loc->Zipcode;
			$customer->city     = 'Austin'; //$loc->City;
			$customer->state    = 'TX'; //$loc->State;
			$customer->country  = 'US';
			$customer->geo_long = -97.7862226; //$loc->Longitude;
			$customer->geo_lat  = 30.2416049; //$loc->Latitude;	
					
			$customer->save();
			$user->attachRole($customerRole);
		});
    }  
}