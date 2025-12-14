
<?php

class SmsGateway {

	function sendSMS($recipients,$message){

		  $data = array(
					    'api_key' => 'c0Q4bdus34E14NPAbfFv',
					    'password' => 'SMSA2C21',
					    'text' => $message,
					    'numbers' => $recipients,
					    'from' => 'MlimiPay'
					);

		  $curl = curl_init();

		  curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://telcomw.com/api-v2/send',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $data,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		//echo $response;
	}
 }

?>