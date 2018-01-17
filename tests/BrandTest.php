<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;

class BrandTest extends TestCase
{
    public function logincsr()
    {
        $email = 'csr-001@caffeineinteractive.com';
        $pass  = 'lbt01LBT';

        $this->visit('auth/login');
        $this->see("Don't have an account yet?");
        $this->type($email, 'email');
        $this->type($pass, 'password');
        $this->press('Login');   
        $this->assertTrue(Auth::check()); 

        return Auth::user();   
    }

	function testCreateUi()
	{

		$user = $this->logincsr();

		$this->visit(route('admin.brands.create'));
		$this->see("Brand Name");
		$this->see("Logo");
		$this->see("Brand Description");
		$this->see("Brand Catalog URL");
		$this->see("Default Images");
		$this->see("Default Images");
	}

}