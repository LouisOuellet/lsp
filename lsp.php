<?php
class LSP {

	private $cURL;
	private $Token;
	public $Status = FALSE;

	public function __construct($server,$app,$license,$hash,$strategy = FALSE){
		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_URL, $server.'?app='.$app.'&license='.$license);
		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
		$this->Token = curl_exec($this->cURL);
		curl_close($this->cURL);
		if(($this->Token.$hash != '')&&(password_verify($this->Token, $hash))){
			if($strategy){
				$this->Status = TRUE;
			}
		} else {
			if(!$strategy){
				echo 'Invalid License';
				exit;
			}
		}
	}
}
