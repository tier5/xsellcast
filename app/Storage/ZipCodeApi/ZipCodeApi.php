<?php namespace App\Storage\ZipCodeApi;

class ZipCodeApi
{
	protected $foundZips = null;

	protected $error = null;

	public function setFoundZips($zips)
	{
		$this->foundZips = $zips;

		return $this;
	}

	public function getFoundZips()
	{
		return $this->foundZips;
	}

	public function setError($error)
	{
		$this->error = $error;

		return $this;
	}

	public function getError()
	{
		return $this->error;
	}

	public static function getNearest($zip)
	{
		$apiKey = config('lbt.zipcodeapi_key');
		$z = new ZipCodeApi();

		/**
		 * Distance must count as miles
		 *
		 * @var        string
		 */
		$distance = '50';
		$url = "https://www.zipcodeapi.com/rest/" . $apiKey . "/radius.json/" . $zip . "/" . $distance . "/miles?minimal";

		/**
		 * Do curl request to fetch zip list.
		 */
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));

		$result = json_decode(curl_exec($curl));

		if(isset($result->zip_codes)){

			$z->setFoundZips($result->zip_codes);
		}

		if(isset($result->error_code) && $result->error_code == 401){

			$z->setError($result->error_msg);
		}

		return $z;
	}
}