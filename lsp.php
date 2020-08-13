<?php
class LSP {

	private $cURL;
	private $Token;
	private $Fingerprint;
	private $Server;
	private $App;
	private $License;
	private $Hash;
	public $Status = FALSE;
	public $Update = FALSE;

	public function __construct($server,$app,$license,$hash){
		$this->Server = $server;
		$this->App = $app;
		$this->License = $license;
		$this->Hash = $hash;
		if(strpos(shell_exec("git status -sb"), 'behind') !== false){
			$this->Update = TRUE;
		}
		$this->Fingerprint = md5($_SERVER['SERVER_ADDR'].$_SERVER['SERVER_NAME']);
		$this->validate();
	}

	public function validate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'?app='.$this->App.'&license='.$this->License.'&fingerprint='.$this->Fingerprint.'&action=validate');
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$this->Token = curl_exec($this->cURL);
			curl_close($this->cURL);
			if(($this->Token.$this->Hash != '')&&(password_verify($this->Token, $this->Hash))){
				$this->Status = TRUE;
			}
		}
	}

	public function activate(){
		if(!$this->Status){
			$this->cURL = curl_init();
			curl_setopt($this->cURL, CURLOPT_URL, $this->Server.'?app='.$this->App.'&license='.$this->License.'&fingerprint='.$this->Fingerprint.'&action=activate');
			curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, 1);
			$this->Token = curl_exec($this->cURL);
			curl_close($this->cURL);
			if(($this->Token.$this->Hash != '')&&(password_verify($this->Token, $this->Hash))){
				$this->Status = TRUE;
			}
		}
	}

	public function update($branch = "master"){
		if($this->Update){
			shell_exec("git pull origin $branch");
		}
	}
}
