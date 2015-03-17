<?php
namespace core\traits;
trait RequestGenerator
{
	private $error;

	private $_requestData = array();

	protected function addRequestData($key, $value)
	{
		$this->_requestData[$key] = $value;
		return $this;
	}

	protected function getRequestDataString()
	{
		$request = array();
		foreach ($this->_requestData as $key => $value){
			$request[] = $key.'='.urlencode($value);
		}
		return implode('&', $request);
	}

	protected function getRequestLink($url)
	{
		return $url.'?'.$this->getRequestDataString();
	}

	protected function sendGETRequest($url)
	{
		$curl = curl_init();
		if ($curl) {
			curl_setopt($curl, CURLOPT_URL, $this->getRequestLink($url));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPGET, true);
			$out = curl_exec($curl);
			curl_close($curl);
			return $out;
		}
		throw new \Exception('Error instantiating session cURL in '.get_class($this).'::'.__METHOD__.'!');
	}

	protected function sendPOSTRequest($url, $sslVerify = false, $userAgent = 'Webdelo Framework Bot')
	{
		$curl = curl_init();
		if ($curl) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_requestData);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			if ($sslVerify){
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: '.$userAgent));
			$out = curl_exec($curl);
			curl_close($curl);
			return $out;
		}
		throw new \Exception('Error instantiating session cURL in '.get_class($this).'::'.__METHOD__.'!');
	}

	protected function jsonResponseConvertion($response)
	{
		$origResponse = $response;
		$response = json_decode($response, true);
		if (json_last_error()) {
			$this->setError('Error parsing the response from the remote server (JSON_ERROR_CODE: '.json_last_error().')! Original Remote Server Response: '.$origResponse);
			return false;
		}
		return $response;
	}

	protected function setError($error)
	{
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}
}
