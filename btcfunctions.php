<?php
	
	require_once 'bitcoin.inc';
	require_once 'global_variables.php';
	
	function openBitcoin()
	{
		$bitcoin = new BitcoinClient('https','username','password','btc.microthosting.com','4003');
		return $bitcoin;
	}

	
	function sendBitcoins($address,$cbtc)
	{
				
		$pagar=$cbtc/100;
		
		$bitcoin=openBitcoin();
		
		
		$bitcoin->sendfrom("prueba",$address,floatval($pagar));		

		
				
		$last_balance=$bitcoin->getbalance();
		$last_balance=$last_balance-getReserved();
		
		$fp=fopen("balance.txt","w");
		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			fwrite($fp,"balance=".$last_balance."&hora=".time());
			flock($fp, LOCK_UN); // release the lock
		}		
		fclose($fp);	
	}
	
	function getNewAddress()
	{
		$bitcoin=openBitcoin();
		return $bitcoin->getnewaddress("prueba");
	}
	
	
	
	function buyCoinsMtGox($amount)
	{
		$decoded=mtgox_query('0/data/ticker.php');	
		$price = $cur_sell=$decoded['ticker']['sell'];
		$req=array('amount'=>$amount,'price'=>$price); 
		$decoded=mtgox_query('0/buyBTC.php',$req); 
			
	}
	
	function mtgox_query($path, array $req = array()) 
	{ 
		global $MTGOX_KEY;
		global $MTGOX_SECRET;
	   
		$key=$MTGOX_KEY;
		$secret=$MTGOX_SECRET;
		
		$mt = explode(' ', microtime()); 
		$req['nonce'] = $mt[1].substr($mt[0], 2, 6); 
		$post_data = http_build_query($req, '', '&'); 
		$headers = array( 
					'Rest-Key: '.$key, 
					'Rest-Sign: '.base64_encode(hash_hmac('sha512', $post_data, base64_decode($secret), true)), 
						); 
		static $ch = null; 
		if (is_null($ch)) 
		{ 
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 		
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MtGox PHP client; '.php_uname('s').'; PHP/'.phpversion().')'); 
		} 
		curl_setopt($ch, CURLOPT_URL, 'https://mtgox.com/api/'.$path); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		$res = curl_exec($ch); 
		if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch)); 
		$dec = json_decode($res, true); 
		if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists'); 
		return $dec; 	
	} 
			
?>
