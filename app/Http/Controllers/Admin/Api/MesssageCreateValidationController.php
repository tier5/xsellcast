<?php namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Crud\Crud;
use App\Storage\Offer\OfferRepository;
use Auth;
use App\Http\Requests\Admin\MessageDirectSendRequest;

class MesssageCreateValidationController extends Controller
{

	public function index(MessageDirectSendRequest $request)
	{
		return response()->json([]);	
	}

}