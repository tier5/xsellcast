<?php

use Illuminate\Database\Seeder;
use App\Storage\Messenger\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Storage\Media\MediaRepository;
use App\Storage\Offer\OfferRepository;
use App\Storage\CustomerRequest\CustomerRequest;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Messenger\ThreadRepository;
use App\Storage\Category\Category;
use App\Storage\Customer\CustomerSalesRep;
use App\Storage\Customer\Customer;
use App\Storage\Brand\Brand;
use App\Storage\Offer\Offer;
use App\Storage\Csr\CsrRepository;

class GeneralTableSeeder extends Seeder
{
    protected $media;

    protected $offer;

    protected $medias;

    protected $customer_request;

    protected $customer;

    protected $thread;

    protected $csr;

    public function __construct(MediaRepository $media, OfferRepository $offer, CustomerRepository $customer, ThreadRepository $thread, CsrRepository $csr)
    {
        $this->media            = $media;
        $this->offer            = $offer;
        $this->medias           = $this->createSetOfMedia();
        $this->customer_request = new CustomerRequest();
        $this->customer         = $customer;
        $this->thread           = $thread;
        $this->csr              = $csr;
    }

    public function defaultEmail()
    {
        return [
            'salesrep@caffeineinteractive.com',
            'ba-bmw@caffeineinteractive.com',
            'ba-audi@caffeineinteractive.com'
        ];
    }

