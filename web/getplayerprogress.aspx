<?php

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('getplayerprogress.txt', $str);
//auth=nhZmH7Y40dG3y1zlzeChuw__&mode=point&scale=game&gsa=]4X9k3ZC8ROSg9QjBai6Ud__&
//$_GET['auth'] = "WA5n0yAf]7Qudv[QMwBFiA__";
//$_GET['mode'] = "role"; 
//$_GET['scale'] = "game"; 
//exit;
$timestamp = time();
if(isset($_GET["auth"]) AND $_GET["auth"] != "") {	$getAUTH = $_GET["auth"];	} else {	echo "None: No error.";exit;	}
if(isset($_GET["mode"]) AND $_GET["mode"] != "" AND ($_GET["mode"] == "point" OR
$_GET["mode"] == "score" OR
$_GET["mode"] == "ttp" OR
$_GET["mode"] == "kills" OR
$_GET["mode"] == "spm" OR
$_GET["mode"] == "role" OR
$_GET["mode"] == "flag" OR
$_GET["mode"] == "waccu" OR
$_GET["mode"] == "wl" OR
$_GET["mode"] == "twsc" OR
$_GET["mode"] == "sup")) {	$mode = $_GET["mode"];	} else {	errorcode(999);exit;	}
if(isset($_GET["scale"]) AND $_GET["scale"] != "") {	$scale = $_GET["scale"];	} else {	errorcode(999);exit;	}

require_once("ea_support.php");
require_once('include/_ccconfig.php');

$bfcoding  = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($getAUTH)));
if ((hexdec($code[6].$code[7].$code[4].$code[5].$code[2].$code[3].$code[0].$code[1])+708) < $timestamp) {	echo "ExpiredAuth: Expired authentication token";exit;	}
/*
0  4C524F45 7
8  64000000 15
16 9703F104 23
24 0000     27
28 87C3     31
*/
$authPID = hexdec($code[22].$code[23].$code[20].$code[21].$code[18].$code[19].$code[16].$code[17]);
if(isset($_GET["pid"]) AND $_GET["pid"] != "" AND $authPID != $_GET["pid"]) {	$authPID = $_GET["pid"];	}
$connection = @mysql_connect($db_host, $db_user, $db_pass);

@mysql_select_db($db_name);

$col_array = array(
"point" => "date,points,globalscore,experiencepoints,awaybonus",
"score" => "date,score",
"ttp" => "date,ttp",
"kills" => "date,kpm,dpm",
"spm" => "date,spm",
"role" => "date,cotime,sltime,smtime,lwtime,ttp",
"flag" => "date,captures,assist,defend",
"waccu" => "date,waccu",
"wl" => "ate,wins,losses",
"twsc" => "date,twsc",
"sup" => "date,hls,rps,rvs,resp",
);

switch ($mode) {
	case "point":
		$query1 = "SELECT a._lgdt AS date,(a._gsco+a._crpt) AS points, a._gsco AS globalscore,a._crpt AS experiencepoints, awaybonus 
                    FROM `stats_a` a
                    LEFT JOIN playerprogress p ON p.pid = a.pid
                    WHERE a.pid='".$authPID."' ORDER BY a._date DESC LIMIT 20";                
	break;
	case "score":
		$query1 = "SELECT _lgdt AS date, _gsco AS score FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "ttp":
		$query1 = "SELECT _lgdt AS date, _ttp AS ttp FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";            
	break;
	case "kills":
		$query1 = "SELECT _lgdt AS date, _kpm AS kpm, _dpm AS dpm  FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "spm":
		$query1 = "SELECT _lgdt AS date, _spm AS spm FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "role":
		$query1 = "SELECT _lgdt AS date, _tac AS cotime, _tasl AS sltime, _tasm AS smtime, _talw AS lwtime, _ttp AS ttp FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "flag":
		$query1 = "SELECT _lgdt AS date, _cpt AS captures, _capa AS assist, _dcpt AS defend FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "waccu":
		$query1 = "SELECT _lgdt AS date, _ovaccu AS waccu FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "wl":
		$query1 = "SELECT _lgdt AS date, _wins AS wins, _losses AS losses FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "twsc":
		$query1 = "SELECT _lgdt AS date, _twsc as twsc FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
	case "sup":
		$query1 = "SELECT _lgdt AS date, _hls AS hls, _rps AS rps, _rvs AS rvs, _resp AS resp FROM `stats_a` WHERE pid='".$authPID."' LIMIT 20";
	break;
}
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1)) {
	errorcode(104);
	exit;
} else {
	$array_fields = explode(",", $col_array[$mode]);
	$Out = "O
H\tpid\tasof
D\t".$authPID."\t".$timestamp."
H";
	foreach($array_fields as $field) {
		$Out .= "\t".$field;
	}
	while ($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
		$Out .= "\nD";
		foreach ($row1 as $value) {
			$Out .= "\t".$value;
		}
	}
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