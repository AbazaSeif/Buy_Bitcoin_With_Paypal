<?php

	require_once("./textmagic-sms-api-php/TextMagicAPI.php");
	require_once("global_variables.php");
	
	
	function existsNumber($number)
	{
		global $USER_API_SMS;		
		global $PASS_API_SMS;
		
		$api = new TextMagicAPI(array(
			"username" => $USER_API_SMS,
			"password" => $PASS_API_SMS
		));

		try{
			$api->checkNumber(array($number));
			return true;
		} catch(Exception $e) 
		{ 
			return false;
		}
	
	}
	
	
	function sendMessage($number,$pin)
	{
	
		global $USER_API_SMS;
		global $PASS_API_SMS;
		global $SMS_TEXT;
	
		$api = new TextMagicAPI(array(
			"username" => $USER_API_SMS,
			"password" => $PASS_API_SMS
		));

		try{
			$text=$SMS_TEXT.$pin;									
			$api->send($text, array($number), true);
			return true;
		} catch(Exception $e) 
		{ 			
			return false;
		}
	
	}

	
	function fromCodeToCountry($code)
	{
		$list=array("AF"=>"Afghanistan",
					"AL"=>"Albania",
					"DZ"=>"Algeria",
					"AS"=>"American Samoa",
					"AD"=>"Andorra",
					"AO"=>"Angola",
					"AI"=>"Anguilla",
					"AG"=>"Antigua and Barbuda",
					"AR"=>"Argentina",
					"AM"=>"Armenia",
					"AW"=>"Aruba",
					"AU"=>"Australia",
					"AT"=>"Austria",
					"AZ"=>"Azerbaijan",
					"BS"=>"Bahamas",
					"BH"=>"Bahrain",
					"BD"=>"Bangladesh",
					"BB"=>"Barbados",
					"BY"=>"Belarus",
					"BE"=>"Belgium",
					"BZ"=>"Belize",
					"BJ"=>"Benin",
					"BM"=>"Bermuda",
					"BT"=>"Bhutan",
					"BO"=>"Bolivia",
					"BA"=>"Bosnia and Herzegovina",
					"BW"=>"Botswana",
					"BR"=>"Brazil",
					"VG"=>"British Virgin Islands",
					"BN"=>"Brunei",
					"BG"=>"Bulgaria",
					"BF"=>"Burkina Faso",
					"BI"=>"Burundi",
					"KH"=>"Cambodia",
					"CM"=>"Cameroon",
					"CA"=>"Canada",
					"CV"=>"Cape Verde",
					"KY"=>"Cayman Islands",
					"CF"=>"Central African Republic",
					"TD"=>"Chad",
					"CL"=>"Chile",
					"CN"=>"China",
					"CO"=>"Colombia",
					"KM"=>"Comoros",
					"CG"=>"Congo - Brazzaville",
					"CK"=>"Cook Islands",
					"CR"=>"Costa Rica",
					"HR"=>"Croatia",
					"CU"=>"Cuba",
					"CY"=>"Cyprus",
					"CZ"=>"Czech Republic",
					"CI"=>"Côte d’Ivoire",
					"DK"=>"Denmark",
					"DJ"=>"Djibouti",
					"DM"=>"Dominica",
					"DO"=>"Dominican Republic",
					"EC"=>"Ecuador",
					"EG"=>"Egypt",
					"SV"=>"El Salvador",
					"GQ"=>"Equatorial Guinea",
					"ER"=>"Eritrea",
					"EE"=>"Estonia",
					"ET"=>"Ethiopia",
					"FK"=>"Falkland Islands",
					"FO"=>"Faroe Islands",
					"FJ"=>"Fiji",
					"FI"=>"Finland",
					"FR"=>"France",
					"GF"=>"French Guiana",
					"PF"=>"French Polynesia",
					"GA"=>"Gabon",
					"GM"=>"Gambia",
					"GE"=>"Georgia",
					"DE"=>"Germany",
					"GH"=>"Ghana",
					"GI"=>"Gibraltar",
					"GR"=>"Greece",
					"GL"=>"Greenland",
					"GD"=>"Grenada",
					"GP"=>"Guadeloupe",
					"GU"=>"Guam",
					"GT"=>"Guatemala",
					"GG"=>"Guernsey",
					"GN"=>"Guinea",
					"GW"=>"Guinea-Bissau",
					"GY"=>"Guyana",
					"HT"=>"Haiti",
					"HN"=>"Honduras",
					"HK"=>"Hong Kong SAR China",
					"HU"=>"Hungary",
					"IS"=>"Iceland",
					"IN"=>"India",
					"ID"=>"Indonesia",
					"IR"=>"Iran",
					"IQ"=>"Iraq",
					"IE"=>"Ireland",
					"IM"=>"Isle of Man",
					"IL"=>"Israel",
					"IT"=>"Italy",
					"JM"=>"Jamaica",
					"JP"=>"Japan",
					"JE"=>"Jersey",
					"JO"=>"Jordan",
					"KZ"=>"Kazakhstan",
					"KE"=>"Kenya",
					"KI"=>"Kiribati",
					"KW"=>"Kuwait",
					"KG"=>"Kyrgyzstan",
					"LA"=>"Laos",
					"LV"=>"Latvia",
					"LB"=>"Lebanon",
					"LS"=>"Lesotho",
					"LR"=>"Liberia",
					"LI"=>"Liechtenstein",
					"LT"=>"Lithuania",
					"LU"=>"Luxembourg",
					"MO"=>"Macau SAR China",
					"MK"=>"Macedonia",
					"MG"=>"Madagascar",
					"MW"=>"Malawi",
					"MY"=>"Malaysia",
					"MV"=>"Maldives",
					"ML"=>"Mali",
					"MT"=>"Malta",
					"MH"=>"Marshall Islands",
					"MQ"=>"Martinique",
					"MR"=>"Mauritania",
					"MU"=>"Mauritius",
					"YT"=>"Mayotte",
					"MX"=>"Mexico",
					"FM"=>"Micronesia",
					"MD"=>"Moldova",
					"MC"=>"Monaco",
					"MN"=>"Mongolia",
					"ME"=>"Montenegro",
					"MS"=>"Montserrat",
					"MA"=>"Morocco",
					"MZ"=>"Mozambique",
					"MM"=>"Myanmar [Burma]",
					"NA"=>"Namibia",
					"NR"=>"Nauru",
					"NP"=>"Nepal",
					"NL"=>"Netherlands",
					"AN"=>"Netherlands Antilles",
					"NC"=>"New Caledonia",
					"NZ"=>"New Zealand",
					"NI"=>"Nicaragua",
					"NE"=>"Niger",
					"NG"=>"Nigeria",
					"NU"=>"Niue",
					"MP"=>"Northern Mariana Islands",
					"NO"=>"Norway",
					"OM"=>"Oman",
					"PK"=>"Pakistan",
					"PW"=>"Palau",
					"PS"=>"Palestinian Territories",
					"PA"=>"Panama",
					"PG"=>"Papua New Guinea",
					"PY"=>"Paraguay",
					"PE"=>"Peru",
					"PH"=>"Philippines",
					"PL"=>"Poland",
					"PT"=>"Portugal",
					"PR"=>"Puerto Rico",
					"QA"=>"Qatar",
					"RO"=>"Romania",
					"RU"=>"Russia",
					"RW"=>"Rwanda",
					"RE"=>"Réunion",
					"KN"=>"Saint Kitts and Nevis",
					"LC"=>"Saint Lucia",
					"PM"=>"Saint Pierre and Miquelon",
					"VC"=>"Saint Vincent and the Grenadines",
					"WS"=>"Samoa",
					"SM"=>"San Marino",
					"SA"=>"Saudi Arabia",
					"SN"=>"Senegal",
					"RS"=>"Serbia",
					"SC"=>"Seychelles",
					"SL"=>"Sierra Leone",
					"SG"=>"Singapore",
					"SK"=>"Slovakia",
					"SI"=>"Slovenia",
					"SB"=>"Solomon Islands",
					"SO"=>"Somalia",
					"ZA"=>"South Africa",
					"KR"=>"South Korea",
					"ES"=>"Spain",
					"LK"=>"Sri Lanka",
					"SD"=>"Sudan",
					"SR"=>"Suriname",
					"SZ"=>"Swaziland",
					"SE"=>"Sweden",
					"CH"=>"Switzerland",
					"SY"=>"Syria",
					"TW"=>"Taiwan",
					"TJ"=>"Tajikistan",
					"TZ"=>"Tanzania",
					"TH"=>"Thailand",
					"TG"=>"Togo",
					"TK"=>"Tokelau",
					"TT"=>"Trinidad and Tobago",
					"TN"=>"Tunisia",
					"TR"=>"Turkey",
					"TM"=>"Turkmenistan",
					"TC"=>"Turks and Caicos Islands",
					"TV"=>"Tuvalu",
					"VI"=>"U.S. Virgin Islands",
					"UG"=>"Uganda",
					"UA"=>"Ukraine",
					"AE"=>"United Arab Emirates",
					"GB"=>"United Kingdom",
					"US"=>"United States",
					"UY"=>"Uruguay",
					"UZ"=>"Uzbekistan",
					"VU"=>"Vanuatu",
					"VE"=>"Venezuela",
					"VN"=>"Vietnam",
					"YE"=>"Yemen",
					"ZM"=>"Zambia",
					"ZW"=>"Zimbabwe",
					"IC"=>"Spain",
					"D2"=>"Dominican Republic 2");
			return $list[$code];
}	

