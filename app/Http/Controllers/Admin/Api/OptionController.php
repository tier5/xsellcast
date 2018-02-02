<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Auth;

class OptionController extends Controller
{
	public function setTz(Request $request)
	{
		$response = new Response;
		$tz       = $request->get('tz');

		$response->withCookie(cookie()->forever('tz', $tz));
		$response->setcontent(json_encode(['tz' => $tz]));

    	return $response;
	}

}