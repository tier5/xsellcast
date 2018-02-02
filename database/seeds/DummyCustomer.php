<?php use Illuminate\Database\Seeder;

class DummyCustomer extends Seeder
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

		factory(App\Storage\User\User::class, 'customer', 50)->create()->each(function ($user) use($customerRole, $cities){
			$loc                = $cities->shuffle()->first();
			$customer           = $user->customer()->save(factory(App\Storage\Customer\Customer::class)->make());
			$customer->zip      = $loc->Zipcode;
			$customer->city     = $loc->City;
			$customer->state    = $loc->State;
			$customer->country  = 'US';
			$customer->geo_long = $loc->Longitude;
			$customer->geo_lat  = $loc->Latitude;	
					
			$customer->save();
			$user->attachRole($customerRole);
		});
    }  
}