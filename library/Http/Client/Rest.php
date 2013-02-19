<?php
class Http_Client_Rest extends Http_Client_Curl_Request{
	public function auth($user, $pwd){
		$this->options['CURLOPT_USERPWD'] = "$user:$pwd";
		$this->options['CURLOPT_HTTPAUTH'] = CURLAUTH_DIGEST;
	}
}