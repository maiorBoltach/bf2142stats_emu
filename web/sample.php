<html>
  <body>
    <form action="sample.php" method="post">
<?php
ini_set('user_agent',"GameSpyHTTP/1.0");
require_once("ea_support.php");

$bfcoding  = &new ea_stats();

$timestamp = '';
@$timestamp = $_REQUEST['timestamp'];
$timestamp = time();

if(isset($_REQUEST['pid']) AND $_REQUEST['pid'] != "") {
	$pid = $_REQUEST['pid'];
} else {
	$pid = '0';
}

$action = '';
@$action = $_REQUEST['action'];

$code = '';
@$code = $_REQUEST['code'];

$auth = '';
@$auth = $_REQUEST['auth'];

if(isset($_REQUEST['clType']) AND $_REQUEST['clType'] != "") {
	$clType = $_REQUEST['clType'];
} else {
	$clType = 0;
}
if(!isset($_REQUEST['server_type']) OR $_REQUEST['server_type'] == "" OR $_REQUEST['server_type'] == "local") {
	$server_type = '86.111.224.14';
} elseif(isset($_REQUEST['server_type']) AND isset($_REQUEST['server_type']) == "stella") {
	$server_type = 'stella.prod.gamespy.com';
}
//----------------------------------------------------
$timestamp_hex = strtoupper(dechex($timestamp));
$timestamp_dword = dwh(dechex($timestamp));
//----------------------------------------------------
$pid_hex = strtoupper(dechex($pid));
$pid_dword = dwh(dechex($pid));
//-----------------------------------------------------
//$auth_ = $timestamp_dword."64000000".$pid_dword."0100";




//'6bb0'      12CE4C4564000000CA4AEF040100


if($action == 'Get Auth Key') {
  $code = $timestamp_dword.dwh(dechex(100)).$pid_dword."0".$clType."00";
  $code.= CalcCRC($code);
  $result = $bfcoding->DefEncryptBlock($bfcoding->hex2str($code));
  $auth = $bfcoding->getBase64Encode($result);
} elseif ($action == 'Get Code (in HEX format)') {
  $result = $bfcoding->getBase64Decode($auth);
  $code = $bfcoding->DefDecryptBlock($result);
  $code = $bfcoding->str2hex($code);
} else {
  $auth = '';
  $code = '';
}
echo '<table border="1"><tr>';
echo '      <td><b>TIMESTAMP:</b></td><td><input type="text" name="timestamp" size="10" maxlength="10" value="'.$timestamp.'"></td><td><b>TIMESTAMP_HEX:</b></td><td>'.$timestamp_hex.'</td><td><b>TIMESTAMP_bWORD:</b></td><td>'.$timestamp_dword.'</td>'."\n";
echo '</tr><tr>';
echo '      <td><b>PID:</b></td><td><input type="text" name="pid" size="10" maxlength="10" value="'.$pid.'"></td><td><b>PID_HEX:</b></td><td>'.$pid_hex.'</td><td><b>PID_bWORD:</b></td><td>'.$pid_dword.'</td>'."\n";
echo '</tr><tr>';
//echo '      <td>&nbsp;</td><td colspan="3"><input type="text" name="code2" size="50" maxlength="32" value="'.$auth_.'"></td><td><input type="submit" name="action" value="2Get Auth Key"></td>'."\n";
//echo '</tr><tr>';
echo '      <td><b>AUTH:</b></td><td colspan="3"><input type="text" name="auth" size="50" maxlength="24" value="'.$auth.'"></td><td><input type="submit" name="action" value="Get Code (in HEX format)"></td><td><input type="checkbox" name="server_type" value="stella.prod.gamespy.com" ';
if ($server_type == "stella.prod.gamespy.com") { echo " checked"; }
echo '>stella</td>'."\n";
echo '</tr><tr>';
echo '      <td><b>CODE:</b></td><td colspan="3"><input type="text" name="code" size="50" maxlength="32" value="'.$code.'"></td><td><input type="submit" name="action" value="Get Auth Key"></td><td><input type="checkbox" name="clType" value="1" ';
if ($clType == 1) { echo " checked"; }
echo '>Server</td>'."\n";
echo '</tr></table>';

