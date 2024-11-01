<?php

class Soclall
{
	private $_app_id;
	private $_app_secret;
	private $_login_url = 'https://api2.socialall.dev/login';
	private $_service_url = 'https://api2.socialall.dev/';

	public function __construct($data)
	{
		$this->_app_id = $data['app_id'];
		$this->_app_secret = $data['secret_key'];
	}

	public function getLoginUrl($network, $callback, $site, $scope = 'user')
	{
		$param = array(
			'app_id' => $this->_app_id,
			'callback' => $callback,
			'scope' => $scope
		);
		if (!empty($site))
			$param['site'] = $site;
		return $this->_login_url . '/' . $network . '?' . http_build_query($param);
	}

	public function getUser($token)
	{
		$params = array(
			'token' => $token
		);
		$response = $this->makeRequest('user', $params);
		return $response;
	}

	public function getFriends($token)
	{
		$params = array(
			'token' => $token
		);
		$response = $this->makeRequest('friends', $params);
		return $response;
	}

	public function postStream($token, $message)
	{
		$params = array(
			'token' => $token,
			'message' => $message
		);
		$response = $this->makeRequest('publish', $params);
		return $response;
	}

	public function sendMessage($token, $message, $friends, $title = '')
	{
		if (!is_array($friends))
			exit('wrong type . type of second parameter must be an array');

		$params = array(
			'token' => $token,
			'friend_id' => implode(',', $friends),
			'message' => $message
		);

		if (!empty($title))
			$params['title'] = $title;

		$response = $this->makeRequest('message', $params);

		return $response;
	}

	public function summary()
	{
		$params = array(
			'app_id' => $this->_app_id,
		);

		$response = $this->makeRequest('summary', $params);

		return $response;
	}

	private function makeRequest($path, $params)
	{
		$sig = $this->signRequest($params);
		$params['sig'] = $sig;

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query($params),
			)
		));

		$api_url = $this->_service_url . $path;

		$response = file_get_contents($api_url, false, $context);

		return json_decode($response, true);
	}

	private function signRequest($data)
	{
		ksort($data);
		$str_data = '';
		foreach ($data as $key => $value)
			$str_data .= "$key=$value";

		return md5($this->_app_secret . $str_data);
	}
}
