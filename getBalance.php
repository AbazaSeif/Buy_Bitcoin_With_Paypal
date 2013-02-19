<?php
	

require_once ("global_variables.php");	
require_once("btcfunctions.php");
require_once("dbfunctions.php");
	
function writeBalance()
{
	
	$bitcoin=openBitcoin();
	$last_balance=$bitcoin->getbalance();	
	$last_balance=$last_balance-getReserved();
	
	$fp=fopen("balance.txt","w");
	if (flock($fp, LOCK_EX)) { // do an exclusive lock
		fwrite($fp,"balance=".$last_balance."&hora=".time());
		flock($fp, LOCK_UN); // release the lock
	}		
	fclose($fp);
	
}

function readBalance()
{
	$raw=file_get_contents("balance.txt");
	parse_str($raw);
	return array("balance"=>$balance, "hora"=>$hora);
}


function updateBalance()
{
	global $BALANCE_UPDATE_SECONDS;
	$data=readBalance();
	if ((time()-$data["hora"])>$BALANCE_UPDATE_SECONDS)
	{
		writeBalance();		
	}	
}

updateBalance();
$data=readBalance();
echo json_encode(array("balance"=>$data["balance"]));
?>