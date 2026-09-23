<?php
namespace App\Helpers;
class IPInfoDB
{
	protected $apiKey;

	public function __construct($apiKey)
	{
		/*if (!preg_match($apiKey)) {
			throw exception('Invalid IPInfoDB API key.');
		}*/
		$this->apiKey = $apiKey;
	}

	public function getCountry($ip)
	{
        $url = 'http://api.ipinfodb.com/v3/ip-country?key=' . $this->apiKey . '&ip=' . $ip . '&format=json';
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $response = curl_exec( $ch );
        curl_close($ch);

        if(is_string($response) && is_array(json_decode($response, true)) && (json_last_error() == JSON_ERROR_NONE)){
            $response	= json_decode($response);
        }else{
            $response	= array();
        }
		return $response;
	}

	public function getCity($ip)
	{
		$response = @file_get_contents('http://api.ipinfodb.com/v3/ip-city?key=' . $this->apiKey . '&ip=' . $ip . '&format=json');

		if (($json = json_decode($response, true)) === null) {
			$json['statusCode'] = 'ERROR';
			return false;
		}

		$json['statusCode'] = 'OK';

		return $json;
	}
}
