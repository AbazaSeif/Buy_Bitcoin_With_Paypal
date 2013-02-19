<?php

	require_once("global_variables.php");
	require_once("dbfunctions.php");		
	require_once("smsfunctions.php");			
	require_once("calcfunctions.php");				
	
	$pin="";
	$message="";
	$problems=true;
		
	$encrypt_ip=encrypt($_SERVER["REMOTE_ADDR"]);
	
	if (isset($_GET["mobile"]))
	{
		$mobile=sanitizeMobile($_GET["mobile"]);		
		$encrypt_mobile=encrypt($mobile);
		
		$pin=rand(10000, 99999);		
		
		$problems=false;
		
		openDB();
									
		if(isScammer($encrypt_mobile))
		{
			$message="You are not allowed to buy here anymore";
			$problems=true;
		}			
		elseif (readPin($encrypt_mobile)<>-1)
		{				
			$message= "Your PIN has already been sent. Use the same of your last purchase. If you did not receive it, please <a href='mailto:$EMAIL'> contact </a>";
			$problems=true;
		} 
		elseif (invalidNumber($mobile))
		{				
			$message= "Invalid number";
			$problems=true;
		}
		
		elseif (isNewMobile($encrypt_mobile))
		
		{			
			newMobile($encrypt_mobile,$pin);
			
		} 
		
		if (!$problems)
		{
			if (canGetNewPin($encrypt_ip))
			{	
				if (sendMessage($mobile,$pin))
				{
					updatePin($encrypt_mobile,$pin);
				}
				else
				{
					$message="Problem sending you SMS";
					$problems=true;
				}
			}
			else
			{
				$seconds=86400-(time()-getLastPinTime($encrypt_ip));
				$h=intval($seconds/3600);
				$m=intval($seconds/60)-60*$h;
				$s=$seconds-60*$m-3600*$h;
				$message= "Please wait ".$h." hours, ".$m." minutes and ".$s." seconds before asking for more PINs";
				$problems=true;
			}
		}
		
		closeDB();
	
	} else
	
	{
		
		$message="Invalid number";
		$problems=true;
	}	
		
							
	
	
	
	echo json_encode(array("problems"=>$problems, "message"=> $message));
	
	
	function invalidNumber($num)
	{
		$res=false;
		if (strlen($num)<3 or !existsNumber($num))
		{
			$res=true;
		}
		return $res;
	}
	
?>			