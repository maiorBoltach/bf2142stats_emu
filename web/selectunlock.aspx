<?php

/*

selectunlock.aspx?uid=211&auth=tR4r484XsJFRLkCGMBktuQ__ 
/selectunlock.aspx?auth=egDoJjAKsgv4uQaJ0Mzzzg__&uid=311&gsa=123abc456def789ghiABCD__

O
H	result
D	0
$	10	$


*/

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . ": " . $val . "\n";
}
file_put_contents('selectunlock.txt', $str);
//auth=qRyUw3ZNP97C2jeGFJhJ3Q__&uid=311&gsa=PRiCE5WaOm010xMpYD7Tfb__&
//$_GET['auth'] = "qRyUw3ZNP97C2jeGFJhJ3Q__";
//$_GET['uid'] = "311";

$timestamp = time();
$authPIDAvcred = 0;
if(isset($_GET["auth"]) AND $_GET["auth"] != "") {	$auth = $_GET["auth"];	} else {	echo "None: No error.";exit;	}
if(isset($_GET["uid"]) AND $_GET["uid"] != "") {	$uid = $_GET["uid"];	} else {	echo "None: No error.";exit;	}

require_once("ea_support.php");
require_once('include/_ccconfig.php');

$bfcoding  = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($auth)));
if ((hexdec($code[6].$code[7].$code[4].$code[5].$code[2].$code[3].$code[0].$code[1])+708) < $timestamp) {	echo "ExpiredAuth: Expired authentication token";exit;	}
/*
0  4C524F45 7
8  64000000 15
16 9703F104 23
24 0000     27
28 87C3     31
*/
$authPID = hexdec($code[22].$code[23].$code[20].$code[21].$code[18].$code[19].$code[16].$code[17]);

if(isset($_GET["pid"]) AND $_GET["pid"] != "" AND $authPID != $_GET["pid"]) {	echo "Invalid Params";exit;	}
$connection = @mysql_connect($db_host, $db_user, $db_pass);

@mysql_select_db($db_name);
$query1 = "SELECT unavl FROM `playerprogress` WHERE pid='".$authPID."' LIMIT 1";
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1) OR $game_unlocks == 1) {
	errorcode(104);
	exit;
} else {
	$row1 = mysql_fetch_assoc($result1);
	if($row1['unavl'] > 0) {
		list($kit,$tree,$order) = str_split($uid,1);
		$query2 = "SELECT ukit,utree,uorder FROM `unlocks` WHERE pid='".$authPID."' AND ukit='".$kit."' AND utree='".$tree."'";
		$result2 = mysql_query($query2) or die(mysql_error());
		if (mysql_num_rows($result2)) {
			$row2 = mysql_fetch_assoc($result2);
			if ($order <= $row2['uorder'] OR ($order-1) > $row2['uorder']) {
				errorcode(104);
				exit;
			} else {
				$query3 = "UPDATE `unlocks` SET uorder='".$order."' WHERE pid='".$authPID."' AND ukit='".$kit."' AND utree='".$tree."'";
				$result3 = mysql_query($query3) or die(mysql_error());
				$query4 = "UPDATE `playerprogress` SET unavl=(unavl-1), unlc=(unlc+1) WHERE pid='".$authPID."'";
				$result4 = mysql_query($query4) or die(mysql_error());
			}
		} elseif($order == 1) {
			$query3 = "INSERT INTO `unlocks` SET pid='".$authPID."', ukit='".$kit."', utree='".$tree."', uorder='".$order."'";
			$result3 = mysql_query($query3) or die(mysql_error());
			$query4 = "UPDATE `playerprogress` SET unavl=(unavl-1), unlc=(unlc+1) WHERE pid='".$authPID."'";
			$result4 = mysql_query($query4) or die(mysql_error());
		} else {
			errorcode(104);
			exit;
		}
	} else {
		errorcode(104);
		exit;
	}
}

function errorcode($errorcode=104) {
	$Out = "E\t".$errorcode;
	$countOut = preg_replace('/[\t\n]/','',$Out);
	print $Out."\n$\t".strlen($countOut)."\t$\n";
}
?>