<?php

	require_once ("global_variables.php");
	
	function fromPagosToLim($pagos)
	{
		global $IS_THERE_LIMIT;
		
		if ($IS_THERE_LIMIT)
		{
		
			$lim=limInferiorBTC();
			
			if ($pagos<1)
			{
				$lim=$lim;
			} elseif ($pagos<2)
			{
				$lim=$lim*1.5;
			} elseif ($pagos<3)
			{
				$lim=$lim*3;
			} elseif ($pagos<4)
			{
				$lim=$lim*5;
			} elseif ($pagos<8)
			{
				$lim=$lim*10;
			} elseif ($pagos<10)
			{
				$lim=$lim*15;
			}
			else
			{
				$lim=$lim*20;
			}
			
			return $lim;
		}
		else
		{
			$current_balance=file_get_contents("balance.txt");
			parse_str($current_balance);
			return intval($balance*100);
		}
				
	}
	
	function limInferiorBTC()
	{
		global $USD_MIN_BUY;
		$current_price=file_get_contents("tick.txt");
		parse_str($current_price);
		$lim_inf=round($USD_MIN_BUY/$precio*100,0);
		return $lim_inf;
	
	}
	
	function encrypt($data)
	{		
		global $DB_KEY;
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($DB_KEY), $data, MCRYPT_MODE_CBC, md5(md5($DB_KEY))));
		return $encrypted;
	}
	
	function decrypt($data)
	{
		global $DB_KEY;
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($DB_KEY), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($DB_KEY))), "\0");
		return $decrypted;
	}			
	
	function sanitizeMobile($num)
	{
		
		if ($num[0]==' ')
		{
			$num=substr($num,1);
		}		
		if ($num[0]=='+')
		{
			$num=substr($num,1);
		}		
		if (substr($num,0,2)=='00')
		{
			$num=substr($num,2);
		}
		return $num;
	}
	
	
?>