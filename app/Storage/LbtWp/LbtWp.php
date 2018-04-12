<?php namespace App\Storage\LbtWp;

use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
//use Vnn\WpApiClient\WpClient;
use App\Storage\LbtWp\WpClient;
use GuzzleHttp\Client;

class LbtWp
{
	protected $wp_site;

	protected $wp_user;

	protected $wp_pass;

	public function __construct()
	{
		$this->wp_site = config('lbt.wp_site');
		$this->wp_user = config('lbt.wp_user');
		$this->wp_pass = config('lbt.wp_pass');
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
	public function categories() {
		return $this->client()->categories();

	}
}

?>