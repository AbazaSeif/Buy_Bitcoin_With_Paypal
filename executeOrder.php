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
			echo "You have already been payed. If you believe there is a mistake, please <a href='mailto:$EMAIL'>contact</a>";
		}		
		else
		{
		
			if ((isset($address) and isset($cbtc)))
			{					

				#SEND Bitcoins
				openBitcoin();				
				sendBitcoins($address,$cbtc);							
				setAsPayed($pass);
				
				#BUY Bitcoins en MtGox
				buyCoinsMtGox(floatval($cbtc/100*(1+$MTGOX_FEE)));
				
				# UPDATE DATA
				updateAddress($encrypt_mobile,$address);
				updatePagos($encrypt_mobile,getPagos($encrypt_mobile));
				#updatePin($encrypt_mobile,-1);				
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
	
	if(!$problems)
	{
		$btc=$cbtc/100;
		echo"
		The payment has succeeded. Your address $address will show the amount of $btc units in the Hall of Fame.<br/>
		<br/> You will receive an email from Paypal with payment confirmation. Thank you for using this service.
		";
	}
		
?>