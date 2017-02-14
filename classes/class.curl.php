<?php

class CURL {
	
 	 private $useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)'; 
     private $url; 
     private $referer ="http://www.google.com";
	 private $timeout = '60';
	 private $ch;
	
	 function __construct($url) {
		
		 if (!function_exists('curl_init'))
			die('CURL is not installed!');
		  
		$this->url = $url;

	 }
	
	function initCurl() {
		
		 $this->ch = curl_init();
		 curl_setopt($this->ch, CURLOPT_URL, $this->url);
		 curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);
		 curl_setopt($this->ch, CURLOPT_USERAGENT, $this->useragent);
		 curl_setopt($this->ch, CURLOPT_HEADER, 0);
		 curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		
	}
	
	function getStatus() {
		
		$this->initCurl();
			
		return false;
	}
	
	function getContent() {
		
		$this->initCurl();			
		$output = curl_exec($this->ch);
		$this->closeCurl();
		
		return $output;
	}
	
	function closeCurl()  {
		curl_close($this->ch);	
	}
	
	
}

?>