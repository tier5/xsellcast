<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Auth;
use Socialite;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\User\UserRepository;
use App\Storage\Media\MediaRepository;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Session;
class SocialController extends Controller
{
	use AuthenticatesAndRegistersUsers;

	protected $user;

	protected $media;

	public function __construct(UserRepository $user, MediaRepository $media)
	{
		$this->user = $user;
		$this->media = $media;
	}

	/**
	 * Redirect the user to the GitHub authentication page.
	 *
	 * @return Response
	 */
	public function redirectToProvider($provider)
	{
		// $socpe=[
		// 	'r_basicprofile',
		// 	'r_emailaddress',
		// 	'rw_company_admin',
		// 	'w_share'
		// 	];

	    return Socialite::driver($provider)
	    	// ->scopes($socpe)
	    	->redirect();
	}

	/**
	 * Obtain the user information from GitHub.
	 *
	 * @return Response
	 */
	public function handleProviderCallback(Request $request,$provider)
	{

		// $fields = ['name', 'email', 'gender', 'verified', 'link', 'first_name', 'last_name'];
		$token    = $request->get('code');
		// dd($request->all());
		try {

			$user   = Socialite::driver($provider)
			// ->fields($fields)
			->user();
			if(Session::has('demo')){
				Session::forget('demo');
				 return view('auth.linkedin_demo')->with('user',$user);
			}

		// dd($user);
		}catch(\Exception $e){

			return redirect()->route('home');
		}

		$userInfo = $user->user;
		if($user->avatar_original!=null){
				$media    = $this->media->uploadImgFrmFb($user->avatar_original);
		}


		$userData = array(
			'meta' => array(
				'name'             => $user->name,
				'in_registered'	   => true,
				'in_token'         => $user->token,
				'in_refresh_token' => $user->refreshToken,
				'in_expire_token'  => $user->expiresIn,
				'profile_img'      => $user->avatar_original,
				'in_id'            => $user->id,
				'in_profile_url'   => $userInfo['publicProfileUrl'],
				'avatar_media_id'  => $media->id ),
			'linkedin'  => $userInfo['publicProfileUrl'],
			'job_title' => $userInfo['industry'],
			'email'     => $user->email,
			'firstname' => $userInfo['firstName'],
			'lastname'  => $userInfo['lastName'],
			'password'  => str_random(8)
		);
		// dd($userData);

		$foundUser = $this->user->skipPresenter()->findByField('email', $user->email)->first();
// dd($foundUser);
		if($foundUser)
		{
			$user = $foundUser;

			Auth::guard($this->getGuard())->login($user);

			$salesRep = $user->salesRep()->first();
			if($salesRep){
			// dd('update');
			//TODO: update missing info like cell phone, avatar, physical address, professional title and business affiliation.
			//
			if($salesRep->linkedin==''){
			$salesRep->linkedin=$userInfo['publicProfileUrl'];
			}
			if($salesRep->job_title==''){
			$salesRep->job_title=$userInfo['industry'];
			}
			// if($salesRep->email_work==''){
			// $salesRep->email_work=$user->email;
			// }


			$salesRep->save();
			}

		}else
		{
			// dd('create new');
			$user = $this->user->createSalesRep($userData);

			Auth::guard($this->getGuard())->login($user);

			$salesRep = $user->salesRep()->first();
			$salesRep->linkedin=$userInfo['publicProfileUrl'];
			$salesRep->job_title=$userInfo['industry'];

			$salesRep->save();

			return redirect()->route('register', ['id' => $salesRep->id]);
		}



      return redirect()->route('home');


	}

    public function cancelRegister()
    {
		Auth::logout();
		return redirect()->route('home');
    }

    ///Demo
    /**
	 * Redirect the user to the linkedin authentication page.
	 *
	 * @return Response
	 */
	public function redirectToProviderDemo()
	{
		Session::put('demo','1');

	    return Socialite::driver('linkedin')
	    	// ->scopes($socpe)
	    	->redirect();
	}

}