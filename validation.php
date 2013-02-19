<?php
		
	require_once("global_variables.php");
	require_once("dbfunctions.php");
	require_once("calcfunctions.php");
	require_once("btcfunctions.php");
	require_once($PAYPALEWP);
		
	openDB();
	if (!anyProblem())
	{		
		$pinDB=readPin($encrypt_mobile);		
		if ($pinDB==$pinSent)
		{
		
			#Force update balance
			$bitcoin=openBitcoin();
			$last_balance=$bitcoin->getbalance();	
			$last_balance=$last_balance-getReserved();
			
			if ($last_balance>($amount/100))
			{				
				$last_balance=$last_balance-$amount/100;			
				$fp=fopen("balance.txt","w");
				if (flock($fp, LOCK_EX)) { // do an exclusive lock
					fwrite($fp,"balance=".$last_balance."&hora=".time());
					flock($fp, LOCK_UN); // release the lock
				}		
				fclose($fp);
				
				
				#GENERATE KEY
				$key=generateKey($address,$amount);
				
				
				#SHOW FORM
				print_form();
								
			}
			else
			{
				echo "Not enough BTC to sell. Please keep your PIN, since you will need it for your next purchase";
			}
			
		} else
		{
			echo "Incorrect PIN";
		}
		
		
	}

	closeDB();

	
	
