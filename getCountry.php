<?php

require_once("global_variables.php");
require_once("./textmagic-sms-api-php/TextMagicAPI.php");
require_once("smsfunctions.php");
require_once("calcfunctions.php");

if (isset($_GET["mobile"]))
{
	
	$number=sanitizeMobile($_GET["mobile"]);
	
		
	
	$country="";
	$problems=false;
	
	$api = new TextMagicAPI(array(
		"username" => $USER_API_SMS,
		"password" => $PASS_API_SMS
		));
		
	try{
	
		$res=$api->checkNumber(array($number));
		$country_code=$res[$number]["country"];
		$country=fromCodeToCountry($country_code);
		
		
	} catch(Exception $e) 
	{ 
		$problems=true;
	}
	
	echo json_encode(array("country"=>$country, "error"=>$problems));
}

?>