//getPageContents();
if($action == 'Get Auth Key') {
	$test = array();
	$test['1']	= 'getawardsinfo.aspx?pid='.$pid.'&auth='.$auth;
	$test['2']	= 'getbackendinfo.aspx?auth='.$auth;
	$test['3']	= 'getleaderboard.aspx?auth='.$auth.'&pos=1&after=17&type=overallscore';
	$test['4a']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=base';
	$test['4b']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=ovr';
	$test['4c']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=award';
	$test['4d']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=ply';
	$test['4e']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=titan';
	$test['4f']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=wrk ';
	$test['4g']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=com ';
	$test['4h']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=wep';
	$test['4i']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=veh';
	$test['4j']	= 'getplayerinfo.aspx?auth='.$auth.'&mode=map';
	$test['5a']	= 'getplayerprogress.aspx?mode=point&scale=game&auth='.$auth;
	$test['5b']	= 'getplayerprogress.aspx?mode=score&scale=game&auth='.$auth;
	$test['5c']	= 'getplayerprogress.aspx?mode=ttp&scale=game&auth='.$auth;
	$test['5d']	= 'getplayerprogress.aspx?mode=kills&scale=game&auth='.$auth;
	$test['5e']	= 'getplayerprogress.aspx?mode=spm&scale=game&auth='.$auth;
	$test['5f']	= 'getplayerprogress.aspx?mode=role&scale=game&auth='.$auth;
	$test['5g']	= 'getplayerprogress.aspx?mode=flag&scale=game&auth='.$auth;
	$test['5h']	= 'getplayerprogress.aspx?mode=waccu&scale=game&auth='.$auth;
	$test['5i']	= 'getplayerprogress.aspx?mode=wl&scale=game&auth='.$auth;
	$test['5j']	= 'getplayerprogress.aspx?mode=twsc&scale=game&auth='.$auth;
	$test['5k']	= 'getplayerprogress.aspx?mode=sup&scale=game&auth='.$auth;
	$test['6']	= 'getunlocksinfo.aspx?auth='.$auth;
	$test['7']	= 'playersearch.aspx?auth='.$auth;
	
	$array_url = array();
	$array_url['stella.prod.gamespy.com'] = $test;
	$array_url['86.111.224.14'] = $test;

	echo '
<table>';
	foreach($array_url[$server_type] as $req) {
		echo '
	<tr><td><a href="http://'.$server_type.'/'.$req.'" target="_blank">http://'.$server_type.'/'.$req.'</a></td></tr>
	<tr><td><pre>'.getPageContents("http://".$server_type."/".$req).'</pre></td></tr>';
	}
	echo '
</table>
';
}

function getPageContents($url){
	// Try file() first
	/*
	if( function_exists('file') && function_exists('fopen') && ini_get('allow_url_fopen') ) {
		//ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
		ini_set("user_agent","GameSpyHTTP/1.0");
		ini_set("auto_detect_line_endings", true);
		$results = @file($url);
	}
	*/
	// either there was no function, or it failed -- try curl
	if( !($results) && (function_exists('curl_exec')) ) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		$sip = '86.111.224.14';
		$sip = '195.140.177.250';
		curl_setopt($curl_handle, CURLOPT_INTERFACE, $sip);
		//curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
		curl_setopt($curl_handle, CURLOPT_USERAGENT, "GameSpyHTTP/1.0");
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
		$results = curl_exec($curl_handle);
		$err = curl_error($curl_handle);
		if( $err != '' ) {
			print "getData(): CURL failed: ";
			print "$err";
			return false;
		}
		//$results = explode("\n",trim($results));
		curl_close($curl_handle);
	}

	if( !$results ) // still nothing, forgetd a'bout it
	return false;

	return $results;
}
?>
    </form>
  </body>
</html>