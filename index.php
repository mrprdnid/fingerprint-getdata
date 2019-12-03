<?php

	$IP = "192.168.18.12";
	$Key = "0";

	$flog = fopen("log/". date("Ymd")."_touch.log", "ar+");

	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
		fputs($Connect, "Content-Type: text/xml".$newLine);
		fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
		fputs($Connect, $soap_request.$newLine);
		$buffer = "";
		while($Response = fgets($Connect, 1024)){
			$buffer = $buffer.$Response;
		}
	} else {
		echo "0";
		fwrite($flog, date("H:i:s ")."1. connection error\n");
		exit;
	}

	include("parse.php");

	$buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
	//fwrite($flog, date("H:i:s ")."$buffer\n");
	$buffer = explode("\r\n",$buffer);
	$jml = count($buffer);

	// print_r(count($buffer));exit;

	$data = Parse_Data($buffer[$jml-2],"<Row>","</Row>");
	$id_kartu_member = Parse_Data($data,"<PIN>","</PIN>");

	header("Content-type: text/xml");

	print_r($buffer[$jml-2]);exit;

	foreach ($buffer as $key => $value) {

		print_r($value);
	}
	

?>