    public static function theDefaultEmail()
    {
        return [
            'salesrep@caffeineinteractive.com',
            'ba-bmw@caffeineinteractive.com',
            'ba-audi@caffeineinteractive.com'
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(InitialRoleTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(DummyCustomer::class);

        $roleObj = new App\Storage\Role\Role();
        $salesRepRole = $roleObj->where('name', 'sales-rep')->first();
        $customers = Customer::get();

        $this->createDealers(function($dealer, $brand) use($customers, $salesRepRole){
          /**
           * Create User sales rep related dealer.
           */
          factory(App\Storage\User\User::class, 'sales_rep', 2)->create()->each(function ($u) use($salesRepRole, $customers, $dealer, $brand) {
              $salesRep = $u->salesRep()->save(factory(App\Storage\SalesRep\SalesRep::class)->make());
              $dealer->salesReps()->save($salesRep);
              $u->attachRole($salesRepRole);
              $isDefaultEmail = false;
              
              /**
               * Check default sales rep email.
               *
               * @var        string
               */
              foreach($this->defaultEmail() as $e)
              {
                  $defualtEmail = $e;
                  $c = App\Storage\User\User::where('email', $defualtEmail)->count();
                  
                  if($c == 0)
                  {
                      $u->email = $defualtEmail;
                      $u->save();
                      $isDefaultEmail = true;

                      break;
                  }
              }

              $custCount = ($isDefaultEmail ? 4 : 2);

              $this->createOffers($brand, $salesRep);

          });
        });
        /**
         * Create dealers without BA assign.
         */
        $this->createDealers(null, 2);

        /**
         * Assign offer to customers
         */
        foreach($customers as $customer)
        {
          $reqTypes = ['appt', 'price', 'info', 'add_offer'];
          $reqType = $reqTypes[array_rand($reqTypes)];
          $faker = \Faker\Factory::create();     
          
          $offer = Offer::orderByRaw("RAND()")->first();

          $salesRep = $this->customer->findNereastBAOfOffer($offer, $customer);

          if($reqType == 'add_offer'){
              $this->customer->setOfferToCustomer($offer->id, $customer);

              if($salesRep){
                $userIds = [$salesRep->user->id, $customer->user->id];
                shuffle($userIds);
                $this->thread->createMessage($userIds[0], $userIds[1], $faker->paragraphs(4, true), 'message', $faker->sentence());
              }
          }else{
            $appr = rand(0, 1);

          //  if($salesRep){
              $this->customer_request->sendRequest($customer, $offer, $reqType, $faker->paragraphs(4, true), '', $appr);
          //  }else{

              //Send email notification to CSR when there is no BA to dealer.
           //  $this->csr->sendUnmatchLeadNotify();
          //  }
          }               
        }
    }

    public function createDealers($after_dealer_added, $limit = 10)
    {
        //$locations = collect(SeederHelpers::cities('IL')->splice(0, 2)->toArray())->merge(SeederHelpers::cities('TX')->splice(0, 4)->toArray())->merge(SeederHelpers::cities('MA')->splice(0, 2)->toArray())->merge(SeederHelpers::cities('NY')->splice(0, 2)->toArray());

        $roleObj = new App\Storage\Role\Role();
        $salesRepRole = $roleObj->where('name', 'sales-rep')->first();

        /**
         * Create dealers
         */
        factory(App\Storage\Dealer\Dealer::class, $limit)->create()->each(function ($dealer) use($salesRepRole, $after_dealer_added){

   //       $location         = $locations->shuffle()->first();
   //       $dealer->zip      = $location->Zipcode;
    //      $dealer->city     = $location->City;
    //      $dealer->state    = $location->State;
    //      $dealer->country  = 'US';
    //      $dealer->geo_long = $location->Longitude;
    //      $dealer->geo_lat  = $location->Latitude;
    //      $dealer->save();

          /**
           * Create brands related to dealer.
           */
          $brand = Brand::orderByRaw("RAND()")->first();               
          $dealer->brands()->save($brand);

          if(is_callable($after_dealer_added))
          {
            call_user_func_array($after_dealer_added, [$dealer, $brand]);
          }
          
        });

    }

    public function createOffers($brand, $salesRep)
    { 

      
      /**
       * Create offer related to brand.
       */
      return factory(App\Storage\Offer\Offer::class, 10)->create()->each(function ($offer) use($brand, $salesRep){
          $brand->offers()->save($offer);
          $authorTypes = ['custom', 'brand', 'dealer'];

          if(!config('lbt.offer.enable_dealer_offers'))
          {
            unset($authorTypes['dealer']);
          }

          $authorType = $authorTypes[array_rand($authorTypes)];

          $offer->author_type = $authorType;
          if($authorType == 'custom'){
              $salesRep->offers()->save($offer);    
          }
          $offer->save();
          
          /**
           * Set offer media
           *
           * @var        array
           */
          $mediaIds = $this->medias[array_rand($this->medias)];
          $thumbId = $mediaIds[0];

          $this->offer->createOfferMedia($offer, $mediaIds, $thumbId);
      });
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function runX()
    {

        $this->call(InitialRoleTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(BrandSeeder::class);
//        $this->call(DummyCustomer::class);

        $roleObj = new App\Storage\Role\Role();
        $salesRepRole = $roleObj->where('name', 'sales-rep')->first();
        $customerRole = $roleObj->where('name', 'customer')->first();
//        $customers = Customer::get();

        /**
         * Create dealers
         */
        factory(App\Storage\Dealer\Dealer::class, 10)->create()->each(function ($dealer) use($salesRepRole, $customerRole, $customers){
          //  $brands = $dealer->brands()->save(factory(App\Storage\Brand\Brand::class)->make());
          //  factory(App\Storage\DealersCategory\DealersCategory::class, rand(1, 4))->create()->each(function($dealer_cat) use($dealer){
          //      $dealer->categories()->save($dealer_cat);
          //  });

            //$dealerCats = $dealer->categories()->save($cats);
            /**
             * Create brands related to dealer.
             */
           
           $brand = Brand::orderByRaw("RAND()")->first();

           // factory(App\Storage\Brand\Brand::class, 3)->create()->each(function ($brand) use($dealer, $salesRepRole, $customerRole) {
               
                $dealer->brands()->save($brand);

             //   $brand->categories()->save(Category::orderByRaw("RAND()")->first());

                /**
                 * Create User sales rep related dealer.
                 */
                factory(App\Storage\User\User::class, 'sales_rep', 2)->create()->each(function ($u) use($dealer, $brand, $salesRepRole, $customerRole, $customers) {
                    $salesRep = $u->salesRep()->save(factory(App\Storage\SalesRep\SalesRep::class)->make());
                    $dealer->salesReps()->save($salesRep);
                    $u->attachRole($salesRepRole);
                    $isDefaultEmail = false;
                    
                    /**
                     * Check default sales rep email.
                     *
                     * @var        string
                     */
                    foreach($this->defaultEmail() as $e)
                    {
                        $defualtEmail = $e;
                        $c = App\Storage\User\User::where('email', $defualtEmail)->count();
                        
                        if($c == 0)
                        {
                            $u->email = $defualtEmail;
                            $u->save();
                            $isDefaultEmail = true;

                            break;
                        }
                    }

                    $custCount = ($isDefaultEmail ? 4 : 2);

                    /**
                     * Create customer related to sales rep.
                     */
                    factory(App\Storage\User\User::class, 'customer', $custCount)->create()->each(function ($user) use($salesRep, $brand, $customerRole) {
                        $customer = $user->customer()->save(factory(App\Storage\Customer\Customer::class)->make());
                        $salesRep->customers()->save($customer);
                        $user->attachRole($customerRole);

                        /**
                         * Create offer related to brand.
                         */
                        factory(App\Storage\Offer\Offer::class, 10)->create()->each(function ($offer) use($customer, $brand, $salesRep){
                            $brand->offers()->save($offer);
                            $authorTypes = ['custom', 'brand', 'dealer'];
                            $authorType = $authorTypes[array_rand($authorTypes)];

                            $offer->author_type = $authorType;
                            if($authorType == 'custom'){
                                $salesRep->offers()->save($offer);    
                            }
                            $offer->save();
                            
                            /**
                             * Set offer media
                             *
                             * @var        array
                             */
                            $mediaIds = $this->medias[array_rand($this->medias)];
                            $thumbId = $mediaIds[0];

                            $this->offer->createOfferMedia($offer, $mediaIds, $thumbId);

                            $reqTypes = ['appt', 'price', 'info', 'add_offer'];
                            $reqType = $reqTypes[array_rand($reqTypes)];
                            $faker = \Faker\Factory::create();
                            if($reqType == 'add_offer'){
                                /**
                                 * Set customer related to offer.
                                 */
                                $this->customer->setOfferToCustomer($offer->id, $customer);
                                $userIds = [$salesRep->user->id, $customer->user->id];
                                shuffle($userIds);
                                $this->thread->createMessage($userIds[0], $userIds[1], $faker->paragraphs(4, true), 'message', $faker->sentence());
                            }else{
                                $appr = rand(0, 1);

                                $this->customer_request->sendRequest($customer->user->id, $salesRep->user->id, $offer->id, $reqType, $faker->paragraphs(4, true), '', $appr);
                            }
                        });
                    });

                });                
            //});
        });
    }

    public function createSetOfMedia()
    {
        $medias = [];
        for($i = 0; $i < 4; $i++)
        {
            $medias[] = $this->generateMedias();
        }

        return $medias;
    }

    public function generateMedias()
    {
        $mediaIds = [];
        $thumbId = 0;
        $images = array();
        for($i = 1; $i < 3; $i++)
        {
            $faker = \Faker\Factory::create();
            $imagePath = $faker->image(base_path('public/uploads'));

            if($imagePath && $imagePath != '')
            {
                $images[] = $imagePath;
            }else{
                shuffle($images);
                $imagePath = (isset($images[0]) ? $images[0] : base_path('public/uploads/dummy.jpg'));
               // dd($imagePath);
            }

            $file = new \Symfony\Component\HttpFoundation\File\File($imagePath);
            $media = $this->media->skipPresenter()->uploadImg($file->getPathname(), [[150, 100]], false);
            $mediaIds[] = $media->id;
        }
        
        return $mediaIds;      
    }
}