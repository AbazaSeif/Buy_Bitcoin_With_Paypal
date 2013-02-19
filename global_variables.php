<?php

	$IS_LOCAL=false;
	$IS_REAL_PAYPAL=true;
	$IS_THERE_LIMIT=false;		
	
	$SECONDS_RESERVING_BITCOINS=10000;
	
	$SECONDS_BETWEEN_PURCHASES=10;
	$BALANCE_UPDATE_SECONDS=120;
	$TICK_UPDATE_SECONDS=30;
	$EMAIL="youremailhere@yourdomainhere.com";
	$COEFFICIENT_TICK=1.4;
	$MIN_PRICE=0;	
	$MAX_SMS_IP_DAY=3;
	$DB_KEY="DB_KEY";

	$MTGOX_KEY='YOUR_MTGOX_KEY';
	$MTGOX_SECRET='YOUR_MTGOX_SECRET';
	$MTGOX_FEE=0.0053;
	
	$USER_API_SMS="SMS_API_USER";
	$PASS_API_SMS="SMS_API_PASS";
	$SMS_TEXT="Thanks for buying at DoNotCompare. Here is your PIN to continue your purchase: ";	
	
	#Minimum USD to spend in the site (to avoid high Paypal fees)
	$USD_MIN_BUY=5.3;
	
	# These IFs select the credentials if you are online/offline and if you are using the actual Paypal account or the sandboxed version for testing
	
	if ($IS_REAL_PAYPAL)
	{			
			$PAYPAL_SELLER = "YOURPAYPAL@EMAIL.COM";
			$API_UserName = "YOURPAYPALAPIUSERNAME";
			$API_Password = "YOURPAYPALAPIPASS";
			$API_Signature = "YOURPAYPALAPISIGNATURE";
			$API_AppID = "YOURAPIAPPID";
			
			if ($IS_LOCAL)
			{
				$MY_CERTIFICATE="./paypal/my-pubcert.pem";
				$MY_PRIVATE_KEY="./paypal/my-prvkey.pem";
				$VENDOR_ID_PAYPAL="VENDORIDPAYPAL";
				$PAYPAL_CERTIFICATE="./paypal/paypal_cert_real.pem";
				$PAYPALEWP="./paypal/paypalewp.php";
			}
			else
			{
				$MY_CERTIFICATE="../../paypal/my-pubcert.pem";
				$MY_PRIVATE_KEY="../../paypal/my-prvkey.pem";
				$VENDOR_ID_PAYPAL="VENDORIDPAYPAL";
				$PAYPAL_CERTIFICATE="../../paypal/paypal_cert_real.pem";				
				$PAYPALEWP="../../paypal/paypalewp.php";
			}	
	}
	else
	{			
			$PAYPAL_SELLER = "PAYPALSELLER_SANDBOXACCOUNT@EMAIL.COM";
			$API_UserName = "FOR_SANDBOXED_PAYPAL_TEST_ONLY";
			$API_Password = "FOR_SANDBOXED_PAYPAL_TEST_ONLY";
			$API_Signature = "FOR_SANDBOXED_PAYPAL_TEST_ONLY";
			$API_AppID = "FOR_SANDBOXED_PAYPAL_TEST_ONLY";
			
			if ($IS_LOCAL)
			{
				$MY_CERTIFICATE="./paypal/my-pubcert.pem";
				$MY_PRIVATE_KEY="./paypal/my-prvkey.pem";
				$VENDOR_ID_PAYPAL="FOR_SANDBOXED_PAYPAL_TEST_ONLY";
				$PAYPAL_CERTIFICATE="./paypal/paypal_cert.pem";	
				$PAYPALEWP="./paypal/paypalewp.php";
			}
			else
			{
				$MY_CERTIFICATE="../../paypal/my-pubcert.pem";
				$MY_PRIVATE_KEY="../../paypal/my-prvkey.pem";
				$VENDOR_ID_PAYPAL="FOR_SANDBOXED_PAYPAL_TEST_ONLY";
				$PAYPAL_CERTIFICATE="../../paypal/paypal_cert.pem";				
				$PAYPALEWP="../../paypal/paypalewp.php";
			}	
	}
	

		
?>
