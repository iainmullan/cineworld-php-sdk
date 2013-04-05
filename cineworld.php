<?php
class Cineworld {
	
	var $baseUrl = "http://www.cineworld.com/api/quickbook";
	var $format = 'json';
	var $key;
	
	function __construct($key) {
		$this->key = $key;
	}

	function cinemas($params = array()) {
		$params['full'] = 'true';
		$response = $this->_method('cinemas', $params);
		return $response['cinemas'];
	}
	
	function films($params = array()) {
		$response = $this->_method('films', $params);
		return $response['films'];
	}
	
	function cinema($id) {

		$params = array(
			'cinema' => $id
		);
		$cinemas = $this->_method('cinemas', $params);
		if (!empty($cinemas)) {
			return $cinemas[0];
		}
		
		return false;
	}

	function film($id) {

		$params = array(
			'film' => $id,
			'full' => 'true'
		);

		$films = $this->_method('films', $params);
		if (!empty($films)) {
			return $films[0];
		}

		return false;
	}

	function performances($filmId, $cinemaId, $date = false) {
		
		if (!$date) {
			$date = date('Ymd');
		}
		$params = array(
			'film' => $filmId,
			'cinema' => $cinemaId,
			'date' => $date
		);

		$performances = $this->_method('performances', $params);
		return $performances['performances'];
	}

	/**
	*
	*/
	protected function _method($method, $params = array()) {
		$url = "{$this->baseUrl}/{$method}";
		$params['key'] = $this->key;
		$response = $this->get($url,$params);
		return $response;
	}

	protected function get($url,$params) {

		if ($params) {
			$url = $url.'?'.http_build_query($params);
		}
		
		echo "\n$url\n";

		$response = file_get_contents($url);
		$response = json_decode($response, true);
		
		return $response;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		
//		var_dump($response);

		if ($this->format == 'json') {
			$response = json_decode($response, true);
		}

		return $response;
	}

}
