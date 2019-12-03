<?php

	$IP = "192.168.18.12";
	$Key = "0";

	$id_finger = '35';
	$flog = fopen("log/".date("Ymd")."_usersearch.log", "ar+");
	
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">$id_finger</PIN></Arg></GetUserInfo>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response = fgets($Connect, 1024)){
			$buffer = $buffer.$Response;
		}
		
	} else {
		echo "0";
		fwrite($flog, date("H:i:s ")."connection error\n");
	}
	
	include("parse.php");
	$buffer = Parse_Data($buffer,"<GetUserInfoResponse>","</GetUserInfoResponse>");

	header("Content-type: text/xml");

	print_r($buffer);exit;
	
	$buffer1 = explode("\r\n",$buffer);
	if(count($buffer1[0]) > 0){
		fwrite($flog, date("H:i:s ")."$buffer\n");
		
		$data = Parse_Data($buffer1[1],"<Row>","</Row>");
		$PIN = Parse_Data($data,"<PIN>","</PIN>");
		$PIN2 = Parse_Data($data,"<PIN2>","</PIN2>");
		
		if($PIN2 != ''){
			echo $PIN2;
		} else {
			echo "0";
		}
	}
	

?>