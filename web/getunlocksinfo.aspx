<?php 


$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "==" . $val . "&";
}
file_put_contents('getunlocksinfo.txt', $str);

$timestamp = time();
$authPIDAvcred = 0;
//auth=kMKtvZrBVEBeSjGYCgNgQA__
//$_GET['auth'] = "RWwVGQIuIPQatqknkDoeiQ__";

if(isset($_GET["auth"]) AND $_GET["auth"] != "") {	$auth = $_GET["auth"];	} else {	echo "None: No error.";exit;	}

require_once("ea_support.php");
require_once('include/_ccconfig.php');

$bfcoding  = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($auth)));
//echo date(DATE_ATOM, hexdec($code[6].$code[7].$code[4].$code[5].$code[2].$code[3].$code[0].$code[1]))."<br />\n";
//echo date(DATE_ATOM, time())."<br />\n";
if ((hexdec($code[6].$code[7].$code[4].$code[5].$code[2].$code[3].$code[0].$code[1])+1708) < $timestamp) {	echo "ExpiredAuth: Expired authentication token";exit;	}
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
$query1 = "SELECT rnk, subaccount, unavl as avcred, unlc FROM `subaccount` s 
	LEFT JOIN `playerprogress` p ON p.pid=s.id
WHERE s.id='".$authPID."' LIMIT 1";
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1) AND $game_unlocks != 1) {
	errorcode(104);
	exit;
} else {
	if ($row1['rnk'] > ($row1['avcred']+$row1['unlc'])) {
		$row1['avcred'] = (($row1['rnk']-$row1['unlc']-$row1['unavl'])+$row1['unavl']);
		$query9 = "UPDATE `playerprogress` SET unavl='".$row1['avcred']."' WHERE pid='".$authPID."'";
		$result9 = mysql_query($query9) or die(mysql_error());
	}
	$row1 = mysql_fetch_array($result1);
	$authPIDNick = rawurldecode($row1['subaccount']);
	$authPIDAvcred = $row1['avcred'];
}


if ($game_unlocks == 0) {
	$Out = "O
H\tpid\tnick\tasof
D\t".$authPID."\t".$authPIDNick."\t".$timestamp."
H\tAvcred
D\t".$authPIDAvcred."
H\tUnlockID";
	$query2 = "SELECT * FROM `unlocks` WHERE pid='".$authPID."'";
	$result2 = mysql_query($query2) or die(mysql_error());
	if (mysql_num_rows($result2)) {
		while ($row2 = mysql_fetch_array($result2)) {
			$Out .= "\nD\t".$row2['ukit'].$row2['utree'].$row2['uorder'];
		}
	}
} elseif ($game_unlocks == 1){
	$Out = "O
H\tpid\tnick\tasof
D\t".$authPID."\t".$authPIDNick."\t".$timestamp."
H\tAvcred
D\t0
H\tUnlockID";
	$Out .= "
D\t115
D\t125
D\t215
D\t225
D\t315
D\t325
D\t415
D\t425
D\t516
D\t524";
} else {
	$Out = "O
H\tpid\tnick\tasof
D\t".$authPID."\t".$authPIDNick."\t".$timestamp."
H\tAvcred
D\t".$authPIDAvcred."
H\tUnlockID";
	
}
$countOut = preg_replace('/[\t\n]/','',$Out);
print $Out."\n$\t".strlen($countOut)."\t$\n";
@mysql_close($connection);
function errorcode($errorcode=104) {
	$Out = "E\t".$errorcode;
	$countOut = preg_replace('/[\t\n]/','',$Out);
	print $Out."\n$\t".strlen($countOut)."\t$\n";
} 
?>