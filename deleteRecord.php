<?php

	require_once("btcfunctions.php");		
	require_once("dbfunctions.php");
	require_once("global_variables.php");		
	
	$problems=false;
	$error_message="There has been an error. If you believe there is a mistake, please <a href='mailto:$EMAIL'>contact</a>";
	
	
	if (isset($_GET["key"]))
	{
		$pass=$_GET["key"];	
		$encrypt_mobile=$_GET["key2"];
		
		openDB();
						
		$data=getInfoPayment($pass);
		
		$address=$data['address'];
		$cbtc=$data['cbtc'];
		$payed=$data['payed'];		
		
		if (!isset($payed))
		{
			
			echo $error_message;	
			$problems=true;
		}		
		elseif ($payed==1)
		{
			echo $error_message;	
			$problems=true;
		}		
		else
		{
		
			if ((isset($address) and isset($cbtc)))
			{					

				#DELETE RECORD
				deletePayment($pass);
				
				echo "Sorry, your payment could not be authorized. You have not been credited.";
			}
			else 
			{
			
				echo $error_message;
				$problems=true;
			}
									
		}
		
		closeDB();
	}
	else
	
	{	
	
		echo $error_message;
		$problems=true;
	}
	
		
?>