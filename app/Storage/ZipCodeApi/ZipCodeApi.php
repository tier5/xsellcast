<?php namespace App\Storage\ZipCodeApi;

class ZipCodeApi
{
	protected $foundZips = null;
	protected $zipcode = null;
	protected $lat = null;
	protected $long = null;

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

	public function setZipCode($zip)
	{
		$this->zipcode = $zip;

		return $this;
	}
	public function getFoundLat()
	{
		return $this->lat;
	}

	public function setLat($lat)
	{
		$this->lat = $lat;

		return $this;
	}
	public function getFoundLong()
	{
		return $this->long;
	}

	public function setLong($long)
	{
		$this->long = $long;

		return $this;
	}

	public function getZipCode()
	{
		return $this->zipcode;
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

	public static function getNearest($zip,$distance=200)
	{
		$apiKey = config('lbt.zipcodeapi_key');
		$z = new ZipCodeApi();

		/**
		 * Distance must count as miles
		 *
		 * @var        string
		 */
		// $distance = '50';
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



	public static function getZipByIP($ip)
	{

		$z = new ZipCodeApi();

		$url = "http://ip-api.com/json/" .$ip;

		/**
		 * Do curl request to fetch zip list.
		 */
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));

		$result = json_decode(curl_exec($curl));

		if(isset($result->status) && $result->status =='success'){

			$z->setZipCode($result->zip);

		}else{
			$z->setError($result->message);
		}

		return $z;
	}


	public static function getZipByGeo($geo_lat,$geo_long)
	{

		$z = new ZipCodeApi();
		// https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=AIzaSyA-PveyfxZiAnCbUiAl-Up6ddUZAa8EEGw
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$geo_lat.",".$geo_long."&key=" .env('GOOGLE_MAP_API_KEY');

		/**
		 * Do curl request to fetch zip list.
		 */
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));
		$zip_code='';
		$results = json_decode(curl_exec($curl));
		if($results->status!='INVALID_REQUEST'){

			foreach ($results as $result) {
				foreach ($result as $address_components) {
					foreach ($address_components as $address_component) {
						foreach ($address_component as $value) {

							if($value->types[0]=='postal_code'){
								$zip_code=$value->long_name;
								break;
							}
						}break;

					}
					break;
				}
			break;
			}
			$z->setZipCode($zip_code);
		}
	  	else{
			$z->setError($results->error_message);
		}

		return $z;
	}

	public static function getGeoByzip($zip,$distance=200)
	{
		$apiKey = config('lbt.ziptogeo_key');
		$z = new ZipCodeApi();

		/**
		 * Distance must count as miles
		 *
		 * @var        string
		 */
		// $distance = '50';
	 	$url="https://www.zipcodeapi.com/rest/" . $apiKey . "/info.json/" . $zip . "/degrees";
		/**
		 * Do curl request to fetch zip list.
		 */
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));

		$result = json_decode(curl_exec($curl));

		if(isset($result->lat)){

			$z->setLat($result->lat);
		}
		if(isset($result->lng)){

			$z->setLong($result->lng);
		}

		if(isset($result->error_code) && $result->error_code == 401){

			$z->setError($result->error_msg);
		}

		return $z;
	}
}