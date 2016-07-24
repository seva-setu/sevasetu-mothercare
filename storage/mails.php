<?php
	$template = array();
	
	function template1($placeholder){
		$ret = '<MESSAGE>
		<AUTHKEY>119770ArC5iGknjIL5792d144</AUTHKEY>
		<SENDER>SEVSTU</SENDER>
		<ROUTE>4</ROUTE>
		<CAMPAIGN>XML API</CAMPAIGN>
		<COUNTRY>91</COUNTRY>
		<SMS TEXT="Welcome to Seva Setu\'s Mother Care Program. For assistance, write to help@sevasetu.org" >
		<ADDRESS TO="'.$placeholder.'"></ADDRESS>
		</SMS>
		</MESSAGE>';
		return $ret;
	}
	
	
	function send_sms($template_id, $data_arr){
		$url = 'https://control.msg91.com/api/postsms.php';
		$key = $_ENV['SMS_KEY'];
		$name = 'SEVSTU';
		
		if($template_id == 1)
			$data = array('data'=>call_user_func_array('template'.$template_id, array($data_arr)));
		
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
