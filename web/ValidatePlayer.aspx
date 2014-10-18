<?php
$timestamp = time();
$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('ValidatePlayer.txt', $str);

//auth=MVI[zJuk3ZC8l2]4rVIvj5__&tid=0&SoldierNick=Sinthetix&pid=5&
//$_GET['auth'] = "MVI[zJuk3ZC8l2]4rVIvj5__";
//$_GET['SoldierNick'] = "Sinthetix";
//$_GET['pid'] = "5";

if (isset($_GET["auth"]) AND $_GET["auth"] != "") {
    $auth = $_GET["auth"];
} else {
    echo "None: No error.";
    exit;
}
$nick = $_GET['SoldierNick'];
$pid = $_GET['pid'];

require_once("ea_support.php");
require_once('include/_ccconfig.php');
require_once ('include/rankSettings.php');


//O 
//H pid nick spid asof 
//D 82490978 Bigbacon 82490978 1310658026 
//H result 
//D Ok $ 62 $ 
//echo "DecryptionFailure: Authentication token decryption failure";
//die;
$Out = "O\n" .
 "H\tpid\tnick\tspid\tasof\n" .
 "D\t".$pid."\t".$nick."\t".$pid."\t". $timestamp."\n".
 "H\tresult\n".
 "D\tOk";

$countOut = preg_replace('/[\t\n]/', '', $Out);
print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
//file_put_contents('ValidatePlayer2.txt', $Out . "\n$\t" . strlen($countOut) . "\t$\n");