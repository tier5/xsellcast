<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use App\Storage\User\User;

class BaUserTest extends TestCase
{
    public function loginBa()
    {
        $user = User::forSalesReps()->first();
        Auth::login($user);

      //  $email = 'ba-bmw@caffeineinteractive.com';
      //  $pass  = 'lbt01LBT';

 //       $this->visit('auth/login');
 //       $this->see("Don't have an account yet?");
 //       $this->type($email, 'email');
 //       $this->type($pass, 'password');
 //       $this->press('Login');   
 //       $this->assertTrue(Auth::check()); 
        $this->assertTrue(Auth::check()); 
        $this->visit(route('home'));

        return $user;
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBaLogin()
    {

        $email = 'ba-bmw@caffeineinteractive.com';
        $pass  = 'lbt01LBT';

        $this->visit('auth/login');
        $this->see("Don't have an account yet?");
        $this->type($email, 'email');
        $this->type($pass, 'password');
        $this->press('Login');   
        $this->assertTrue(Auth::check()); 
        $this->visit(route('home'));

    }

    public function testBaLogout()
    {
        $this->loginBa();
        $this->seePageIs(route('home'));  
        $this->click('Log out');
        $this->see("Or log in with");
    }

    public function testMessage()
    {
        $this->loginBa();
        $this->visit(route('admin.messages'))
            ->see("Inbox Messages");
    }

    public function testCreateMessage()
    {
        $faker    = Factory::create();
        $salesrep = $this->loginBa()->salesRep;
        $customer = $salesrep->customers->first();

        $this->visit(route('admin.messages'));

        //Goto add new message.
        $this->click('New Message');
        $this->seePageIs(route('admin.messages.create'));
        $this->see("Compose new message");
        $this->submitForm('Send', ['body' => $faker->paragraphs(1, true), 'to' => $customer->user->email, 'subject' => $faker->sentence(2)]);
        $this->see('Message has been sent.');  
    }

    public function testDiscardMessage()
    {
        $faker    = Factory::create();
        $salesrep = $this->loginBa()->salesRep;
        $customer = $salesrep->customers->first();

        $this->visit(route('admin.messages'));

        //Goto add new message.
        $this->click('New Message');
        $this->seePageIs(route('admin.messages.create'));
        $this->see("Compose new message");
        $this->submitForm('Discard', ['body' => $faker->paragraphs(1, true), 'to' => $customer->user->email, 'subject' => $faker->sentence(2)]);
        $this->see('Sent Messages');
    }

    public function testDraftMessage()
    {
        $faker    = Factory::create();
        $salesrep = $this->loginBa()->salesRep;
        $customer = $salesrep->customers->first();

        $this->visit(route('admin.messages'));

        //Goto add new message.
        $this->click('New Message');
        $this->seePageIs(route('admin.messages.create'));
        $this->see("Compose new message");
        $this->submitForm('Draft', ['body' => $faker->paragraphs(1, true), 'to' => $customer->user->email]);
        $this->see('Message has been save to drafts.');
    }  

    public function testApptMessage()
    {
        $this->loginBa();
        $this->visit('admin/messages/appt');
        $this->see("Inbox Messages: Appointment Requests");
    } 

    public function testPriceMessage()
    {
        $this->loginBa();
        $this->visit('admin/messages/price');
        $this->see("Inbox Messages: Price Requests");
    }   

    public function testInfoMessage()
    {
        $this->loginBa();
        $this->visit('admin/messages/info');
        $this->see("Inbox Messages: Information Requests");
    }    

    public function testDirectMessage()
    {
        $this->loginBa();
        $this->visit('admin/messages/message');
        $this->see("Inbox Messages: Direct Messages");
    }           

    public function testSearchMessage()
    {
        $this->loginBa();
        $this->visit('admin/messages');
        $this->see("Inbox Messages");

        $this->submitForm('Search', ['s' => 'Find text']);
        $this->see("Find text");
    }         

    public function testWelcomePage()
    {
        $this->loginBa();
        $this->visit(route('admin.welcome.salesrep'));     
        
        $this->see('Click "PROSPECTS" in the left menu. Your prospects that have been matched with you are found in "ALL PROSPECTS" and as you are matched with new names you\'ll find those under "NEW PROSPECTS". Click on "ALL PROSPECTS" and then on a name to view the prospect\'s details.');

        $this->see('Click "MESSAGES" in the left menu. Filters are available to see which type of requests are sent your way. Select "ALL MESSAGES" and then on a message subject to view the message and respond.');
    } 

    public function testAllProspect()
    {
        $user = $this->loginBa();
        $this->click('All Prospects');
        $this->see("Keyword search");

        return $user;
    }

    public function testShowProspect()
    {
        $user = $this->testAllProspect();
        $prospect = $user->salesRep->customers->first()->user;
        $prosName = $prospect->firstname . ' ' . $prospect->lastname;
        /////
        echo PHP_EOL;
        foreach($user->salesRep->customers as $c)
        {
            echo $c->user->firstname . ' ' . $c->user->lastname . PHP_EOL;
        }
        ///
        $this->click($prosName);
        $this->see("Activity");

    }
}
