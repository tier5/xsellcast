<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserPostRequest;
use App\Http\Requests\FetchUsersGetRequest;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * @resource User
 *
 * User resource.
 */
class UsersController extends Controller
{
    public function __construct()
    {

    }

    /**
     * All
     *
     * Get a list of users.
     *
     * @param  String access_token
     * @return Response
     */
    public function index(FetchUsersGetRequest $request, Authorizer $authorizer)
    {
    	$result = array();

		return response()
			->json(array(
				'result' => $result ));
    }

    /**
     * Create
     *
     * Create a new user.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(StoreUserPostRequest $request)
    {

    }


}
