<?php
/**
 * Created by PhpStorm.
 * User: kingpabel
 * Date: 3/23/16
 * Time: 4:40 PM
 */
namespace app\Http\Middleware;
use Closure;
use League\OAuth2\Server\Exception\OAuthException;
class OauthExceptionMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
            // Was an exception thrown? If so and available catch in our middleware
            if (isset($response->exception) && $response->exception) {
                throw $response->exception;
            }
            return $response;
        } catch (OAuthException $e) {
            $data = [
                'status'=>false,
                'code'=>config('responses.access_denied.status_code'),
                'data'=>[],
                //'error' => $e->errorType,
                //'error_description' => $e->getMessage(),
                // 'error' => 'access_denied',
                'error_description' =>  "The resource owner or authorization server denied the request.",
                'message'=>config('responses.access_denied.status_message'),
            ];
            return \Response::json($data, $e->httpStatusCode, $e->getHttpHeaders());
        }
    }
}