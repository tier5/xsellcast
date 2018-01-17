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

class FbSocialController extends Controller
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
	public function redirectToProvider()
	{
	    return Socialite::driver('facebook')
	    	->scopes(['email','public_profile','user_about_me'])
	    	->redirect();
	}

	/**
	 * Obtain the user information from GitHub.
	 *
	 * @return Response
	 */
	public function handleProviderCallback(Request $request)
	{
		$fbFields = ['name', 'email', 'gender', 'verified', 'link', 'first_name', 'last_name'];
		$token    = $request->get('code');
		
		try {

			$userFb   = Socialite::driver('facebook')->fields($fbFields)->user();
		}catch(\Exception $e){
		
			return redirect()->route('home');
		}

		$userInfo = $userFb->getRaw();
		$media    = $this->media->uploadImgFrmFb($userFb->avatar_original);

		$userData = array(
			'meta' => array(
				'name'             => $userFb->name,
				'fb_registered'	   => true,
				'fb_token'         => $userFb->token,
				'fb_refresh_token' => $userFb->refreshToken,
				'fb_expire_token'  => $userFb->expiresIn,
				'profile_img'      => $userFb->avatar_original,
				'fb_id'            => $userFb->id,
				'fb_profile_url'   => 'https://facebook.com/' . $userFb->id,
				'avatar_media_id'  => $media->id ),
			'facebook'  => $userFb->profileUrl,
			'email'     => $userFb->email,
			'firstname' => $userInfo['first_name'],
			'lastname'  => $userInfo['last_name'],	
			'password'  => str_random(8)
		);

		$foundUser = $this->user->skipPresenter()->findByField('email', $userFb->email)->first();
		
		if($foundUser)
		{
			$user = $foundUser;
		}else
		{
			$user = $this->user->createSalesRep($userData);	
		}
		
		$salesRep = $user->salesRep()->first();

        Auth::guard($this->getGuard())->login($user);	

        return redirect()->route('register', ['id' => $salesRep->id]);
	}

    public function cancelRegister()
    {
		Auth::logout();
		return redirect()->route('home');        
    }	
}