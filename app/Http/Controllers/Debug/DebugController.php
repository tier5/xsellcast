<?php

namespace App\Http\Controllers\Debug;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Dealer\DealerRepository;

use App\Storage\Cronofy\CronofyHttp;


class DebugController extends Controller
{
	protected $dealer;

    protected $media;

	public function __construct(DealerRepository $dealer)
	{
		$this->dealer = $dealer;
	}

    public function index(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $formParams = [
            'access_token' => $request->get('access_token'),
            'type' => 'appt',
            'sender_id' => 8,
            'recepient_id' => 2,
            'offer_id' => 66,
            'subject' => 'Subject',
            'body' => 'Body'];
        $response = $client->request('POST', route('api.v1.messages.create'), ['form_params' => $formParams]);

        echo '<pre>';
        print_R((string)$response->getBody());
    }

    public function cronofyConnect()
    {
    	$clientId = 'RbzWCPamo9facjLkqtrZ1MQ3Q46KDeoV';
    	$secretId = 'JJpWgUFnKsp-qar2LAqwwekZK8Sz-AU-wwsuWLsJS-PIw94WWvzvwZeTNL2Ysd6iV4AuO9wkMqKMtIibbbFKSg';
    	$cronofy = new CronofyHttp($clientId, $secretId);
    	$scope = array('read_account','list_calendars','read_events','create_event','delete_event');
    	$redirectUri = route('auth.cronofy.callback');

		$url = $cronofy->getAuthorizationURL(array('scope' => $scope, 'redirect_uri' => $redirectUri));

		echo '<a href="'.$url.'">Cronofy</a>';
	//	echo $url = $cronofy->request_token($params);
    }

}