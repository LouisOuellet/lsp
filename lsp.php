<?php
class LSP {

	private $cURL;
	private $Token;

	public function __construct($server,$license){
		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_URL, $server.'?license='.md5($license));
		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
		$this->Token = curl_exec($this->cURL);
		curl_close($this->cURL);
	}

	public function authenticate($token){
		if(hash_equals($this->Token, $token)){
			return TRUE;
		}
	}
}
