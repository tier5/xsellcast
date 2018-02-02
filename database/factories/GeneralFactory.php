<?php 

$factory->define(App\Storage\Dealer\Dealer::class, function (Faker\Generator $faker) {

    $company  = $faker->company;
    $dateTime = date('Y-m-d H:i:s');
    $city     = App\Storage\CityState\CityState::whereIn('state', ['NY', 'TX', 'MI'])->orderBy(DB::raw('RAND()'))->first();
    $hoo      = array();

    foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
    {
        $hoo[$day]['from'] = rand(0, 24) . '00';
        $hoo[$day]['to']   = rand(0, 24) . '30';

        if (rand(0, 1)) {
            $hoo[$day]['closed'] = 1;    
        }
    }

    return [
        'name'               => ucfirst($company). ' Dealer',
        'address1'           => $faker->address,
        'street_name'        => $faker->streetName,
        'street'             => $faker->streetAddress,
        'apt'                => '',
        'fax'                => $faker->phoneNumber,
        'website'            => 'http://www.domain.com',
        'city'               => $city->city,
        'zip'                => $city->zip,
        'state'              => $city->state,
        'country'            => 'US',
        'phone'              => $faker->phoneNumber, 
        'email'              => $faker->safeEmail,
        'geo_long'           => $city->get_long,
        'geo_lat'            => $city->get_lat,
        'created_at'         => $dateTime,
        'updated_at'         => $dateTime,
        'hours_of_operation' => serialize($hoo)
    ];
});

$factory->define(App\Storage\Brand\Brand::class, function (Faker\Generator $faker) {

	$company = $faker->company;
	$dateTime = date('Y-m-d H:i:s');
	
    return [
        'name' => ucfirst($company) . ' Inc.',
        'parent_id' => 0,
        'created_at' => $dateTime,
        'updated_at' => $dateTime
    ];
});

$factory->define(App\Storage\DealersCategory\DealersCategory::class, function (Faker\Generator $faker) {

    $name = $faker->words(rand(1, 3), true);
    $dateTime = date('Y-m-d H:i:s');
    
    return [
        'name' => ucfirst($name),
        'created_at' => $dateTime,
        'updated_at' => $dateTime
    ];
});

$factory->define(App\Storage\Offer\Offer::class, function (Faker\Generator $faker) {

    $dateTime = date('Y-m-d H:i:s');
    $ytIds = array('mQgNZCBqckk', '', 'sC9abcLLQpI', 'xmrs5f4RGow');
    $ret = [
        'contents' => $faker->paragraphs(3, true),
        'pdf_contents' => $faker->paragraphs(3, true),
        'title' => ucfirst($faker->words(rand(1, 3), true)),
        'youtube_id' => $ytIds[rand(0, 3)],
        'original_source_url' => $faker->url,
        'created_at' => $dateTime,
        'updated_at' => $dateTime
    ];

    return $ret;
});

$factory->define(App\Storage\User\User::class, function (Faker\Generator $faker) {

    $dateTime = date('Y-m-d H:i:s');
    $ret = [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt('lbt01LBT'),
        'remember_token' => str_random(10),
        'created_at' => $dateTime,
        'updated_at' => $dateTime
    ];

    return $ret;
}, 'sales_rep');

$factory->define(App\Storage\User\User::class, function (Faker\Generator $faker) {

    $dateTime = date('Y-m-d H:i:s');
    $ret = [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt('lbt01LBT'),
        'remember_token' => str_random(10),
        'created_at' => $dateTime,
        'updated_at' => $dateTime        
    ];

    return $ret;
}, 'customer');

$factory->define(App\Storage\SalesRep\SalesRep::class, function (Faker\Generator $faker) {

    $dateTime = date('Y-m-d H:i:s');
    $ret = [
        'user_id' => $faker->paragraphs(3, true),
        'opid' => 0,
        'created_at' => $dateTime,
        'updated_at' => $dateTime        
    ];

    return $ret;
});

$factory->define(App\Storage\Customer\Customer::class, function (Faker\Generator $faker) {

    $city     = App\Storage\CityState\CityState::whereIn('state', ['NY', 'TX', 'MI'])->orderBy(DB::raw('RAND()'))->first();

    $dateTime = date('Y-m-d H:i:s');
    $ret = [
        'user_id'    => $faker->paragraphs(3, true),
        'created_at' => $dateTime,
        'updated_at' => $dateTime,
        'zip'        => $city->zip,
        'state'      => $city->state,
        'country'    => 'US',
        'city'       => $city->city,
        'geo_long'   => $city->geo_long,
        'geo_lat'    => $city->geo_lat
    ];

    return $ret;
}); 

?>