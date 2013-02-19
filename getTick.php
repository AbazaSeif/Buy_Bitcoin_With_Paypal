<?php

require_once ("global_variables.php");

function writeTick()
{


	$opts = array(
	  'http'=> array(
		'method'=>   "GET",
		'user_agent'=>    "MozillaXYZ/1.0"));
	$context = stream_context_create($opts);
	$json = file_get_contents('https://mtgox.com/code/data/ticker.php', false, $context);
	$json = json_decode($json);
	$last=$json->{'ticker'}->{'last'};	
	
	$last=fromTickToPVP($last);
	
	$fp=fopen("tick.txt","w");
	if (flock($fp, LOCK_EX)) { // do an exclusive lock
		fwrite($fp,"precio=".$last."&hora=".time());
		flock($fp, LOCK_UN); // release the lock
	}		
	fclose($fp);
	
}

function readTick()
{
	$raw=file_get_contents("tick.txt");
	parse_str($raw);	
	return array("precio"=>$precio, "hora"=>$hora);
}


function updateTick()
{
	global $TICK_UPDATE_SECONDS;
	$data=readTick();
	if ((time()-$data["hora"])>$TICK_UPDATE_SECONDS)
	{
		writeTick();		
	}	
}


function fromTickToPVP($tick)
{
	global $COEFFICIENT_TICK;
	global $MIN_PRICE;
	return max($tick*$COEFFICIENT_TICK,$MIN_PRICE);
}

updateTick();
$data=readTick();

$PVP=$data["precio"];

echo json_encode(array("price"=>$PVP));

?>
