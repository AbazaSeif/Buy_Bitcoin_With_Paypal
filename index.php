<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<title>Enter the Hall of Fame of DoNotCompare </title>
	<script src="jquery-1.6.2.min.js"></script>		


	<link rel="stylesheet" type="text/css" href="style.css" />

	<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-21077255-1']);
		_gaq.push(['_setDomainName', '.donotcompare.com']);
		_gaq.push(['_trackPageview']);

		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

	</script>




  
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>


<body>


<div class="container">
<div class="titleblock">
<h1>Welcome to DoNotCompare.com</h1>




<h4>
	Buy units and enter the HALL OF FAME
</h4>




</div>


<div class="content">


  
<h3>
	Follow the discussion at
	<a href="https://bitcointalk.org/index.php?topic=38873.0">
		BitcoinTalk.org
	</a>
</h3>


<font align='left' size='3'>
	Current* price: 1 unit = $ <a id="price"></a><br/> 
	Current Units available: <a id="balance"></a>
</font>
<br/><br/>
<font size=1> 
	*Prices are updated each 30 seconds. The price shown may change. The final price will be given in the next scree
</font>

<h4>
Enter the prestigiuous Hall of Fame of DoNotCompare. Comparisons are odious, and the opportunity to be respected for being in this hall is definitely worth it. Here is how it works:
<br/>
<ol>
	<li>You buy your <u>IMMEDIATE</u> position in the Hall of Fame (min 0.01 units).</li>
	<li>In this Hall, your Bitcoin address will be publicly listed, as well as the amount of units you purchased.</li>
	<li>There will be a link that will lead to http://blockexplorer.com/address/[your_address], and everyone will see how, according to that page, you will have at least the units you payed for (you can see that in the 'Amount' column). Both Blockexplorer and me use the same units. Please, make a unique address for each purchase, since in that way it would be clear that the units came from here.</li>
</ol>

<a href='halloffame.php'> Take a look at it! </a>
<br/> 
</h4>



<h4>		
	
	<?php
	require_once('global_variables.php');
	global $AUTHENTICATION_MOBILE;	
	$AUTHENTICATION_MOBILE=true;
	$identification=null;
	# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
	require_once( 'openid.php');
	require_once('dbfunctions.php');
	require_once('calcfunctions.php');
	try {
		# Change 'localhost' to your domain name.
		$openid = new LightOpenID('btc.donotcompare.com');
		if(!$openid->mode) {
			if(isset($_GET['login'])) {
				$openid->identity = 'https://www.google.com/accounts/o8/id';
				header('Location: ' . $openid->authUrl());
			}
	?>
	<!--	
		<form action="?login" method="post">		
			You can also use Google OpenID (instead of the mobile phone):	<button>Login with Google OpenID</button>
		</form>
	-->
		
	<?php			
		} else {
			$openid->validate();
			$identification=$openid->identity;
			$AUTHENTICATION_MOBILE=false;
		}
	} catch(ErrorException $e) {					
	}

	?>
<form method="POST" name="data" action="validation.php">			
			<fieldset>
				<!-- <legend>Get cBTC (cents of Bitcoin)</legend>	-->
				<legend><h3>Enter the Hall Of Fame</h3></legend>
				<b>Bitcoin Address</b> <input type="text" size="42" name="address" onchange="isValid()"/> <a id="isValid"> </a>
				<br/><font size=2>Please create a NEW address for each purchase and DO NOT reuse it</font>
				<br/>
				<br/>
				<b>cBTC</b> <input type="text" size="4" name="amount" onchange="checkLim()"/> 				
				<br/>Min cBTC: <a id="min">--</a>
				<br/>Max cBTC: <a id="lim">--</a>
				<br/><font size=2> Attention, these are <b>CENTS</b>. Ex: if you want 1 unit type 100 in the case above. This limit is set to avoid fraud. It will be automatically increased with each purchase.</font>
				<br/>
				<br/>
	
				Estimated <b>total</b> price: $
				<a id="estimatedPrice"> --</a>			
				<br/> <font size=2> The price shown may change. The final price will be given in the next screen</font>

				<?php
				if (!$AUTHENTICATION_MOBILE)
				{
					$encrypt_mobile=encrypt($identification);
					$pin=99999;
					
					if (isset($identification))
					{
						openDB();
						if (isNewMobile($encrypt_mobile))				
						{			
							newMobile($encrypt_mobile,$pin);								
						} 												
						updatePin($encrypt_mobile,$pin);
						closeDB();						
					
				?>
						<input type="hidden" name="pin" value="99999"/>
						<input type="hidden" name="mobile" value="<?php echo $identification; ?>"/>
				<?php				
					}					
				}
				else
				{
				?>
					<br/>
					<br/>
					<b>PIN Code</b> <input type="text" size="5" name="pin"/>
					<br/>
					<font size=2> Mobile phone* </font>
					<input type="text" size="18" name="mobile" onchange="getLim()" value="With Country Code!"/>
					<input type="button" id="button" value="Get PIN" onclick="getPin()">
					<font size=2 id="message"> </font>
					<font size=2"> <br/>Don't forget the country code 
					<br/> Your phone number will ONLY be used to send you your PIN code. A encrypted version will be used to count the number of purchases and thus increase the limit.
					</font>
					<br/><br/>								
				<?php				
				}
				?>
				<br/>				
				<div style="text-align:center;">
					<br/>
					<input type="submit" id="submit" value="Continue"> 					
				</div>
				<br/>				
			</fieldset>
		</form>
