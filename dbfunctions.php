<?php

	require_once("global_variables.php");
	
	function openDB()
	{
		global $con;
		global $IS_LOCAL;
		
		if ($IS_LOCAL)
		{
			$con = mysql_connect('127.0.0.1', 'root', '');
			if (!$con) 
			{
				die('Could not connect: ' . mysql_error());						
			}	
			mysql_select_db("ventabtc", $con);
		}
		else
		{
		
			$con = mysql_connect('sql.byethost33.org', 'donotcom', 'bd6RI9c5h0');		
			if (!$con) 
			{
				die('Could not connect: ' . mysql_error());						
			}		
			mysql_select_db("donotcom_ventabtc", $con);
		
		}
		
	}
	
	function closeDB()
	{
		global $con;
		mysql_close($con);
	}
	
	function newMobile($mobile,$pin)
	{
		$query = sprintf("INSERT INTO users (mobile, timestamp, pin) VALUES ('%s','%s','%s')",
			mysql_real_escape_string($mobile),
			mysql_real_escape_string(0),
			mysql_real_escape_string($pin)
			);				
		$result=mysql_query($query);
						
	}
		
	function updatePagos($mobile,$pagos_ant)
	{
		
		$query = sprintf("UPDATE users SET pagos='%s',timestamp='%s' WHERE mobile='%s'",
			mysql_real_escape_string($pagos_ant+1),
			mysql_real_escape_string(time()),
			mysql_real_escape_string($mobile)
			);				
		$result=mysql_query($query);
	}
	
	function setAsPayed($pass)
	{		
		$query = sprintf("UPDATE payments SET payed='1' WHERE pass='%s'",
			mysql_real_escape_string($pass)
			);				
		$result=mysql_query($query);
	}
	
	function updateAddress($mobile,$address)
	{
		
		$query = sprintf("UPDATE users SET address='%s' WHERE mobile='%s'",
			mysql_real_escape_string($address),
			mysql_real_escape_string($mobile)
			);				
		$result=mysql_query($query);
	}
	
	function updatePin($mobile,$pin)
	{
		
		$query = sprintf("UPDATE users SET pin='%s' WHERE mobile='%s'",
			mysql_real_escape_string($pin),			
			mysql_real_escape_string($mobile)
			);				
		$result=mysql_query($query);
	}
	
	function getTime($mobile)
	{
		$query = sprintf("SELECT timestamp FROM users WHERE mobile='%s'",
			mysql_real_escape_string($mobile));

		$result=mysql_query($query);
			
		$row = mysql_fetch_assoc($result);
		$time=$row['timestamp'];
		
		if (!(isset($time)))
		{
			$time=0;
		}
						
		return $time;
	}
	
	
	function getLastPinTime($ip)
	{
		$query = sprintf("SELECT timestamp FROM pins WHERE ip='%s'",
			mysql_real_escape_string($ip));

		$result=mysql_query($query);			
		$row = mysql_fetch_assoc($result);
		$time=$row['timestamp'];
		
		if (!(isset($time)))
		{
			$time=0;
		}
						
		return $time;
	}
	
	
	function readPin($mobile)
	{
		$query = sprintf("SELECT pin FROM users WHERE mobile='%s'",
			mysql_real_escape_string($mobile));

		$result=mysql_query($query);
			
		$row = mysql_fetch_assoc($result);
		$pin=$row['pin'];
		
		if (!(isset($pin)))
		{
			$pin=-1;
		}
						
		return $pin;
	}
	
	function getPagos($mobile)
	{
		
		$query = sprintf("SELECT pagos FROM users WHERE mobile='%s'",
			mysql_real_escape_string($mobile));

		$result=mysql_query($query);
			
		$row = mysql_fetch_assoc($result);
		$pagos=$row['pagos'];				
		
		if (!(isset($pagos)))
		{
			$pagos=0;
		}
						
		return $pagos;
	}

	
	function isNewMobile($mobile)
	{
		$query = sprintf("SELECT pagos FROM users WHERE mobile='%s'",
		mysql_real_escape_string($mobile));
		$result=mysql_query($query);			
		$row = mysql_fetch_assoc($result);
		$pagos=$row['pagos'];				
		
		if (!(isset($pagos)))
		{
			return true;
		}
						
		return false;
	
	}
	
	function isScammer($mobile)
	{
		
		$query = sprintf("SELECT scammer FROM users WHERE mobile='%s'",
			mysql_real_escape_string($mobile));

		$result=mysql_query($query);
			
		$row = mysql_fetch_assoc($result);
		$scammer=$row['scammer'];				
		
		if (!(isset($scammer)))
		{
			$scammer=0;
		}
						
		return $scammer;
	}
	

	function getInfoPayment($pass)
	{
		$query = sprintf("SELECT address,cbtc,payed FROM payments WHERE pass='%s'",
			mysql_real_escape_string($pass));		
		$result=mysql_query($query);
		return mysql_fetch_assoc($result);
	}
	
	
	function generateKey($address,$cbtc)
	{
		
		$key=sha1(rand(10000,20000)*time());
		
		$query = sprintf("INSERT INTO payments (address, cbtc, pass, timestamp) VALUES ('%s','%s','%s','%s')",
			mysql_real_escape_string($address),
			mysql_real_escape_string($cbtc),
			mysql_real_escape_string($key),
			mysql_real_escape_string(time())
			);				
		$result=mysql_query($query);	
		return $key;
	}

	function getReserved()
	{
		global $SECONDS_RESERVING_BITCOINS;
		
		openDB();
		$limit_time=time()-$SECONDS_RESERVING_BITCOINS;
		
		#First delete obsolete UNPAID records
		$query = sprintf("DELETE FROM payments WHERE timestamp<'%s' AND payed='0'",
			mysql_real_escape_string($limit_time)
			);				
		$result=mysql_query($query);	
		
		#GET reserved
		
		$query = sprintf("SELECT sum( cbtc ) AS res FROM payments WHERE payed=0");
		$result=mysql_query($query);	
		$result=mysql_fetch_assoc($result);		

		return $result["res"]/100;
	}
	
	
	function deletePayment($key)
	{
		$query = sprintf("DELETE FROM payments WHERE pass='%s'",
			mysql_real_escape_string($key)
			);				
		$result=mysql_query($query);
	}
	
	
	function getAllPayed()
	{
		$query = sprintf("SELECT address,cbtc FROM payments WHERE payed='1'");		
		$result=mysql_query($query);
		$all=array();
		while($row=mysql_fetch_array($result))
		{
			array_push($all,$row);
		}
		return $all;
	}
	
	
	
	function canGetNewPin($ip)
	{
		global $MAX_SMS_IP_DAY;
		
		$query = sprintf("SELECT attempts,timestamp FROM pins WHERE ip='%s'",
			mysql_real_escape_string($ip));
		$result=mysql_query($query);	
		$result=mysql_fetch_assoc($result);	
				
				
		if (!$result)
		{
			$query = sprintf("INSERT INTO pins (ip, timestamp) VALUES ('%s','%s')",
				mysql_real_escape_string($ip),
				mysql_real_escape_string(time())
				);				
			$result=mysql_query($query);
		
			$query = sprintf("SELECT attempts,timestamp FROM pins WHERE ip='%s'",
			mysql_real_escape_string($ip));
			$result=mysql_query($query);	
			$result=mysql_fetch_assoc($result);	
		}
		
		
		if ($result["attempts"]>=$MAX_SMS_IP_DAY)
		{
			if ((time()-$result["timestamp"])>86400)
			{
				$query = sprintf("UPDATE pins SET attempts='1',timestamp='%s' WHERE ip='%s'",
				mysql_real_escape_string(time()),
				mysql_real_escape_string($ip)
				);				
				$result=mysql_query($query);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$query = sprintf("UPDATE pins SET attempts='%s' WHERE ip='%s'",
				mysql_real_escape_string($result["attempts"]+1),
				mysql_real_escape_string($ip)
				);				
				$result=mysql_query($query);
			return true;
		}	
	}
	
		
	function newIngresosRecord($address,$email)
	{
		$query = sprintf("INSERT INTO ingresos (paypalAccount, toAddress, cbtc) VALUES ('%s','%s','%s')",
			mysql_real_escape_string($address),
			mysql_real_escape_string($email),
			mysql_real_escape_string("0")
			);				
		$result=mysql_query($query);	
	}
	
	function getAddressesWaitingForCoins()
	{
		$query = sprintf("SELECT toAddress FROM ingresos WHERE payedCoins='0'");		
		$result=mysql_query($query);
		$all=array();
		while($row=mysql_fetch_array($result))
		{
			array_push($all,$row["toAddress"]);
		}
		return $all;
	}
	
	function setAsReceivedCoins($address,$cbtc)
	{		
		
		$query = sprintf("UPDATE ingresos SET payedCoins='1',cbtc='%s',ReceiveCoinsTime='%s' WHERE toAddress='%s'",			
			mysql_real_escape_string($cbtc),
			mysql_real_escape_string(time()),			
			mysql_real_escape_string($address)
			);					
		$result=mysql_query($query);	
	}
	
	function setAsReceivedMoney($paypal)
	{		
		$query = sprintf("UPDATE ingresos SET payedMoney='1' WHERE paypalAccount='%s'",				
			mysql_real_escape_string($paypal)
			);					
		$result=mysql_query($query);	
	}
	
	function getUsersNotPayedMoney()
	{
		$query = sprintf("SELECT paypalAccount,cbtc FROM ingresos WHERE payedMoney='0' AND payedCoins='1'");		
		$result=mysql_query($query);
		$all=array();
		while($row=mysql_fetch_array($result))
		{
			array_push($all,$row);
		}
		return $all;
	}
	
?>