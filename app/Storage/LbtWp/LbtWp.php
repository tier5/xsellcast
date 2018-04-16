<?php namespace App\Storage\LbtWp;

use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
//use Vnn\WpApiClient\WpClient;
use App\Storage\LbtWp\WpClient;
use App\Storage\LbtWp\LbtException;
use GuzzleHttp\Client;


class LbtWp
{
	protected $wp_site;

	protected $wp_user;

	protected $wp_pass;

	protected $client_id;
	protected $client_secret;
	protected $access_token;
	protected $wp_api_site;


	public function __construct()
	{
		$this->wp_site = config('lbt.wp_site');
		$this->wp_user = config('lbt.wp_user');
		$this->wp_pass = config('lbt.wp_pass');

		$this->client_id = config('lbt.wp_api_client_id');
		$this->client_secret = config('lbt.wp_api_secret_id');
		$this->wp_api_site = config('lbt.wp_api_site');

		$this->getToken();
	}

	public function client()
	{
		$client = new WpClient(new GuzzleAdapter(new Client()), $this->wp_site);
		$client->setCredentials(new WpBasicAuth($this->wp_user, $this->wp_pass));
		return $client;
	}

	public function posts()
	{
		return $this->client()->posts();
	}

	public function offers()
	{

		return $this->client()->offers();
	}

	private function api_url($path)
    {
        return $this->wp_api_site . $path;
    }

	 private function url_params($params)
    {
        if (count($params) == 0) {
            return "";
        }
        $str_params = array();

        foreach ($params as $key => $val) {
            if(gettype($val) == "array"){
                for($i = 0; $i < count($val); $i++){
                    array_push($str_params, $key . "[]=" . urlencode($val[$i]));
                }
            } else {
                array_push($str_params, $key . "=" . urlencode($val));
            }
        }

        return   join("&", $str_params);
    }
    private function parsed_response($response)
    {
        $json_decoded = json_decode($response, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            return $response;
        }

        return $json_decoded;
    }

    public function handle_response($result, $status_code)
    {
        if ($status_code >= 200 && $status_code < 300) {

            return $this->parsed_response($result);
        }
        else{
        	dd($result);
        }

        throw new LbtException($this->http_codes[$status_code], $status_code, $this->parsed_response($result));
    }