# *************************************************** #	
# *************************************************** #	
# *************************************************** #

	function print_form ()						
	{	
	
		global $amount;
		global $address;
		global $mobile;
		global $key;
		global $encrypt_mobile;
		global $MY_CERTIFICATE;
		global $MY_PRIVATE_KEY;
		global $VENDOR_ID_PAYPAL;
		global $PAYPAL_CERTIFICATE;
		global $IS_LOCAL;
		global $IS_REAL_PAYPAL;					
		
		$BTC=round($amount/100,2);

		$current_price=file_get_contents("tick.txt");
		parse_str($current_price);		
		$totalToPay=round($precio*$BTC,2);
		
		
		$itemshown="phone number/Google Id";
			
		
		echo "		
			<fieldset>
				<legend>Get into the DoNotCompare's Hall of Fame</legend>
				<br/>
				Please confirm your data and click on 'Buy Now'. The purchase will be through Paypal. I have no access to your financial data. Once finished payment, please let Paypal automatically redirect you to this page in order to execute the order.
				<br/> <br/> 
				<b>
				IMPORTANT NOTE: Your $itemshown will be shown in Paypal's receipt. Think twice before proceeding if you are not the owner of the account. <br/> Your $itemshown will NEVER be shown in the Hall of Fame.
				</b>
				<br/> <br/> 
				Bitcoin Address: $address
				<input type='hidden' size='42' name='address'/>
				<br/>
				Amount of units: $amount cents ($BTC Units)				
				<br/>
				Unit price: $ $precio				
				<br/>
				Total price: $ ".$totalToPay."				
				<br/><br/>			
		";
		
		# Load PayPal API
		$paypal = new PayPalEWP();
		$paypal->setTempFileDirectory("/tmp");
		
		$paypal->setCertificate($MY_CERTIFICATE, $MY_PRIVATE_KEY);				
		$paypal->setCertificateID($VENDOR_ID_PAYPAL);				
		$paypal->setPayPalCertificate($PAYPAL_CERTIFICATE);
		
		if ($IS_LOCAL)
		{
			$url_first_part="http://127.0.0.1/VentaBTC";			
		}
		else
		{
			$url_first_part="http://www.donotcompare.com/btc";
		}
		
		if ($IS_REAL_PAYPAL)
		{
			$mail_paypal="EMAI@mail.com";
		}
		else
		{
			$mail_paypal="email@mail.comm";
		}
		
	
		if (strlen($mobile)>15)
		{
			$idnumber="Google ID: ".substr($mobile, 38);
		}
		else
		{
			$idnumber="Phone: 00".$mobile;
		}
		$itemname="Position in Hall of Fame.Address $address. Units: $BTC. $idnumber";			
		
		
		$parameters = array("cmd" => "_xclick",
		    "business" => "$mail_paypal",
		    "item_name" => "$itemname",
		    "amount" => "$totalToPay",
		    "no_shipping" => "1",
			"return" => $url_first_part."/executeOrder.php?key=".$key."&key2=".urlencode($encrypt_mobile),
		    "cancel_return" => $url_first_part."/deleteRecord.php?key=".$key."&key2=".urlencode($encrypt_mobile),					    
		    "no_note" => "1",
		    "currency_code" => "USD",
			"lc" => "GB"
			);

		
		#var_dump($parameters);
		
		$text=$paypal->encryptButton($parameters);
		#var_dump($text);
		
		if ($IS_REAL_PAYPAL)
		{
			echo "
			  <form method='post' name='Download' id='Download' action='https://www.paypal.com/cgi-bin/webscr' class=''>
			  <input type='hidden' name='cmd' value='_s-xclick'>  
			  <input type='image' src='https://www.paypal.com/en_US/i/btn/x-click-but23.gif' border='0' name='submit' alt='Make payments with PayPal - it\'s fast, free and secure!'>
			  <input type='hidden' name='encrypted' value='$text'>
			  </form>
			  </fieldset>		
			";				
		}
		else
		{		
			echo "
			  <form method='post' name='Download' id='Download' action='https://www.sandbox.paypal.com/cgi-bin/webscr' class=''>
			  <input type='hidden' name='cmd' value='_s-xclick'>  
			  <input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/x-click-but23.gif' border='0' name='submit' alt='Make payments with PayPal - it\'s fast, free and secure!'>
			  <input type='hidden' name='encrypted' value='$text'>
			  </form>
			  </fieldset>		
			";		
		}
	
		
	}


	function anyProblem()
	{	
		global $SECONDS_BETWEEN_PURCHASES;
		$problems=false;
		global $encrypt_mobile;
		global $pagos;
		global $address;
		global $amount;
		global $pin;
		global $pinSent;
		global $mobile;
		
		$amount=intval($_POST["amount"]);
		
		if (
			!isset($_POST["mobile"]) or !isset($_POST["amount"]) or
			!isset($_POST["address"]) or !isset($_POST["pin"])			
			) 
			{ 
				$problems=true;
			}
		elseif (!(is_numeric($_POST["amount"]))) 
		{ 
			echo "Amount not a valid number";
			$problems=true;
		}
		elseif ($_POST["pin"]<100)
		{ 
			echo "PIN not a valid number";
			$problems=true;
		}
		else
		{
			$pinSent=$_POST["pin"];
			$mobile=sanitizeMobile($_POST["mobile"]);
			$encrypt_mobile=encrypt($mobile);
			
			$amount=intval($_POST["amount"]);			
			$pagos=getPagos($encrypt_mobile);	
			$address=$_POST["address"];
			
			if ($amount>fromPagosToLim($pagos) or $amount<limInferiorBTC())
			{
				# SI INTENTA COMPRAR MAS DEL MAXIMO
				
				echo "You are not allowed to buy such amount. Your limit is ".fromPagosToLim($pagos)." cBTC";
				$problems=true;
			} 
			elseif (!($amount>0))
			{
				#SI FUERZA UNA CANTIDAD ENTERA NEGATIVA O NULA
				echo "Please select a positive amount";
				$problems=true;
			}
			elseif ((time()-getTime($encrypt_mobile))<$SECONDS_BETWEEN_PURCHASES)
			{
				#SI INTENTA COMPRAR ANTES DE QUE PASE EL TIEMPO MIN
				$seconds=$SECONDS_BETWEEN_PURCHASES-(time()-getTime($encrypt_mobile));
				$h=intval($seconds/3600);
				$m=intval($seconds/60)-60*$h;
				$s=$seconds-60*$m-3600*$h;
				echo "In order to prevent fraud you have to wait ".$h." hours, ".$m." minutes and ".$s." seconds to your next purchase. Please keep your PIN, you will need it.";			
				$problems=true;
			}
			elseif (isScammer($encrypt_mobile))
			{
				#IF HE HAS BEEN FLAGGED AS SCAMMER
				echo "Due to conflicts with recent purchases we have decided to limit your access";
				$problems=true;
				
			}								
		}
			
		return $problems;
	}
		

?>
