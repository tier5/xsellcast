<?php namespace App\Storage\LocalApiRequest;

use GuzzleHttp\Client;

class LocalApiRequest
{

    protected $token;

    public function __construct()
    {
        $this->token = $this->requestToken();
    }

    protected function requestToken()
    {
        $client = new Client();
        $json = null;
        try{
            $response = $client->request('POST', route('oauth.request-token'), [
                'form_params' => [
                    'client_id' => config('lbt.api_client_id'),
                    'client_secret' => config('lbt.api_secret_id')
                ]
            ]);

            $json = json_decode($response->getBody());
        }
        catch(\Exception $e){

        }

        if(!isset($json->data->access_token)){

            return null;
        }

        return $json->data->access_token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function dealers()
    {
        $client = new Client();
        $response = $client->request('GET', route('api.v1.dealers'), [
            'query' => [
                'access_token' => $this->getToken()
            ]
        ]);

        return $response->getBody();
    }

    public function url($route, $params = [])
    {
        $params['access_token'] = $this->getToken();
        return route($route, $params);
    }

    public static function requestUrl($route, $params = [])
    {
        $local = new LocalApiRequest();

        return $local->url($route, $params);
    }
}