</h4>

<h4>
If you had any problem with the procedure please <a href="mailto:btc@donotcompare.com">contact me</a> providing the SHA1 of the mobile phone you used (you can do it <a href="http://www.tools4noobs.com/online_php_functions/sha1/"> here</a>) and the last Bitcoin address you used here. It will help if you say the approximate date of your purchase and the last PIN code you received.
		
		<br/>
				<br/> 
				<font size=2> *Your mobile phone as you wrote it will ONLY be used to send your PIN code. A encrypted version will be stored in a database in order to anonymously identify you for your future purchases and increase your limit. Your data WILL NOT be sold to third parties. You WILL NEVER receive any other communication from our part than the PIN you asked for.</font>	

</h4>








</div>







<font size='1'> Design by <a href="http://www.projectaces.com">LS</a> </font>

<br/>



</div>

<script>
		
		/* ***************************** */
			var mobile="";
			
			function getPin()
			{				
				$.ajax
					(
						{						
							url: "getPin.php?mobile="+mobile, 
							success: 
								function(response)
								{
									
									if (response.problems)
									{
										document.getElementById("message").innerHTML=response.message;
									} else
									{
										document.getElementById("message").innerHTML="PIN sent";
									}									
								}, 
							dataType: "json"
						}
					) 	
			}
			
			
			function isValid()
			{			
				address= document.data.address.value;
				var strurl;
				if (address!="")
				{
					str_url="isValidAddress.php?address="+address;
				}
				
				$.ajax
				(
					{						
						url: str_url, 
						success: 
							function(response)
							{
								
								document.getElementById("isValid").innerHTML=response.valid;
							}, 
						dataType: "json"
					}
				) 	
			}
			
			function getLim()
			{
			
				mobile= document.data.mobile.value;								
				var strurl;
				if (mobile=="")
				{
					str_url="getLim.php";
				}else
				{
					str_url="getLim.php?mobile="+mobile;
				}
				
				$.ajax
				(
					{						
						url: str_url, 
						success: 
							function(response)
							{
								
								document.getElementById("lim").innerHTML=response.lim;
								document.getElementById("min").innerHTML=response.min;
							}, 
						dataType: "json"
					}
				)

				
				if (mobile!="With Country Code!")
				{
					$.ajax
					(
						{						
							url: "getCountry.php?mobile="+mobile, 
							success: 
								function(response)
								{				
									if (!response.error)
									{
										document.getElementById("message").innerHTML="You are from "+response.country+". If not, please make sure you wrote the correct country code";
									}
									else
									{
										document.getElementById("message").innerHTML="Invalid number";
									}
								}, 
							dataType: "json"
						}
					)
				}

				
			}
		
		/* ***************************** */
			function checkLim()
			{
				getLim();
				var limit=document.getElementById("lim").innerHTML;
				
			
				if (!(parseInt(document.data.amount.value)<parseInt(limit)))
				{										
					document.data.amount.value=limit;
				}
				
				var value=document.data.amount.value;
				//Check if value if integer
				if (!(parseFloat(value) == parseInt(value)))
				{
					value=parseInt(value);
					document.data.amount.value=value;
				}
								
				if (isNaN(value) || parseInt(value)<parseInt(document.getElementById("min").innerHTML))
				{										
					document.data.amount.value=document.getElementById("min").innerHTML;
				}
				
					
				// Calculate total price				
				var dollars=
				document.getElementById("price").innerHTML*document.data.amount.value*0.01;
				
				document.getElementById("estimatedPrice").innerHTML=Math.round(dollars*100)/100;
			}
		
		/* ***************************** */
		
		getLim();
		
		$.ajax
		(
			{
				url: "getBalance.php", 
				success: 
					function(response)
					{
						
						document.getElementById("balance").innerHTML=response.balance;
					}, 
				dataType: "json"
			}
		) 
		
		
		$.ajax
		(
			{
				url: "getTick.php", 
				success: 
					function(response)
					{
						
						document.getElementById("price").innerHTML=response.price;
					}, 
				dataType: "json"
			}
		) 	

		
		 $(document).ready
		 (
			function() 
			{ 
				setInterval
				(
					function() 
					{ 
					
						$.ajax
						(
							{
								url: "getTick.php", 
								success: 
									function(response)
									{
										
										document.getElementById("price").innerHTML=response.price;
									}, 
								dataType: "json"
							}
						)
						
						$.ajax
						(
							{
								url: "getBalance.php", 
								success: 
									function(response)
									{
										
										document.getElementById("balance").innerHTML=response.balance;
									}, 
								dataType: "json"
							}
						)
					}
						
					,30000) 	
			}
		 );
												
			
			
		</script>
		
		<!-- http://contadores.miarroba.es  -->
		<script type="text/javascript" src="http://contadores.miarroba.es/ver.php?id=654743"></script>
		<!-- http://contadores.miarroba.es  -->
		
	</body>
</html>
