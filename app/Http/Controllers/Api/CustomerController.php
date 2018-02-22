<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Customer\CustomerRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\Offer\OfferRepository;
use App\Storage\Customer\Customer;
use App\Storage\User\User;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Requests\Api\CustomersShowRequest;
use App\Http\Requests\Api\SimpleGetRequest;
use App\Http\Requests\Api\CustomerSalesRepGetRequest;
use App\Http\Requests\Api\CustomerOfferPostRequest;
use App\Http\Requests\Api\CustomerOfferDeleteRequest;
use App\Http\Requests\Api\CustomerPostRequest;
use App\Http\Requests\Api\CustomerPostSocialRequest;
use App\Http\Requests\Api\CustomerPostLoginRequest;
use App\Http\Requests\Api\CustomerPostSocialLoginRequest;
use App\Http\Requests\Api\CustomerPutRequest;
use App\Http\Requests\Api\CustomerDeleteRequest;
use App\Http\Requests\Api\CustomerForgotPasswordRequest;
use App\Http\Requests\Api\CustomerNewPasswordRequest;
use App\Http\Requests\Api\CustomerChangePasswordRequest;
use Snowfire\Beautymail\Beautymail;
use Hash;
use Mail;
/**
 * @resource Customer
 *
 * Customer resource.
 */
class CustomerController extends Controller
{
	protected $customer;

    protected $salesrep;

	public function __construct(CustomerRepository $customer, SalesRepRepository $salesrep, OfferRepository $offer)
	{
        $this->customer = $customer;
        $this->salesrep = $salesrep;
        $this->offer    = $offer;
	}

	/**
	 * All
	 *
	 * Get a list of customers.
	 *
	 * @param      \App\Http\Requests\Api\CustomersRequest  $request  The request
	 *
	 * @return     Response
	 */
    public function index(CustomersRequest $request)
    {
    	//$customers = $this->customer->paginate(20);

        $order = $request->get('sort', 'asc');
        $limit = $request->get('limit', 20);
        $rows  = $this->customer->scopeToUser();
        $rows  = ($rows ? $rows->orderBy('users.lastname', $order) : null);

        /**
         * Search by lastname or firstname
         */
        if($request->has('s') && $request->get('s') != '') {
            $rows = $rows->whereName($request->get('s'));
        }

        /**
         * Get list of prospects
         */
        $rows = ($rows ? $rows->paginate($limit) : null);

		return response()->json($rows);
    }

    /**
     * Single
     *
     * Get a customer by ID.
		 *
     * Return 404 if offer doesn't exist.
     *
     * @param      \App\Http\Requests\Api\CustomersShowRequest  $request    The request
     * @param      Integer  $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function show(CustomersShowRequest $request, $customer_id)
    {
    	$customer = $this->customer->find($customer_id);

		return response()
			->json($customer);
    }

    /**
     * Brand Associates
     *
     * Get a list of brand associates related to a customer.
     *
     * @param      \App\Http\Requests\Api\CustomerSalesRepGetRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function salesReps(CustomerSalesRepGetRequest $request, $customer_id)
    {
        $filter       = $request->get('filter_by');
        $showApproved = true;
        $showRejected = true;

        if($filter == 'approved')
        {
            $showApproved = true;
            $showRejected = false;
        }elseif($filter == 'rejected')
        {
            $showApproved = false;
            $showRejected = true;
        }

        $customers = $this->salesrep->getByCustomer($customer_id, $showApproved, $showRejected)->paginate(20);

        return response()
            ->json($customers);
    }

    /**
     * Offers (lookbook)
     *
     * Get a list of offers related to a customer.
     *
     * @param      \App\Http\Requests\Api\SimpleGetRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function offers(SimpleGetRequest $request, $customer_id)
    {
        $offers = $this->offer->getByCustomer($customer_id)->paginate();

        return response()
            ->json($offers);
    }

    /**
     * Add Offer
     *
     * Add an offer related to a customer.
     *
     * @param      \App\Http\Requests\Api\CustomerOfferPostRequest  $request  The request
     *
     * @return     Response
     */
    public function addOffer(CustomerOfferPostRequest $request)
    {
        $customerId = $request->get('customer_id');
        $offerId    = $request->get('offer_id');
        $customer   = $this->customer->skipPresenter()->find($customerId);

        $this->customer->setOfferToCustomer($offerId, $customer);

       // $offer->customers()->save($customer);

        return response()
            ->json(array());
    }

