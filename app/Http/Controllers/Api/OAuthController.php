<?php

namespace App\Http\Controllers\Api;

use Response;
use Authorizer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\OAuthRequestTokenPostRequest;
use App\Http\Controllers\Controller;

/**
 * @resource OAuth - Token
 *
 * Request and refresh app access token.
 * Token is require for accessing xsellcast api. Ask caffeine team for your site client_id and secret_id.
 */
class OAuthController extends Controller
{

    /**
     * Request Token
     *
     * Send request for acquiring client credential token.
     * 
     * @param  OAuthRequestTokenPostRequest $request
     * @return Response
     */
    public function getClientCredentialsToken(OAuthRequestTokenPostRequest $request)
    { 
        $request->request->add(['grant_type' => 'client_credentials']);

        $authIssuer = Authorizer::getIssuer();
        $authIssuer->setRequest($request);
        return Response::json($authIssuer->issueAccessToken());
    }
}