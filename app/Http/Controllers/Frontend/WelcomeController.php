<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;

class WelcomeController extends Controller
{
    public function home()
    {
    	if(Auth::check())
    	{
    		return redirect()->route('home');	
    	}else{
    		return redirect()->route('auth.login');	
    	}
       // 
        ///view('welcome');

    }

}
