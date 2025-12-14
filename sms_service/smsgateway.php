
<?php
require 'smsgateway/vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;


class SmsGateway {

	function sendSMS($recipients,$message){

	// Set your app credentials
	$username   = "mlimipay_sms";
	$apiKey     = "8cb603caa58adc77089a69082947ba5f95c3d9d03a3ad5987685178c79c390a4";

	// Initialize the SDK
	$AT = new AfricasTalking($username,$apiKey);

	// Get the SMS service
	$sms = $AT->sms();

	// Set your shortCode or senderId
	$from = "MlimiPay";

	try {
	    // Thats it, hit send and we'll take care of the rest
	    $result = $sms->send([
	        'to'      => $recipients,
	        'message' => $message,
	        'from'    => $from
	    ]);

	    //print_r($result);
	} catch (Exception $e) {
	    // "Error: ".$e->getMessage();
	}


	  }
 }
?>