<?php
	
	require_once("dbfunctions.php");
	require_once("calcfunctions.php");

	
	if (isset($_GET["mobile"]))
	{
		$encrypt_mobile=encrypt(sanitizeMobile($_GET["mobile"]));
		openDB();
		$limit=fromPagosToLim(getPagos($encrypt_mobile));
		closeDB();
		
	} else
	{
		$limit=fromPagosToLim(0);
	}
	
		
	echo json_encode(array("lim"=>$limit,"min"=>limInferiorBTC()));
	

	
?>