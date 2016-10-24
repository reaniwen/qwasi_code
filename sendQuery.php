<?php 
// http://php.net/manual/en/function.curl-setopt.php
function sendQuery($method = NULL, $data = NULL) {
	if ($method == NULL) {
		$resArray = array("ErrorCode"=>-999);
		return $resArray;
	}
	$ch = curl_init(); 

	$url = "https://aim-mcinstance-new-02.qwasi.com/REST/rest21.php";

	$username = "user123";
	$password = "Qwasi123";
	$headers = array("Content-type: application/xml", "qid: 140");

	$mobile = "6466960087";
	$message = "Hello World";
	$parameters = array();
	if($method == "sendSMS"){
		$parameters = array(
			"operation" => "Put",
			"method" => "misc.sendsms",
			"mobile" => $data["mobile"],
			"message" => $data["message"], 
			);
	} else if ($method == "createUser") {
		$parameters = array(
			"operation" => "Put",
			"method" => "member.create",
			"msisdn" => $data["mobile"],
			"first_name" => $data["firstName"],
			"last_name" => $data["lastName"],
			"email" => $data["email"],
			"optin_status" => 1
			);	
	}
	
	$fields_string = http_build_query($parameters);

	$options = array(
		CURLOPT_URL => $url,						
		CURLOPT_HTTPHEADER => $headers,				
		CURLOPT_USERPWD => $username.":".$password,	// authentication
		CURLOPT_POSTFIELDS => $fields_string,		// data in body
		CURLOPT_ENCODING => "gzip",
		CURLOPT_SSL_VERIFYPEER => false,			// insecure SSL
		CURLOPT_RETURNTRANSFER => true,				// return the transfer as a string 
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)'
		);
	curl_setopt_array($ch, $options);

	$output = curl_exec($ch);
	$xml = simplexml_load_string($output);		// convert the output into XML format
	$json = json_encode($xml);					// convert the XML to json
	$resArray = json_decode($json,TRUE);		// convert json to Array


	// close curl resource to free up system resources 
	curl_close($ch);

	// test to see if the result is currect
	// foreach ($resArray as $key => $value) {
	// 	echo "the val of $key is $value";
	// 	echo "<br>";
	// }

	return $resArray;
}
	