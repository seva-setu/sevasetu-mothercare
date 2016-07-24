<?php
	$template = array();
	
	function template1($phone_number, $auth_token){
		$ret = '<MESSAGE>
		<AUTHKEY>'.$_ENV['SMS_KEY'].'</AUTHKEY>
		<SENDER>'.$_ENV['SMS_NAME'].'</SENDER>
		<ROUTE>'.$_ENV['SMS_ROUTE'].'</ROUTE>
		<CAMPAIGN>XML API</CAMPAIGN>
		<COUNTRY>91</COUNTRY>
		<SMS TEXT="'.$auth_token.' is your passkey. For assistance, write to help@sevasetu.org" >
		<ADDRESS TO="'.$phone_number.'"></ADDRESS>
		</SMS>
		</MESSAGE>';
		return $ret;
	}
	
	
	function send_sms($template_id, $data_arr){
		$url = $_ENV['SMS_URL'];
		
		if($template_id == 1)
			$data = array('data'=>call_user_func_array('template'.$template_id, $data_arr));
		
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		if ($result === FALSE) { /* Handle error */ }
		return $result;
	}


?>
