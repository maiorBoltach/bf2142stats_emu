<?php

$timestamp = time();
$version = 115;

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('clearranknotification.txt', $str);
//$_GET['auth'] = "2kug7VmGH5Tn5kUda1dNqw__";

if (isset($_GET["auth"]) AND $_GET["auth"] != "") {
    $auth = $_GET["auth"];
} else {
    echo "None: No error.";
    exit;
}


require_once("ea_support.php");
require_once('include/_ccconfig.php');

$bfcoding = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($auth)));
if ((hexdec($code[6] . $code[7] . $code[4] . $code[5] . $code[2] . $code[3] . $code[0] . $code[1]) + 708) < $timestamp) {
    echo "ExpiredAuth: Expired authentication token";
    exit;
}
/*
  0  4C524F45 7
  8  64000000 15
  16 9703F104 23
  24 0000     27
  28 87C3     31
 */
$authPID = hexdec($code[22] . $code[23] . $code[20] . $code[21] . $code[18] . $code[19] . $code[16] . $code[17]);
if (hexdec($code[26] . $code[27] . $code[24] . $code[25]) == 1) {
    $clType = 'server';
    $isServer = true;
} else {
    $clType = 'client';
    $isServer = false;
}

$connection = @mysql_connect($db_host, $db_user, $db_pass);

@mysql_select_db($db_name);

$query1 = "SELECT id FROM `subaccount` WHERE id='" . $authPID . "' LIMIT 1";
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1)) {
    errorcode(104);
    exit;
}

$query2 = "SELECT rnkcg FROM `playerprogress` WHERE pid='" . $authPID . "' LIMIT 1";
$result2 = mysql_query($query2) or die(mysql_error());
$row2 = mysql_fetch_assoc($result2);
if ($row2['rnkcg'] > 0) {
    $query3 = "UPDATE playerprogress SET rnkcg = 0  WHERE pid='" . $authPID . "' LIMIT 1";
    $result3 = mysql_query($query3) or die(mysql_error());   
    exit; // !!!fixme!!!
} elseif ($row2['rnkcg'] == 0) {
    $Out = "O\n" .
            "H\tresult\tasof\n" .
            "D\t0\t" . $timestamp . "\n" .
            "H\tversion\n" .
            "D\t" . $version . "\n";
} else {
    errorcode(104);
    exit;
}
$countOut = preg_replace('/[\t\n]/', '', $Out);
print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
@mysql_close($connection);

function errorcode($errorcode=104) {
    $Out = "E\t" . $errorcode;
    $countOut = preg_replace('/[\t\n]/', '', $Out);
    print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
}

?>