    private $http_codes = array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      102 => 'Processing',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      207 => 'Multi-Status',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      306 => 'Switch Proxy',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Timeout',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Long',
      415 => 'Unsupported Media Type',
      416 => 'Requested Range Not Satisfiable',
      417 => 'Expectation Failed',
      418 => 'I\'m a teapot',
      422 => 'Unprocessable Entity',
      423 => 'Locked',
      424 => 'Failed Dependency',
      425 => 'Unordered Collection',
      426 => 'Upgrade Required',
      449 => 'Retry With',
      450 => 'Blocked by Windows Parental Controls',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Timeout',
      505 => 'HTTP Version Not Supported',
      506 => 'Variant Also Negotiates',
      507 => 'Insufficient Storage',
      509 => 'Bandwidth Limit Exceeded',
      510 => 'Not Extended'
    );


	 // private function http_get($path, array $params = array())
  //   {
  //       $url = $this->api_url($path);
  //       $url .= $this->url_params($params);

  //       if (filter_var($url, FILTER_VALIDATE_URL)===false) {
  //           throw new LbtException('invalid URL');
  //       }

  //       $curl = curl_init();
  //       curl_setopt($curl, CURLOPT_URL, $url);
  //       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  //       curl_setopt($curl, CURLOPT_HTTPHEADER, $this->get_auth_headers());
  //       curl_setopt($curl, CURLOPT_USERAGENT, self::USERAGENT);
  //       $result = curl_exec($curl);
  //       if (curl_errno($curl) > 0) {
  //           throw new LbtException(curl_error($curl), 2);
  //       }
  //       $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  //       curl_close($curl);

  //       return $this->handle_response($result, $status_code);
  //   }

	private function http_post($path, array $params = array())
    {
        $url = $this->api_url($path);

		$url_params=$this->url_params($params);

        if (filter_var($url, FILTER_VALIDATE_URL)===false) {
            throw new LbtException('invalid URL');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $this->get_auth_headers(true));
        // curl_setopt($curl, CURLOPT_USERAGENT, self::USERAGENT);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        $result = curl_exec($curl);
        if (curl_errno($curl) > 0) {
            throw new LbtException(curl_error($curl), 3);
        }
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        // return($result);
        return $this->handle_response($result, $status_code);
    }

    // private function http_delete($path, array $params = array())
    // {
    //     $url = $this->api_url($path);

    //     if (filter_var($url, FILTER_VALIDATE_URL)===false) {
    //         throw new LbtException('invalid URL');
    //     }

    //     $curl = curl_init();
    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $this->get_auth_headers(true));
    //     curl_setopt($curl, CURLOPT_USERAGENT, self::USERAGENT);
    //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
    //     $result = curl_exec($curl);
    //     if (curl_errno($curl) > 0) {
    //         throw new LbtException(curl_error($curl), 4);
    //     }
    //     $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //     curl_close($curl);

    //     return $this->handle_response($result, $status_code);
    // }


	private function getToken(){

		$client_auth=[
			'client_id'=>$this->client_id,
			'client_secret'=>$this->client_secret
		];
		$result=$this->http_post('fetch_token',$client_auth);
		if($result['code']==200){
		$this->access_token=$result['data']['access_token'];
		}

	}

	public function storeCategory(array $params=array()){

		$params['access_token']=$this->access_token;

		$result=$this->http_post('add_new_brand',$params);
		if($result['code']==200){
		return $result;
		}

	}
}
		// $result=$this->http_post('wp-json/wpr-datahub-api/v1/fetch_token',$auth);

// // 		$client = new  \GuzzleHttp\Client(['base_uri' => 'https://v2.luxurybuystoday.com/wp-json/wpr-datahub-api/v1/']);
// // 		$response = $client->request('POST', 'fetch_token', [
// // 		    'form_params' => $auth,
// // 		]);
// // $body = $response->getBody();
// // 		 dd($body);
// // 		 exit;

// $url_params=$this->url_params($auth);
// echo $url_params;
// // Make Post Fields Array
// $curl = curl_init();
// // echo  'client_id=rRXeQSMR8a65IA7a0uMdwwAqMjL5SAgNsoUcK3Va&client_secret=18AfMk8StGPoKNzDo1TylfeTmVMINsjy1wZadtaj';
// // curl_setopt_array($curl, array(
// //     CURLOPT_URL => "https://v2.luxurybuystoday.com/wp-json/wpr-datahub-api/v1/fetch_token",
// //     CURLOPT_RETURNTRANSFER => true,
// //     CURLOPT_ENCODING => "",
// //     CURLOPT_MAXREDIRS => 10,
// //     CURLOPT_TIMEOUT => 30000,
// //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// //     CURLOPT_CUSTOMREQUEST => "POST",
// //     CURLOPT_POSTFIELDS => json_encode($auth),
// //     CURLOPT_HTTPHEADER => array(
// //     	// Set here requred headers

// //        'Content-Type: x-www-form-urlencoded',
// //     ),
// // ));
// $url=$this->wp_api_site."fetch_token";
// curl_setopt($curl, CURLOPT_URL, $url);
// // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
// curl_setopt($curl, CURLOPT_POST, 1);
// curl_setopt($curl, CURLOPT_POSTFIELDS,$url_params);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_HTTPHEADER , array(
//     	// Set here requred headers
//         "accept: */*",
//         "accept-language: en-US,en;q=0.8",
//         "contentType: application/json; charset=UTF-8",
//         "cache-control:no-cache, must-revalidate, max-age=0"
//     )
// );

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//     echo "cURL Error #:" . $err;
// } else {
//     print_r(json_decode($response));
// }
// 	}
// }

?>