<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Cronofy\CronofyHttp;

class CronofyController extends Controller
{
	public function callback(Request $request)
	{
		$code = $request->get('code');
		$clientId = 'RbzWCPamo9facjLkqtrZ1MQ3Q46KDeoV';
		$secretId = 'JJpWgUFnKsp-qar2LAqwwekZK8Sz-AU-wwsuWLsJS-PIw94WWvzvwZeTNL2Ysd6iV4AuO9wkMqKMtIibbbFKSg';
    	$cronofy = new CronofyHttp($clientId, $secretId);
    	$param = array(
    		'code' => $code,
    		'redirect_uri' => route('auth.cronofy.request.token'));
		
		// $token = $cronofy->request_token($param);

		//print_R($token);
	}
}