    /**
     * Delete Offer
     *
     * Delete an offer related to customer.
     * The @parameter $_method is required and value must set to <strong>DELETE</strong>.
     *
     * @param      \App\Http\Requests\Api\CustomerOfferDeleteRequest  $request  The request
     *
     * @return     Integer Number of deleted rows.
     */
    public function deleteOffer(CustomerOfferDeleteRequest $request)
    {
        $customerId = $request->get('customer_id');
        $offerId    = $request->get('offer_id');
        $offer      = $this->customer->skipPresenter()
            ->find($customerId)
            ->pivotOffers()->where('offer_id', $offerId)->first(); //->delete();
        $offer->added = false;
        $offer->save();

        return response()
            ->json($offer);
    }

    /**
     * Create
     *
     * Create a new customer.
     *
     * @param      \App\Http\Requests\Api\CustomerPostRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function store(CustomerPostRequest $request)
    {
        try {
            $data             = $request->all();
            // $data['password'] = (isset($data['password']) ? $data['password'] : uniqid();
            $data['geo_long'] = (isset($data['geo_long']) ? $data['geo_long'] : '');
            $data['geo_lat']  = (isset($data['geo_lat']) ? $data['geo_lat'] : '');
            $data['country']  = 'US';
            $data['provider']  = (isset($data['provider']) ? $data['provider'] : '');
            $data['provider_token']  = (isset($data['provider_token']) ? $data['provider_token'] : '');

            $customer         = $this->customer->createOne($data);

            // return response() ->json($this->customer->find($customer->id));
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>$this->customer->find($customer->id),
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
    /**
     * Create
     *
     * Create a new customer using Social Registration.
     *
     * @param      \App\Http\Requests\Api\CustomerPostSocialRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function storeSocialRegistration(CustomerPostSocialRequest $request)
    {
        try {
            $data                   = $request->all();
            $data['password']       = uniqid();
            $data['geo_long']       = (isset($data['geo_long']) ? $data['geo_long'] : '');
            $data['geo_lat']        = (isset($data['geo_lat']) ? $data['geo_lat'] : '');
            $data['country']        = 'US';
            $data['provider']       = (isset($data['provider']) ? $data['provider'] : '');
            $data['provider_token'] = (isset($data['provider_token']) ? $data['provider_token'] : '');

            $customer               = $this->customer->createOne($data);

            // return response() ->json($this->customer->find($customer->id));
            return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>$this->customer->find($customer->id),
                    'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Update
     *
     * Update an existing customer.
     *
     * @param      \App\Http\Requests\Api\CustomerPutRequest  $request  The request
     *
     * @return     Response
     */
    public function update(CustomerPutRequest $request)
    {
        $custData = $request->except(['id', 'email', 'lastname', 'firstname']);
        $data     = $request->all();
        $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));

        $this->customer->updateOne($customer, $data);

        return response()
            ->json($this->customer->skipPresenter(false)->find($customer->id));
    }

    /**
     * Delete
     *
     *  Delete an existing customer.
     *
     * @param      \App\Http\Requests\Api\CustomerDeleteRequest  $request  The request
     *
     * @return     Response
     */
    public function destroy(CustomerDeleteRequest $request)
    {
        $customer = Customer::find($request->get('customer_id'));

        if(!$customer)
        {
            return response()->json(['data' => 0]);
        }

        $userId = $customer->user->id;
        $customer->offers()->detach();

        foreach($customer->pivotOffers()->get() as $pivot)
        {
            $pivot->delete();
        }

        foreach ($customer->salesRepsPivot()->get() as $pivot) {
            $pivot->delete();
        }

        $customer->salesReps()->detach();

        foreach($customer->activities()->get() as $row)
        {
            $row->delete();
        }

        $customer->delete();
        User::find($userId)->delete();

        return response()->json(['data' => 1]);
    }

    /**
     *
     * Customer login.
     *
     * @param      \App\Http\Requests\Api\CustomerPostLoginRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function doCustomerLogin(CustomerPostLoginRequest $request)
    {
        try {
            $email             = $request->email;
            $password          = $request->password;
            $user= User::where('email','=',$email)->first();
            if(!empty($user)){

                if(Hash::check($password,$user->password))
                    {
            // dd($user);
                        return response()->json([
                        'status'=>true,
                        'code'=>config('responses.success.status_code'),
                        'data'=>$user->customer,
                        'message'=>config('responses.success.status_message'),
                        ], config('responses.success.status_code'));
                    }

            }
                return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>[],
                    'message'=>'Invalid email or password',
                ], config('responses.bad_request.status_code'));

            // $customer

            // return response() ->json($this->customer->find($customer->id));

            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

     /**
     *
     * Customer Social login.
     *
     * @param      \App\Http\Requests\Api\CustomerPostSocialLoginRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function doCustomerSocialLogin(CustomerPostSocialLoginRequest $request)
    {
        try {
            $provider             = $request->provider;
            $provider_token       = $request->provider_token;
            $user= User::where('provider','=',$provider)->where('provider_token','=',$provider_token)->first();
            if(!empty($user)){

                return response()->json([
                'status'=>true,
                'code'=>config('responses.success.status_code'),
                'data'=>$user->customer,
                'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }

            return response()->json([
               'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>[],
                'message'=>'Invalid '.$provider .' Token' ,
            ], config('responses.bad_request.status_code'));

            // $customer

            // return response() ->json($this->customer->find($customer->id));

            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     * Forgot Password.
     *
     * @param      \App\Http\Requests\Api\CustomerPostSocialLoginRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function forgotPassword(CustomerForgotPasswordRequest $request)
    {
        try {
            $email             = $request->email;
            $user= User::where('email','=',$email)->first();
            // $customer=$user->customer;
            if(!empty($user)){
            $token=   app('auth.password.broker')->createToken($user);
              $user->token=$token;

                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.auth.api.password-reset', compact('user'), function($message) use($user)
                {
                     // $token = str_random(64);
                    $message
                        ->from(env('NO_REPLY'))
                        // ->from(env('MAIL_USERNAME'))
                        ->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject('Password Reset Link');
                });
                // \Mail::send('emails.auth.api.password-reset', compact('user'), function($message) use($user)
                // {
                //      // $token = str_random(64);

                //     $message
                //         ->from(env('NO_REPLY'))
                //         ->to($user->email, $user->firstname . ' ' . $user->lastname)
                //         ->subject('Password Reset Link');
                // });

                return response()->json([
                'status'=>true,
                'code'=>config('responses.success.status_code'),
                'data'=>['We have e-mailed your password reset link!'],
                'message'=>config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }

            return response()->json([
               'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>[],
                'message'=>'Invalid Email ' ,
            ], config('responses.bad_request.status_code'));

            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }



     /**
     *
     * New Password.
     *
     * @param      \App\Http\Requests\Api\CustomerNewPasswordRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function newPassword(CustomerNewPasswordRequest $request)
    {
        try {
            $email             = $request->email;
            $token             = $request->token;
            $password             = $request->password;
            $user= User::where('email','=',$email)->first();
            // $customer=$user->customer;
            if(!empty($user)){
            // $token=   app('auth.password.broker')->reset();
            // $credentials = $request->only(
               // 'email', 'password', 'token','password_confirmation'
            // );
            //
            $credentials=['email'=>$email, 'password' =>$password , 'token' => $token ,'password_confirmation'=> $password];

               $response =app('auth.password.broker')->reset($credentials, function ($user, $password) {
                   $this->resetPassword($user, $password);
                   return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>['Your password was succesfully changed'],
                    'message'=>config('responses.success.status_message'),
                    ], config('responses.success.status_code'));

                   });
            }

            return response()->json([
               'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>[],
                'message'=>'Invalid Email or Token' ,
            ], config('responses.bad_request.status_code'));

            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
    * Reset the given user's password.
    *
    * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
    * @param  string  $password
    * @return void
    */
   protected function resetPassword($user, $password)
   {
       $user->password = bcrypt($password);

       $user->save();
        return true;
       // Auth::login($user);
   }


   /**
     *
     * Change Password.
     *
     * @param      \App\Http\Requests\Api\CustomerNewPasswordRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function changePassword(CustomerChangePasswordRequest $request)
    {
        try {
            $email          = $request->email;
            $password       = $request->password;
            // $newHashPass    = Hash::make($request->password);
            $user= User::where('email','=',$email)->first();
            // $customer=$user->customer;
            if(!empty($user)){
                $this->resetPassword($user, $password);
                return response()->json([
                    'status'=>true,
                    'code'=>config('responses.success.status_code'),
                    'data'=>['Your password was succesfully changed'],
                    'message'=>config('responses.success.status_message'),
                    ], config('responses.success.status_code'));
            }
            return response()->json([
               'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>[],
                'message'=>'Invalid Email' ,
            ], config('responses.bad_request.status_code'));

            }
        catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'=>false,
                'code'=>config('responses.bad_request.status_code'),
                'data'=>null,
                'message'=>$e->getMessage()
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
}
