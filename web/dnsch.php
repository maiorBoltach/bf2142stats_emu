<?php
$allow_db_changes = true;
$allow_db_show = true;

//set_time_limit(0);
//ignore_user_abort(true);

require('include/utils.php');
$cfg = new Config();
DEFINE("_ERR_RESPONSE","E\nH\tresponse\nD\t<font color=\"red\">ERROR</font>: ");

/*
if (!checkIpAuth($cfg->get('game_hosts'))) {
	$errmsg = "Unauthorised Access Attempted! (IP: " . $_SERVER['REMOTE_ADDR'] . ")";
	ErrorLog($errmsg, 0);
	die(_ERR_RESPONSE.$errmsg);
}
*/
$db_user = 'gs_dnsname';
$db_pass = '';
$db_name = 'gamespy';
$connection = @mysql_connect($db_host, $db_user, $db_pass);
@mysql_select_db($db_name, $connection);



$query = "SELECT ip, dnshost, gport, port FROM servers WHERE bydnsname=1";
$result = mysql_query($query);
checkSQLResult ($result, $query);

if (!mysql_num_rows($result)) {
//	ErrorLog("Player (".$data["pid_$x"].") not found in `subaccounts`.",3);
	exit;
}

while ($data = mysql_fetch_assoc($result)) {
	$newip = "";
	$newip = gethostbyname($data['dnshost']);
  if ($newip != $data['dnshost']) {
	  if ($newip == $data['ip']) {
	  	//ErrorLog("NOT CHANGE ".$data['dnshost']." == ".$data['ip'],3);
	  } else {
	  	$query1 = "SELECT id FROM servers WHERE ip='".$newip."' AND gport='".$data['gport']."' AND port='".$data['port']."'";
	  	$result1 = mysql_query($query1);
	  	checkSQLResult ($result1, $query1);
	  	if (mysql_num_rows($result1)) {
		  	while ($data1 = mysql_fetch_assoc($result1)) {
		  		$query1a = "DELETE FROM servers WHERE id='".$data1['id']."'";
		  		$result1a = mysql_query($query1a);
		  		checkSQLResult ($result1a, $query1a);
		  	}
	  	}
		  ErrorLog("CHANGE ".$data['dnshost']." == ".$data['ip']." => ".$newip,3);
		  $query2 = "UPDATE servers VALUE SET ip='".$newip."' WHERE dnshost='".$data['dnshost']."'";
		  $result2 = mysql_query($query2);
		  checkSQLResult ($result2, $query2);
	  }
  }
}
?>