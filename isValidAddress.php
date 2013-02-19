<?php


require_once("btcfunctions.php");
	
$valid=true;
	
if (isset($_GET["address"]))
{
	$bitcoin=openBitcoin();			
	$return=$bitcoin->validateaddress($_GET["address"]);	
	$valid=$return["isvalid"];
}
else
{
	$valid=false;
}
		
if ($valid)
{
	$message="Valid address";
}else
{
	$message="INVALID address!";
}

echo json_encode(array("valid"=>$message));
?>