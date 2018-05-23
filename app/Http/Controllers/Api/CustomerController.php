<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Api\CustomerAssignedBaOfferRequest;
use App\Http\Requests\Api\CustomerAvatarsRequest;
use App\Http\Requests\Api\CustomerBaBrandOfferRequest;
use App\Http\Requests\Api\CustomerBaOfferRequest;
use App\Http\Requests\Api\CustomerChangeAvatarRequest;
use App\Http\Requests\Api\CustomerChangePasswordRequest;
use App\Http\Requests\Api\CustomerDeleteRequest;
use App\Http\Requests\Api\CustomerForgotPasswordRequest;
use App\Http\Requests\Api\CustomerMyDealerRequest;
use App\Http\Requests\Api\CustomerMyOfferRequest;
use App\Http\Requests\Api\CustomerNearestDealerRequest;
use App\Http\Requests\Api\CustomerNewPasswordRequest;
use App\Http\Requests\Api\CustomerOfferDeleteRequest;
use App\Http\Requests\Api\CustomerOfferPostRequest;
use App\Http\Requests\Api\CustomerPostLoginRequest;
use App\Http\Requests\Api\CustomerPostLogoutRequest;
use App\Http\Requests\Api\CustomerPostRequest;
use App\Http\Requests\Api\CustomerPostShareOfferRequest;
use App\Http\Requests\Api\CustomerPostSocialLoginRequest;
use App\Http\Requests\Api\CustomerPostSocialRequest;
use App\Http\Requests\Api\CustomerPutRequest;
use App\Http\Requests\Api\CustomerSalesRepGetRequest;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Requests\Api\CustomersShowRequest;
use App\Http\Requests\Api\CustomerUploadAvatarRequest;
use App\Http\Requests\Api\CustomerViewSalesRepRequest;
use App\Http\Requests\Api\SimpleGetRequest;
use App\Storage\Customer\Customer;
use App\Storage\Customer\CustomerRepository;
use App\Storage\Customer\CustomerSalesRep;
use App\Storage\Dealer\Dealer;
use App\Storage\Dealer\DealerRepository;
use App\Storage\LbtWp\WpConvetor;
use App\Storage\Media\MediaRepository;
use App\Storage\Offer\OfferRepository;
use App\Storage\SalesRep\SalesRep;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\User\User;
use App\Storage\ZipCodeApi\ZipCodeApi;
use DB;
use Hash;
use Illuminate\Http\Request;
use Snowfire\Beautymail\Beautymail;

/**
 * @resource Customer
 *
 * Customer resource.
 */
class CustomerController extends Controller {
    protected $customer;

    protected $salesrep;
    protected $dealer;
    protected $media;

