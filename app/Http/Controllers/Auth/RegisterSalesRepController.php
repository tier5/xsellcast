<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Auth;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSalesRepSaveDealerPostRequest;
use App\Http\Requests\RegSalesRepSaveSocialProfPostRequest;

use App\Storage\Media\MediaRepository;
use App\Storage\SalesRep\SalesRepRepository;
use App\Storage\UserActivations\UserActivationsRepository;
use App\Storage\Dealer\DealerRepository;
use App\Storage\LocalApiRequest\LocalApiRequest;
use App\Storage\User\UserRepository;
use App\Storage\Category\Category;

class RegisterSalesRepController extends Controller
{

	use AuthenticatesAndRegistersUsers;

	protected $salesrep;

	protected $user_activation;

	protected $dealer;

    protected $media;

    public function __construct(SalesRepRepository $salesrep, UserActivationsRepository $user_activation, DealerRepository $dealer, UserRepository $user, MediaRepository $media)
    {
    	$this->salesrep = $salesrep;
    	$this->user_activation = $user_activation;
    	$this->dealer = $dealer;
        $this->user = $user;
        $this->media = $media;
    }

    public function showRegistrationForm(Request $request)
    {

        $salesRepId   = $request->get('id');
        $sales_rep    = $this->salesrep->currentUser($salesRepId);
        $user         = ($sales_rep ? $sales_rep->user()->first()  : null );
        $reglvl       = $this->salesrep->registrationLevel($sales_rep);
        $apiRequest   = new LocalApiRequest();
        $access_token = $apiRequest->getToken();
        $categories   = Category::get();


        if(!$sales_rep){

            /**
             * Force logout if ID dont match with current sales rep user login.
             */
            Auth::logout();
        }

    	return view('auth.register_salesrep', compact('access_token', 'sales_rep', 'user', 'reglvl', 'categories'));
    }

    /**
     * Stores an sales rep account.
     *
     * @param      \Illuminate\Http\Request  $request  The request
     *
     * @return     Response Return sales rep info.
     */
    public function storeAccount(Request $request)
    {
        $validator = $this->validatorAccount($request->all());

        if ($validator->fails()) {
           $this->throwValidationException($request, $validator);
        }

        $salesRep = $this->createAccount($request->all(), false);

        return response()->json($salesRep);
    }

    protected function validatorAccount(array $data)
    {
        $rules = [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'];

        return Validator::make($data, $rules);
    }

    /**
     * Store basic information of the sales rep as user.
     *
     * @param      array   $data   The data
     *
     * @return     Array
     */
    protected function createAccount($data, $send_welcome = true)
    {
        $user = $this->user->createSalesRep($data);

        if($send_welcome){
            $this->user->mailSalesRepAcctCreated($user->salesRep);
        }

        Auth::guard($this->getGuard())->login($user);

        return $user;
    }

    public function storeDealer(RegisterSalesRepSaveDealerPostRequest $request)
    {
        /**
         * Pull salesrep data.
         *
         */
        $salesRep = $this->salesrep->currentUser();
        
        /**
         * Add dealer & salesrep relation
         */
        $this->dealer
            ->skipPresenter()
            ->find($request->get('dealer_id'))
            ->salesReps()
            ->save($salesRep);
        // $res->getBody();  	
        
        return response()
            ->json($salesRep);
    }

    public function storeSocialProfile(RegSalesRepSaveSocialProfPostRequest $request)
    {
        $salesRep = $this->salesrep->currentUser();
        $salesRep->facebook = $request->get('facebook');
        $salesRep->twitter = $request->get('twitter');
        $salesRep->linkedin = $request->get('linkedin');
        $salesRep->save();

        $this->user->mailSalesRepAcctCreated($salesRep);

        return response()->json($salesRep);
    }

    public function confirmAccount(Request $request, $token)
    {
        $user = $this->user->getByActivation($token);
    
        if(!$user)
        {
            return redirect()->route('auth.login')->withErrors(["Token don't exist or user has been already activated."]);
        }

        $fromStatus = $user->status;
        $deleted = $this->user_activation->deleteActivation($token); 
        
        if($deleted)
        {
            $user->saveAsActivated();

            Auth::logout();
            Auth::login($user);            
        }

        if($fromStatus == 'invited_unconfirmed')
        {
            $request->session()->flash('message', 'Email confirmed!');   
            return redirect()->route('admin.settings.profile');
        }else{

            return view('auth.confirmation', compact('deleted'));
        }
    }
}
