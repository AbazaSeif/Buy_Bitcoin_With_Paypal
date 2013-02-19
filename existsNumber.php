<?php

require_once("global_variables.php");
require_once("./textmagic-sms-api-php/TextMagicAPI.php");
require_once("smsfunctions.php");
require_once("calcfunctions.php");

if (isset($_POST["number"]))
{
	$number=sanitizeMobile($_POST["number"]);
	

	$api = new TextMagicAPI(array(
		"username" => $USER_API_SMS,
		"password" => $PASS_API_SMS
		));
		
	try{
	
		$res=$api->checkNumber(array($number));
		$country_code=$res[$number]["country"];
		$country=fromCodeToCountry($country_code);
		echo "Found! It should be a number from $country. If it is not, please make sure you have written your country code.";
		
	} catch(Exception $e) 
	{ 
	
		echo "Not found. Please make sure you have written the country code without '00' or '+'. Also consider that some countries omit some digit when writing in international format (for example, in France a mobile number is 0612345678, but in international format is 33612345678 (without the '0')";
	}
	
	
}

?>

<FORM action="existsNumber.php" method="post">
       
    Phone number  <INPUT type="text" name="number"><BR>    
    <INPUT type="submit" value="Check">
    
 </FORM>
 