    public function __construct(CustomerRepository $customer, SalesRepRepository $salesrep, OfferRepository $offer, MediaRepository $media, DealerRepository $dealer) {
        $this->customer = $customer;
        $this->salesrep = $salesrep;
        $this->offer    = $offer;
        $this->media    = $media;
        $this->dealer   = $dealer;
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
    public function index(CustomersRequest $request) {
        //$customers = $this->customer->paginate(20);

        $order = $request->get('sort', 'asc');
        $limit = $request->get('limit', 20);
        $rows  = $this->customer->scopeToUser();
        $rows  = ($rows ? $rows->orderBy('users.lastname', $order) : null);

        /**
         * Search by lastname or firstname
         */
        if ($request->has('s') && $request->get('s') != '') {
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
    public function show(CustomersShowRequest $request, $customer_id) {
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
    public function salesReps(CustomerSalesRepGetRequest $request, $customer_id) {
        $filter       = $request->get('filter_by');
        $showApproved = true;
        $showRejected = true;

        if ($filter == 'approved') {
            $showApproved = true;
            $showRejected = false;
        } elseif ($filter == 'rejected') {
            $showApproved = false;
            $showRejected = true;
        }

        $customers = $this->salesrep->getByCustomer($customer_id, $showApproved, $showRejected)->paginate(20);

        return response()
            ->json($customers);
    }
    /**
     * Brand Associates
     *
     * Get a list of brand associates related to a customer.
     *
     * @param      \App\Http\Requests\Api\CustomerViewSalesRepRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     *
     * @return     Response
     */
    public function viewSalesReps(CustomerViewSalesRepRequest $request) {

        try {
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            // $customer_id = $request->get('customer_id');
            // $salesreps = $this->customer->getByCustomer($customer_id)->paginate();
            $per_page = $request->get('per_page') != '' ? $request->get('per_page') : 20;

            $customer = Customer::find($customer_id)->salesReps()->paginate($per_page)->toArray();

            //
            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $data = array_merge($data, $customer);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
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
    public function offers(SimpleGetRequest $request) {

        try {

            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $offers         = $this->offer->getByCustomer($customer_id)->paginate();

            $data = ['status' => true,
                'code'            => config('responses.success.status_code'),
                'message'         => config('responses.success.status_message')];
            $data = array_merge($data, $offers);
            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
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
    public function addOffer(CustomerOfferPostRequest $request) {
        try {

            $wp_offer_id    = $request->get('wp_offer_id');
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customerId     = $wp->getId('customer', $wp_customer_id);
            $offerId        = $wp->getId('offer', $wp_offer_id);

            $customer = $this->customer->skipPresenter()->find($customerId);

            $this->customer->setOfferToCustomer($offerId, $customer);

            $offer = $this->offer->skipPresenter()->find($offerId);
            // $ba    = $this->customer->findNereastBAOfOffer($offer, $customer);

            // $user = $ba->user;

            // $beautymail = app()->make(Beautymail::class);
            //        $beautymail->send('emails.api.ba-addoffer', compact('user','offer','customer'), function($message) use($user)
            //        {
            //             // $token = str_random(64);
            //            $message
            //                ->from(env('NO_REPLY'))
            //                // ->from(env('MAIL_USERNAME'))
            //                ->to($user->email, $user->firstname . ' ' . $user->lastname)
            //                ->subject('New Offer added to Lookbook');
            //        });

            return response()->json([
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'data'    => 'Offer added succesfully',
                'message' => config('responses.success.status_message'),
            ], config('responses.success.status_code'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }

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
    public function deleteOffer(CustomerOfferDeleteRequest $request) {
        try {

            $wp_offer_id    = $request->get('wp_offer_id');
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $offer_id       = $wp->getId('offer', $wp_offer_id);
            $customer       = $this->customer->skipPresenter()->find($customer_id);
            $offer          = $this->customer->skipPresenter()
                ->find($customer_id)
                ->pivotOffers()->where('offer_id', $offer_id)->delete(); //->first(); //
            // $offer->added = false;
            // $offer->save();

            $action = new \App\Storage\UserAction\UserAction();

            $action->removeCustomerOffer($customer->user->id, $offer_id);

            return response()->json([
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'data'    => 'Offer removed succesfully',
                'message' => config('responses.success.status_message'),
            ], config('responses.success.status_code'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
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
    public function store(CustomerPostRequest $request) {
        try {
            $data = $request->all();
            // $data['password'] = (isset($data['password']) ? $data['password'] : uniqid();
            $data['geo_long']       = (isset($data['geo_long']) ? $data['geo_long'] : '');
            $data['geo_lat']        = (isset($data['geo_lat']) ? $data['geo_lat'] : '');
            $data['country']        = 'US';
            $data['provider']       = (isset($data['provider']) ? $data['provider'] : '');
            $data['provider_token'] = (isset($data['provider_token']) ? $data['provider_token'] : '');

            $data['address1']    = (isset($data['address1']) ? $data['address1'] : '');
            $data['city']        = (isset($data['city']) ? $data['city'] : '');
            $data['state']       = (isset($data['state']) ? $data['state'] : '');
            $data['address2']    = (isset($data['address2']) ? $data['address2'] : '');
            $data['homephone']   = (isset($data['homephone']) ? $data['homephone'] : '');
            $data['cellphone']   = (isset($data['cellphone']) ? $data['cellphone'] : '');
            $data['officephone'] = (isset($data['officephone']) ? $data['officephone'] : '');
            $data['avatar_url']  = (isset($data['avatar_url']) ? $data['avatar_url'] : '');

            // $zip = new ZipCodeApi();
            // $geo = $zip->getGeoByzip($data['zip']);

            // if ($geo->getFoundLat() == null || $geo->getFoundLong() == null) {

            //     return response()->json([
            //         'status'  => false,
            //         'code'    => 400,
            //         'data'    => null,
            //         'errors'  => ['zip' => ['The Zip code is invalid']],
            //         'message' => "Bad request",
            //     ],
            //         config('responses.bad_request.status_code')
            //     );

            // } else {

            //     $data['geo_lat']  = $geo->getFoundLat();
            //     $data['geo_long'] = $geo->getFoundLong();
            // }

            $customer = $this->customer->createOne($data, true);

            // return response() ->json($this->customer->find($customer->id));
            $response = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $response = array_merge($response, $this->customer->find($customer->id));
            return response()->json($response, config('responses.success.status_code'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function storeSocialRegistration(CustomerPostSocialRequest $request) {
        try {
            $data                   = $request->all();
            $data['password']       = uniqid();
            $data['geo_long']       = (isset($data['geo_long']) ? $data['geo_long'] : '');
            $data['geo_lat']        = (isset($data['geo_lat']) ? $data['geo_lat'] : '');
            $data['country']        = 'US';
            $data['provider']       = (isset($data['provider']) ? $data['provider'] : '');
            $data['provider_token'] = (isset($data['provider_token']) ? $data['provider_token'] : '');

            $data['address1']    = (isset($data['address1']) ? $data['address1'] : '');
            $data['city']        = (isset($data['city']) ? $data['city'] : '');
            $data['state']       = (isset($data['state']) ? $data['state'] : '');
            $data['address2']    = (isset($data['address2']) ? $data['address2'] : '');
            $data['homephone']   = (isset($data['homephone']) ? $data['homephone'] : '');
            $data['cellphone']   = (isset($data['cellphone']) ? $data['cellphone'] : '');
            $data['officephone'] = (isset($data['officephone']) ? $data['officephone'] : '');
            $data['avatar_url']  = (isset($data['avatar_url']) ? $data['avatar_url'] : '');

            // $zip = new ZipCodeApi();
            // $geo = $zip->getGeoByzip($data['zip']);

            // if ($geo->getFoundLat() == null || $geo->getFoundLong() == null) {

            //     return response()->json([
            //         'status'  => false,
            //         'code'    => 400,
            //         'data'    => null,
            //         'errors'  => ['zip' => ['The Zip code is invalid']],
            //         'message' => "Bad request",
            //     ],
            //         config('responses.bad_request.status_code')
            //     );

            // } else {

            //     $data['geo_lat']  = $geo->getFoundLat();
            //     $data['geo_long'] = $geo->getFoundLong();
            // }

            $customer = $this->customer->createOne($data, true);

            // return response() ->json($this->customer->find($customer->id));
            $response = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $response = array_merge($response, $this->customer->find($customer->id));
            return response()->json($response, config('responses.success.status_code'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function update(CustomerPutRequest $request) {
        try
        {
            // $custData = $request->except(['id', 'email', 'lastname', 'firstname']);
            $data           = $request->all();
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            // if (isset($data['zip'])) {
            //     $zip = new ZipCodeApi();
            //     $geo = $zip->getGeoByzip($data['zip']);

            //     if ($geo->getFoundLat() == null || $geo->getFoundLong() == null) {

            //         return response()->json([
            //             'status'  => false,
            //             'code'    => 400,
            //             'data'    => null,
            //             'errors'  => ['zip' => ['The Zip code is invalid']],
            //             'message' => "Bad request",
            //         ],
            //             config('responses.bad_request.status_code')
            //         );

            //     } else {

            //         $data['geo_lat']  = $geo->getFoundLat();
            //         $data['geo_long'] = $geo->getFoundLong();
            //     }
            // }

            $customer = $this->customer->skipPresenter()->find($customer_id);
            $this->customer->updateOne($customer, $data);

            return response()->json([
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'data'    => $customer,
                'message' => config('responses.success.status_message'),
            ], config('responses.success.status_code'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
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
    public function destroy(CustomerDeleteRequest $request) {
        $customer = Customer::find($request->get('customer_id'));

        if (!$customer) {
            return response()->json(['data' => 0]);
        }

        $userId = $customer->user->id;
        $customer->offers()->detach();

        foreach ($customer->pivotOffers()->get() as $pivot) {
            $pivot->delete();
        }

        foreach ($customer->salesRepsPivot()->get() as $pivot) {
            $pivot->delete();
        }

        $customer->salesReps()->detach();

        foreach ($customer->activities()->get() as $row) {
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
    public function doCustomerLogin(CustomerPostLoginRequest $request) {
        try {
            $email    = $request->email;
            $password = $request->password;
            $user     = User::where('email', '=', $email)->first();
            // dd($user->customer);
            if (!empty($user)) {

                if (Hash::check($password, $user->password)) {

                    $data = [
                        'customer_id'    => $user->customer->id,
                        'user_id'        => $user->customer->user_id,
                        'wp_customer_id' => $user->customer->wp_userid,
                    ];
                    return response()->json([
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'data'    => $data,
                        'message' => config('responses.success.status_message'),
                    ], config('responses.success.status_code'));
                }

            }
            return response()->json([
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'data'    => [],
                'message' => 'Invalid email or password',
            ], config('responses.bad_request.status_code'));

            // $customer

            // return response() ->json($this->customer->find($customer->id));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function doCustomerSocialLogin(CustomerPostSocialLoginRequest $request) {
        try {
            $provider       = $request->provider;
            $provider_token = $request->provider_token;
            $user           = User::where('provider', '=', $provider)->where('provider_token', '=', $provider_token)->first();
            if (!empty($user)) {
                $data = [
                    'customer_id' => $user->customer->id,
                    'user_id'     => $user->customer->user_id,
                    // 'wp_userid'=>$user->customer->wp_userid
                ];
                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => $data,
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid ' . $provider . ' Token',
            ], config('responses.bad_request.status_code'));

            // $customer

            // return response() ->json($this->customer->find($customer->id));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function forgotPassword(CustomerForgotPasswordRequest $request) {
        try {
            $email = $request->email;
            $user  = User::where('email', '=', $email)->first();
            // $customer=$user->customer;
            if (!empty($user)) {
                $token       = app('auth.password.broker')->createToken($user);
                $user->token = $token;

                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.auth.api.password-reset', compact('user'), function ($message) use ($user) {
                    // $token = str_random(64);
                    $message
                        ->from(env('NO_REPLY'))
                        // ->from(env('MAIL_USERNAME'))
                        ->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject('Password Reset Link');
                });

                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => ['token' => $token],
                    'message' => 'We have e-mailed your password reset link!',
                ], config('responses.success.status_code'));
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Email ',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    public function newPassword(CustomerNewPasswordRequest $request) {
        try {
            $email    = $request->email;
            $token    = $request->token;
            $password = $request->password;
            $user     = User::where('email', '=', $email)->first();

            if (!empty($user)) {

                $credentials = ['email' => $email, 'password' => $password, 'token' => $token, 'password_confirmation' => $password];

                $response = app('auth.password.broker')->reset($credentials, function ($user, $password) {

                    // if(!empty($issettoken)){
                    $this->resetPassword($user, $password);

                    // }
                });

                if ($response == 'passwords.reset') {
                    return response()->json([
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'data'    => ['Your password was succesfully changed'],
                        'message' => config('responses.success.status_message'),
                    ], config('responses.success.status_code'));
                }
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Email or Token',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
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
    protected function resetPassword($user, $password) {
        $user->password = bcrypt($password);

        $user->save();

        // return true;
        // Auth::login($user);
    }

    /**
     *
     * Change Password.
     *
     * @param      \App\Http\Requests\Api\CustomerChangePasswordRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function changePassword(CustomerChangePasswordRequest $request) {
        try {
            $email    = $request->email;
            $password = $request->password;
            // $newHashPass    = Hash::make($request->password);
            $user = User::where('email', '=', $email)->first();
            // $customer=$user->customer;
            if (!empty($user)) {
                $this->resetPassword($user, $password);
                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => ['Your password was succesfully changed'],
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Email',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
    /**
     *
     * Upload Avatar
     *
     * @param      \App\Http\Requests\Api\CustomerUploadAvatarRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function uploadAvatar(CustomerUploadAvatarRequest $request) {
        try {
            $data           = $request->all();
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $customer       = $this->customer->skipPresenter()->find($customer_id);

            $user = $customer->user;

            if (!empty($user)) {

                $file = $data['avatar'];

                $type     = explode('/', $file->getClientMimeType());
                $ext      = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $baseName = basename($file->getClientOriginalName(), '.' . $ext);
                $fileName = $this->media->setUploadPath()->generateFilename($baseName, $ext);

                try {
                    $targetFile = $file->move($this->media->getUploadPath(), $fileName);
                } catch (\Exception $e) {

                    $erroMsg = $this->media->errorMessage($file->getClientOriginalName());
                    $error   = [
                        'title' => $erroMsg[0],
                        'body'  => $erroMsg[1],
                    ];
                    return response()->json([
                        'status'  => false,
                        'code'    => config('responses.bad_request.status_code'),
                        'data'    => $error,
                        'message' => $erroMsg,
                    ], config('responses.bad_request.status_code'));

                }

                if ($type[0] == 'image') {
                    $media = $this->media->skipPresenter()->uploadImg($targetFile->getPathname(), [[150, 100]], false);

                    $media_id = $media->id;
                    //set image as Avatar
                    $user->setMeta('avatar_media_id', $media_id);
                    $user->save();
                    $customer->setMedia($media->id);

                }

                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => ['Your Avatar was succesfully upload'],
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Customer',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     * Avatar list of customer
     *
     * @param      \App\Http\Requests\Api\CustomerAvatarsRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function avatars(CustomerAvatarsRequest $request) {
        try {

            $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
            $user     = $customer->user;

            if (!empty($user)) {

                $medias   = $customer->medias;
                $mediaIds = [];
                foreach ($medias as $key => $media) {
                    $mediaIds[] = $media->id;
                }

                $avatars = $this->media->skipPresenter()->findWhereIn('id', $mediaIds);
                $current = $user->avatar();
                $data    = [
                    'profile_avatar' => $current,
                    'avatars'        => $avatars,
                ];
                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => $data,
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Customer',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     * change avatar of customer
     *
     * @param      \App\Http\Requests\Api\CustomerChangeAvatarRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function changeAvatar(CustomerChangeAvatarRequest $request) {
        try {
            $data = $request->all();

            $wp_customer_id = $request->get('wp_customer_id');
            $avatar_url     = $request->get('avatar_url');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            $customer = $this->customer->skipPresenter()->find($customer_id);
            // $user= $customer->user;
            $customer->avatar_url = $avatar_url;
            if ($customer->save()) {
                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => $customer->avatar_url,
                    'message' => 'Your Avatar was succesfully updated',
                ], config('responses.success.status_code'));
            }
            // if(!empty($user)){

            //    $user->setMeta('avatar_media_id', $request->get('avatar_id'));
            //    $user->save();
            //    return response()->json([
            //         'status'=>true,
            //         'code'=>config('responses.success.status_code'),
            //         'data'=>$user->avatar(),
            //         'message'=>'Your Avatar was succesfully updated',
            //         ], config('responses.success.status_code'));
            // }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Customer',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     *  customer logout
     *
     * @param      \App\Http\Requests\Api\CustomerPostLogoutRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function doCustomerLogout(CustomerPostLogoutRequest $request) {
        try {

            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $customer       = $this->customer->skipPresenter()->find($customer_id);
            $user           = $customer->user;

            if (!empty($user)) {

                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => 'Logout succesfully.',
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Customer',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     *  customer share offer
     *
     * @param      \App\Http\Requests\Api\CustomerPostShareOfferRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function shareOffer(CustomerPostShareOfferRequest $request) {
        try {
            $wp = new WpConvetor();

            $wp_customer_id = $request->get('wp_customer_id');
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $customer       = $this->customer->skipPresenter()->find($customer_id);

            $wp_offer_id = $request->get('wp_offer_id');
            $offer_id    = $wp->getId('offer', $wp_offer_id);

            // $user= $customer->user;
            $offer = $this->offer->skipPresenter()->find($offer_id);

            $email = $request->email;

            if (!empty($customer)) {
                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.api.prospect-shareoffer', compact('offer', 'customer'), function ($message) use ($email) {

                    $message
                        ->from(env('NO_REPLY'))
                        // ->from(env('MAIL_USERNAME'))
                        ->to($email)
                        ->subject('Offer Shared');
                });
                return response()->json([
                    'status'  => true,
                    'code'    => config('responses.success.status_code'),
                    'data'    => 'Offer shared succesfully',
                    'message' => config('responses.success.status_message'),
                ], config('responses.success.status_code'));
            }
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => 'Invalid Customer',
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     *  customer offer
     *
     * @param      \App\Http\Requests\Api\CustomerMyOfferRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function myOffers(CustomerMyOfferRequest $request) {
        try {

            $wp = new WpConvetor();

            $wp_customer_id = $request->get('wp_customer_id');
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            $customer = $this->customer->skipPresenter()->find($customer_id);
            // $user= $customer->user;
            // $offer = $this->offer->skipPresenter()->find($request->offer_id);
            $per_page     = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $offers       = [];
            $msg          = '';
            $customer_zip = $customer->zip;
            if ($customer_zip != '') {

                //1 get all zip codes using zip api
                $zip       = new ZipCodeApi();
                $zip_codes = $zip->getNearest($customer->zip, 1000);
                if ($zip_codes->getFoundZips() != null) {
                    $delears_id = Dealer::whereIn('zip', $zip_codes->getFoundZips())->pluck('id');
                    //3 get offers
                    $offer = $this->offer->myOffers($delears_id);
                    if ($request->get('keyword') != '') {
                        $keyword = $request->get('keyword');
                        $offer->whereOffers(escape_like($keyword));
                    }

                    $offers = $offer->paginate($per_page);

                    $data = [
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'message' => config('responses.success.status_message'),
                    ];
                    $data = array_merge($data, $offers);

                    return response()->json($data, config('responses.success.status_code'));

                } else {
                    $msg = 'Unable to find nearest offer';
                }
            } else {
                $msg = 'Customer zip code is invalid.';
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => $msg,
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     *  customer   Dealers
     *
     * @param      \App\Http\Requests\Api\CustomerMyDealerRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function myDealers(CustomerMyDealerRequest $request) {
        try {

            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);
            $customer       = $this->customer->skipPresenter()->find($customer_id);
            // $user= $customer->user;
            // $offer = $this->offer->skipPresenter()->find($request->offer_id);
            $per_page     = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $delears      = [];
            $msg          = '';
            $customer_zip = $customer->zip;
            if ($customer_zip != '') {

                //1 get all zip codes using zip api
                $zip       = new ZipCodeApi();
                $zip_codes = $zip->getNearest($customer->zip, 1000);
                if ($zip_codes->getFoundZips() != null) {

                    $delear  = $this->dealer->whereInZips($zip_codes->getFoundZips());
                    $delears = $delear->paginate($per_page);
                    $data    = [
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'message' => config('responses.success.status_message'),
                    ];
                    $data = array_merge($data, $delears);

                    return response()->json($data, config('responses.success.status_code'));

                } else {
                    $msg = 'Unable to find nearest Dealers';
                }
            } else {
                $msg = 'Customer zip code is invalid.';
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => $msg,
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
    /**
     *
     *  nearest Dealers
     *
     * @param      \App\Http\Requests\Api\CustomerNearestDealerRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function nearestDealers(CustomerNearestDealerRequest $request) {
        try {

            $zip      = new ZipCodeApi();
            $zip_code = $request->get('zip');

            $wp_brand_id = $request->get('wp_brand_id');
            $wp          = new WpConvetor();
            $brand_id    = $wp->getId('brand', $wp_brand_id);

            $ip       = $request->get('ip');
            $geo_lat  = $request->get('geo_lat');
            $geo_long = $request->get('geo_long');
            $per_page = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $delears  = [];
            $msg      = '';
            if ($zip_code == '') {
                if ($ip != '') {
                    //get zip form Ip
                    $zip_ip = $zip->getZipByIP($ip);
                    if ($zip_ip->getZipCode() != null) {
                        $zip_code = $zip_ip->getZipCode();
                    } else {
                        $msg = 'IP Address is invalid.';
                    }
                }
            }
            if ($zip_code == '') {
                if ($geo_lat != '' && $geo_long != '') {
                    //get zip form geo
                    $zip_geo = $zip->getZipByGeo($geo_lat, $geo_long);
                    if ($zip_geo->getZipCode() != null) {
                        $zip_code = $zip_geo->getZipCode();
                    } else {
                        // $msg=$zip_geo->getError();
                        $msg = 'Invalid latitude or longitude parameter';
                    }

                }
            }

            if ($zip_code != '') {

                //1 get all Nearest zip codes using zip api
                $zip_codes = $zip->getNearest($zip_code, 200);
                if ($zip_codes->getFoundZips() != null) {

                    $delear  = $this->dealer->whereBrandInZips($zip_codes->getFoundZips(), $brand_id);
                    $delears = $delear->paginate($per_page);
                    // dd($delears);
                    $data = [
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'message' => config('responses.success.status_message'),
                    ];
                    $data = array_merge($data, $delears);

                    return response()->json($data, config('responses.success.status_code'));

                } else {
                    $msg = 'Unable to find nearest Dealers';
                }
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => $msg,
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     *
     *  customer   nea
     *
     * @param      \App\Http\Requests\Api\CustomerBaOfferRequest  $request  The request
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function baOffers(CustomerBaOfferRequest $request) {
        try {
            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            $customer = $this->customer->skipPresenter()->find($customer_id);
            // $user= $customer->user;
            // $offer = $this->offer->skipPresenter()->find($request->offer_id);
            $per_page     = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $delears      = [];
            $msg          = '';
            $customer_zip = $customer->zip;
            if ($customer_zip != '') {

                //1 get all zip codes using zip api
                $zip       = new ZipCodeApi();
                $zip_codes = $zip->getNearest($customer->zip, 1000);
                if ($zip_codes->getFoundZips() != null) {
                    // 2 Get delears_id
                    $delears_id = Dealer::whereIn('zip', $zip_codes->getFoundZips())->pluck('id');

                    //3 get offers
                    $offer = $this->offer->myBaOffers($delears_id);
                    if ($request->get('keyword') != '') {
                        $keyword = $request->get('keyword');
                        $offer->whereOffers(escape_like($keyword));
                    }

                    $offers = $offer->paginate($per_page);

                    $data = [
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'message' => config('responses.success.status_message'),
                    ];
                    $data = array_merge($data, $offers);

                    return response()->json($data, config('responses.success.status_code'));

                } else {
                    $msg = 'Unable to find nearest offer';
                }
            } else {
                $msg = 'Customer zip code is invalid.';
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => $msg,
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }

    /**
     * Customer Ba Offer
     *
     * Get a list of offers created by BA.
     *
     * @param      \App\Http\Requests\Api\CustomerBaBrandOfferRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     * @param      Integer                                   $brand_id  The brand identifier
     *
     * @return     Response
     */
    public function salesRepsOffer(CustomerBaBrandOfferRequest $request) {
        try {
            $customer_id = $request->get('customer_id');
            $brand_id    = $request->get('brand_id');

            $customer = $this->customer->skipPresenter()->find($request->get('customer_id'));
            // $user= $customer->user;
            // $offer = $this->offer->skipPresenter()->find($request->offer_id);
            $per_page     = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $delears      = [];
            $msg          = '';
            $customer_zip = $customer->zip;
            if ($customer_zip != '') {

                //1 get all zip codes using zip api
                $zip       = new ZipCodeApi();
                $zip_codes = $zip->getNearest($customer->zip, 1000);
                if ($zip_codes->getFoundZips() != null) {
                    // 2 Get delears_id
                    $delears_ids = Dealer::whereIn('zip', $zip_codes->getFoundZips())->pluck('id');

                    //3 get offers
                    $offer = $this->offer->myBaBrandOffers($delears_ids)->offerByBrand($brand_id);

                    if ($request->get('keyword') != '') {
                        $keyword = $request->get('keyword');
                        $offer->whereOffers(escape_like($keyword));
                    }

                    $offers = $offer->paginate($per_page)->toArray();

                    $data = [
                        'status'  => true,
                        'code'    => config('responses.success.status_code'),
                        'message' => config('responses.success.status_message'),
                    ];
                    $data = array_merge($data, $offers);

                    return response()->json($data, config('responses.success.status_code'));

                } else {
                    $msg = 'Unable to find nearest offer';
                }
            } else {
                $msg = 'Customer zip code is invalid.';
            }

            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => [],
                'message' => $msg,
            ], config('responses.bad_request.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
    /**
     * Customer Ba Offer
     *
     * Get a list of offers created by assigned BA.
     *
     * @param      \App\Http\Requests\Api\CustomerAssignedBaOfferRequest  $request      The request
     * @param      Integer                                   $customer_id  The customer identifier
     * @param      Integer                                   $brand_id  The brand identifier
     *
     * @return     Response
     */
    public function assignedBaOffers(CustomerAssignedBaOfferRequest $request) {
        try {

            $wp_customer_id = $request->get('wp_customer_id');
            $wp             = new WpConvetor();
            $customer_id    = $wp->getId('customer', $wp_customer_id);

            $wp_brand_id = $request->get('wp_brand_id');
            $brand_id    = $wp->getId('brand', $wp_brand_id);

            $per_page = $request->get('per_page') != '' ? $request->get('per_page') : 20;
            $delears  = [];
            $msg      = '';

            $salesreps = CustomerSalesRep::where('customer_id', $customer_id)->pluck('salesrep_id');
            $offer     = $this->offer->assignedBaOffers($salesreps);

            if ($brand_id != '') {
                $offer->offerByBrand($brand_id);
            }

            if ($request->get('keyword') != '') {
                $keyword = $request->get('keyword');
                $offer->whereOffers(escape_like($keyword));
            }

            $offers = $offer->paginate($per_page);
            // dd($offers);
            $data = [
                'status'  => true,
                'code'    => config('responses.success.status_code'),
                'message' => config('responses.success.status_message'),
            ];
            $data = array_merge($data, $offers);

            return response()->json($data, config('responses.success.status_code'));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'status'  => false,
                'code'    => config('responses.bad_request.status_code'),
                'data'    => null,
                'message' => $e->getMessage(),
            ],
                config('responses.bad_request.status_code')
            );
        }
    }
}
