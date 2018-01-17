<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InitialRoleTableSeeder extends Seeder
{	

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$roles = array(
		    array(
				'name'         => 'sales-rep',
				'display_name' => 'Brand Associate'
		    ),
		    array(
				'name'         => 'admin',
				'display_name' => 'Administrator'
		    ),
		    array(
				'name'         => 'customer',
				'display_name' => 'Customer'
		    ),
		    array(
				'name'         => 'broyhill-cs',
				'display_name' => 'Broyhill Customer Service'
		    ),
		    array(
				'name'         => 'csr',
				'display_name' => 'Customer Service Representative'
		    )
		);

		foreach($roles as $role){
			$roleObj = new App\Storage\Role\Role();

			if($roleObj->where('name', $role['name'])->count() == 0){
			    $roleObj->name = $role['name'];
			    $roleObj->display_name = $role['display_name'];
			    $roleObj->save();
			}
		}

		$oathClients = array(
			array(
				'id' => 'wdrp9kd64otflrytzrkvp9',
				'secret' => 'zb4finmvkc09g8kvrsxxl6kscg2x3ku',
				'name' => 'LBT wp site'
			),
			array(
				'id' => 'f3d259ddd3ed8ff3843839b',
				'secret' => '4c7f6f8fa93d59c45502c0ae8c4a95b',
				'name' => 'Main site'
			)
		);

		foreach($oathClients as $client)
		{
			$clientObj = new App\Storage\OAuthClient\OAuthClient();

			$c = $clientObj->where('id', $client['id'])
				->where('secret', $client['secret'])
				->count();

			if($c == 0){
				$clientObj->id = $client['id'];
				$clientObj->secret = $client['secret'];
				$clientObj->name = $client['name'];
				$clientObj->save();
			}
		}

		$this->csrAccounts();
    }

    public function csrAccounts()
    {
		$csr_users_stag = array(
			array(
				'email' => 'csr-001@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'csr-002@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'csr-003@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'info@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
	//		array(
	//			'email' => 'sharon@caffeineinteractive.com',
	//			'password' => bcrypt('lbt01LBT')
	//		),
//			array(
//				'email' => 'devries.sharon@gmail.com',
//				'password' => bcrypt('lbt01LBT')
//			)
		);	

		$csr_users_local = array(
			array(
				'email' => 'info@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'info+001@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'info+002@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			),
			array(
				'email' => 'info+003@caffeineinteractive.com',
				'password' => bcrypt('lbt01LBT')
			)
		);	

		$csrRole = App\Storage\Role\Role::where('name', 'csr')->first();

		if(config('app.env') == 'local')
		{
			$csrUsers = $csr_users_local;
		}else{

			$csrUsers = $csr_users_stag;
		}

		foreach ($csrUsers as $row) {
			$user = new App\Storage\User\User();
			$c = $user->where('email', $row['email'])->count();
			$faker = \Faker\Factory::create();

			if($c == 0)
			{
				$user->email = $row['email'];
				$user->firstname = $faker->firstName;
				$user->lastname = $faker->lastName;
				$user->password = $row['password'];
				$user->save();
				$user->attachRole($csrRole);
			}
		}    	
    }

    public function broyHillsAccounts()
    {
		$broyhillCsRole = App\Storage\Role\Role::where('name', 'broyhill-cs')->first();

		$broyhillCsUsers = array(
			array(
				'email' => 'developer@caffeineinteractive.com',
				'firstname' => 'John Dev',
				'lastname' => 'Doe',
				'password' => bcrypt('lbt01LBT')
			)			
		);

		foreach ($broyhillCsUsers as $row) {
			$user = new App\Storage\User\User();
			$c = $user->where('email', $row['email'])->count();

			if($c == 0)
			{
				$user->email = $row['email'];
				$user->firstname = $row['firstname'];
				$user->lastname = $row['lastname'];
				$user->password = $row['password'];
				$user->save();
				$user->attachRole($broyhillCsRole);
			}
		}    	
    }
}
