<?php

// No Direct Access
defined( '_BF2142_ADMIN' ) or die( 'Restricted access' );

// Build Data Table Array
$DataTables = array('army','awards','kills','kits','mapinfo','maps','player','player_history','round_history','servers','unlocks','vehicles','weapons','data','stats');


// Do Tasks
$task = $_POST['task'] ? $_POST['task'] : '';
switch ($task) {
	case "saveconfig":
		showHeader('Save Configuration');
		saveConfig();
		break;
	case "testconfig":
		showHeader('Test Configuration');
		testConfig();
		break;
	case "clanmanager":
		showHeader('Clan Manager');
		//processClanManager();
		break;
	case "banplayers":
		showHeader('Ban Players');
		processBanPlayers();
		break;
	case "unbanplayers":
		showHeader('Un-Ban Players');
		processUnBanPlayers();
		break;
	case "resetunlocks":
		showHeader('Reset Unlocks');
		processResetUnlocks();
		break;
	case "mergeplayers":
		showHeader('Merge Players');
		processMergePlayers();
		break;
	case "deleteplayers":
		showHeader('Delete Players');
		processDeletePlayers();
		break;
	case "installdb":
		showHeader('Install Database');
		processInstallDB();
		break;
	case "upgradedb":
		showHeader('Upgrade Database');
		processUpgradeDB();
		break;
	case "cleardb":
		showHeader('Clear Database');
		processClearDB();
		break;
	case "backupdb":
		showHeader('Backup Database');
		processBackupDB();
		break;
	case "restoredb":
		showHeader('Restore Database');
		processClearDB();
		processRestoreDB();
		break;
	case "validateranks":
		showHeader('Validate Ranks');
		processValidateRanks();
		break;
	case "checkawards":
		showHeader('Check Backend Awards');
		processCheckAwards();
		break;
	case "importlogs":
		showHeader('Import SNAPSHOT Logs');
		processImportLogs();
		break;
	case "importplayers":
		showHeader('Import Playerss');
		processImportPlayers();
		break;
	case "serverinfo":
		showHeader('Server Info');
		//processServerInfo();
		break;
	default:
		showLoginForm();
		break;
}

// Tidy up HTML
echo "</pre></div>";

function showHeader($str) {
	echo "<div class=\"content-head\"><div class=\"desc-title\">Processing: {$str}</div></div><div class=\"readme\"><pre>";
}


// Display Log Message to Browser
function showLog($msg) {
	global $cfg;
	$outmsg = date('Y-m-d H:i:s')." : ".$msg."\n";
	echo $outmsg;
	
	if ($cfg->get('admin_log') != '') {
		$file = @fopen($cfg->get('admin_log'), 'a');
		@fwrite($file, $outmsg);
		@fclose($file);
	}
	
	flush();
	if (ini_get('zlib.output_compression') != 0) { ob_flush(); }
}

function saveConfig() {
	$cfg = new Config();
		
	// Store New/Changed config items
	showLog("Saving Config...");
	foreach ($_POST as $item => $val) {
		$key = explode('__', $item);
		if ($key[0] == 'cfg') {
			showLog(" -> Found Key: '{$key[1]}' => '".((is_array($cfg->get($key[1])))?str_replace("\r\n", ",",$val):$val)."' (Old: ".((is_array($cfg->get($key[1])))?implode(',',$cfg->get($key[1])):$cfg->get($key[1])).")...");
			$cfg->set($key[1],$val);
		}
	}
	
	$cfg->Save();
}

function testConfig() {

}

function processBanPlayers() {

}

function processUnBanPlayers() {

}

function processResetUnlocks() {

}

function processDeletePlayers() {

}

function processMergePlayers() {

	
}

function processInstallDB() {

}

function processUpgradeDB() {

}

function processClearDB() {
	global $cfg;
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection) or die("Database Error: " . mysql_error());
	$tables = array('awards','mapinfo','stats_a','stats_e','stats_m','stats_v','stats_w','unlocks');
	foreach ($tables as $table) {
		$query1 = 'TRUNCATE TABLE `'.$table.'`;';
		$result1 = mysql_query($query1);
		checkSQLResult ($result1, $query1);
	}
	showLog("Done!");
}

function processBackupDB() {

}

function processRestoreDB() {

}

function processValidateRanks() {

}

function processCheckAwards() {

}

function processImportLogs() {
	// This function will import all existing log files.  This is useful for rebuilding an empty Gamespy database
	global $cfg;

	// Make Sure Script doesn't timeout
	set_time_limit(360);
	
	// Find Log Files
	showLog("Importing Log Files");
	$regex = '([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{4})';
		
	$dir = opendir(chkPath($cfg->get('stats_logs')));
	chdir(chkPath($cfg->get('stats_logs')));
	while (($file = readdir($dir)) !== false) {
		if (strpos($file, $cfg->get('stats_ext')))
		{
			ereg($regex,$file,$sort);
			$files[] = $sort[0] . "|" . $file;
		}
	}
	
	// Sort Files
	sort($files, SORT_STRING);
	// Re-post existing log data to bf2statistics
	$total = 0;
	for ($x = 0; $x < count($files); $x++) {
		$file = explode("|",$files[$x]);
		if(isset($file_p)) {
			while(file_exists($file_p)) {
				//loop
			}
		}
		$file_p = $file[1];
		$fh = fsockopen('195.140.177.252', 80);
		
		fwrite($fh, "POST /bf2142statistics.php HTTP/1.1\r\n");
		fwrite($fh, "HOST: localhost\r\n");
		fwrite($fh, "User-Agent: GameSpyHTTP/1.0\r\n");
		fwrite($fh, "Content-Type: application/x-www-form-urlencoded\r\n");
		
		$filename = @fopen($file[1], 'r');
		$data = fread($filename, filesize($file[1]));
		@fclose($filename);
		
/*
		if (stripos($data, '\EOF\1') === false) {
			// Older SNAPSHOT.  Insert EOF to ensure bf2statiscs.php processes this...
			$data .= '\EOF\1';
		}
		if (stripos($file[1], "importdata") === false) {
			// Make sure we know this is an import of existing log data
			$data .= '\import\1';
		}
*/
		fwrite($fh, "Content-Length: " . strlen($data) . "\r\n\r\n");
		fwrite($fh, $data . "\r\n");
		fclose($fh);
		showLog(" -> Importing $file[1]...done!");
		$total++;
	}
	
	showLog("Total files imported: $total");
}

function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function processImportPlayers() {
	global $cfg;

	DEFINE('__PASS','<b><font color="green">Pass</font></b>');
	DEFINE('__WARN','<b><font color="orange">Warn</font></b>');
	DEFINE('__FAIL','<b><font color="red">Fail</font></b>');

	showLog("include ea_support.php");
	require_once("ea_support.php");
	showLog("Done! :)");
	$bfcoding  = &new ea_stats();

	$timestamp = '';
	$timestamp = time();
	if(isset($_REQUEST['pid']) AND $_REQUEST['pid'] != "") {		$pid = $_REQUEST['pid'];		} else {	$pid = '0';	}
	showLog("pid = $pid!");
	if(isset($_REQUEST['superid']) AND $_REQUEST['superid'] != "") {	$superid = $_REQUEST['superid'];	} else {	$superid = '0';	}
	showLog("superid = $superid!");
	if(isset($_REQUEST['nickname']) AND $_REQUEST['nickname'] != "") {	$nickname = $_REQUEST['nickname'];	} else {	$nickname = '0';	}
	showLog("nickname = $nickname!");
	$clType = 1;
	$timestamp_dword = dwh(dechex($timestamp));
	$pid_dword = dwh(dechex($pid));

	$code  = dwh(dechex($timestamp)).dwh(dechex(100)).dwh(dechex($pid))."0".$clType."00";
	$code .= CalcCRC($code);
	$auth = $bfcoding->getBase64Encode($bfcoding->DefEncryptBlock($bfcoding->hex2str($code)));
	
	showLog("auth = $auth");
	
	$getplayerinfo_base  = "http://stella.prod.gamespy.com/getplayerinfo.aspx?auth=$auth&mode=base";
	showLog("getplayerinfo_base  = ".$getplayerinfo_base);
	$playerinfoBase = getPageContents($getplayerinfo_base);
/*	$playerinfoBase = array(
		"0"	=>	'O',
		"1"	=>	'H	asof	cb',
		"2"	=>	'D	1167917027	server',
		"3"	=>	'H	pid	nick	tid	gsco	rnk	tac	cs	tt	crpt	klstrk	bnspt	dstrk	rps	resp	tasl	tasm	awybt	hls	sasl	tds	win	los	unlc	expts	cpt	dcpt	twsc	tcd	slpts	tcrd	md	ent	ent-1	ent-2	ent-3	wtp-30	htp	hkl	atp	akl	vtp-0	vtp-1	vtp-2	vtp-3	vtp-4	vtp-5	vtp-6	vtp-7	vtp-8	vtp-9	vtp-10	vtp-11	vtp-12	vtp-13	vkls-0	vkls-1	vkls-2	vkls-3	vkls-4	vkls-5	vkls-6	vkls-7	vkls-8	vkls-9	vkls-10	vkls-11	vkls-12	vkls-13	vdstry-0	vdstry-1	vdstry-2	vdstry-3	vdstry-4	vdstry-5	vdstry-6	vdstry-7	vdstry-8	vdstry-9	vdstry-10	vdstry-11	vdstry-12	vdstry-13	ktt-0	ktt-1	ktt-2	ktt-3	wkls-0	wkls-1	wkls-2	wkls-3	wkls-4	wkls-5	wkls-6	wkls-7	wkls-8	wkls-9	wkls-10	wkls-11	wkls-12	wkls-13	wkls-14	wkls-15	wkls-16	wkls-17	wkls-18	wkls-19	wkls-20	wkls-21	wkls-22	wkls-23	wkls-24	wkls-25	wkls-26	wkls-27	wkls-28	wkls-29	wkls-30	klsk	klse	etp-0	etp-1	etp-2	etp-3	etp-4	etp-5	etp-6	etp-7	etp-8	etp-9	etp-10	etp-11	etpk-0	etpk-1	etpk-2	etpk-3	etpk-4	etpk-5	etpk-6	etpk-7	etpk-8	etpk-9	etpk-10	etpk-11	attp-0	attp-1	awin-0	awin-1	tgpm-0	tgpm-1	kgpm-0	kgpm-1	bksgpm-0	bksgpm-1	ctgpm-0	ctgpm-1	csgpm-0	csgpm-1	trpm-0	trpm-1	klls	attp-0	attp-1	awin-0	awin-1	pdt	mtt-0-0	mtt-0-1	mtt-0-2	mtt-0-3	mtt-0-4	mtt-0-5	mtt-0-6	mtt-0-7	mtt-0-8	mtt-0-9	mwin-0-0	mwin-0-1	mwin-0-2	mwin-0-3	mwin-0-4	mwin-0-5	mwin-0-6	mwin-0-7	mwin-0-8	mwin-0-9	mbr-0-0	mbr-0-1	mbr-0-2	mbr-0-3	mbr-0-4	mbr-0-5	mbr-0-6	mbr-0-7	mbr-0-8	mbr-0-9	mtt-1-1	mtt-1-5	mwin-1-1	mwin-1-5	mlos-1-1	mlos-1-5	mbr-1-1	mbr-1-5	msc-1-1	msc-1-5',
		"4"	=>	'D	83534936	ViCh	0	3111	15	0	0	122242	5424	24	812	8	33	20	52686	68091	60456	217	0	0	83	64	15	1501	148	70	1307	0	423	0	0	0	0	0	0	1375	1407	1	5290	75	6591	6968	5290	0	1407	0	5019	1195	33	117233	470	460	0	0	125	122	75	0	1	0	36	22	0	1107	5	0	0	0	40	59	92	0	5	0	109	38	0	1194	17	0	0	0	778	46815	63836	2381	0	386	17	72	16	2	3	179	35	105	1	1	26	1	1	2	5	99	0	2	0	39	106	0	0	0	0	0	0	0	11	3	39	2659	2878	0	0	0	5374	262	276	0	0	13	378	43747	63836	0	0	0	46815	2381	0	0	0	0	0	59726	62532	36	47	118938	3322	1493	22	10	1	0	0	0	0	144	3	1109	59726	62532	36	47	26	19291	11927	14976	7930	15805	10936	6833	9738	4136	17366	16	10	5	7	10	6	4	10	1	11	6	0	0	2	1	0	0	2	2	4	2963	359	2	1	0	0	0	0	61	2',
		"5"	=>	'H	unlockid',
		"6"	=>	'D	213',
		"7"	=>	'D	221',
		"8"	=>	'D	314',
		"9"	=>	'D	323',
		"10"	=>	'D	513',
		"11"	=>	'D	521',
		"12"	=>	'$	1753	$',
		);
*/
	foreach ($playerinfoBase as $tempkey => $tempvalue) {
		$playerinfoBase[$tempkey] = rtrim($tempvalue);
	}
	if(!stristr(implode("\n",$playerinfoBase),"O\nH\tasof\tcb\n")) {
		die("Player with PID {$pid} doesn't exist on the stat server !");
	}
	$playerinfoData1 = parsePlayerInfo($playerinfoBase);
	$playerinfoData2 = parseUnlocks($playerinfoBase);

	$getawardsinfo = 'http://stella.prod.gamespy.com/getawardsinfo.aspx?auth='.$auth;
	showLog("getawardsinfo => $getawardsinfo");
	$playerinfoAwards = getPageContents($getawardsinfo);
/*
	$playerinfoAwards = array(
		"0" =>	'O',
		"1" =>	'H	pid	nick	asof',
		"2" =>	'D	83534936	ViCh	1167941624',
		"3" =>	'H	award	level	when	first',
		"4" =>	'D	102_1	0	1163066800	0',
		"5" =>	'D	103_1	0	1163033899	0',
		"6" =>	'D	104_1	0	1163772832	0',
		"7" =>	'D	108_1	0	1163500940	0',
		"8" =>	'D	112_1	0	1164976672	0',
		"9" =>	'D	113_1	0	1167403200	0',
		"10" =>	'D	114_1	0	1163066800	0',
		"11" =>	'D	116_1	0	1165295376	0',
		"12" =>	'D	200	10	1167487689	1163046145',
		"13" =>	'D	201	11	1167486182	1163033899',
		"14" =>	'D	202	11	1167480143	1163203802',
		"15" =>	'D	313	0	1165325228	0',
		"16" =>	'D	400	75	1167638956	1163032574',
		"17" =>	'D	401	5	1167637510	1163062141',
		"18" =>	'D	411	32	1167480143	1163503716',
		"19" =>	'$	371	$'
		);
*/
	$playerinfoData4 = parseAwards($playerinfoAwards);
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection) or die("Database Error: " . mysql_error());

	$query1 = "SELECT * FROM accounts WHERE `id`='".$superid."'";
	$result1 = mysql_query($query1);
	checkSQLResult ($result1, $query1);
	if (!mysql_num_rows($result1)) {
		showLog("er1: Account not found!"); exit;
	} else {
		$row1 = mysql_fetch_assoc($result1);
		showLog("Account superid=".$superid."[".$row1['name']."] found!");
	}
	$query3 = "SELECT * FROM subaccounts WHERE `subaccount`='".$nickname."'";
	$result3 = mysql_query($query3);
	checkSQLResult ($result3, $query3);
//==>
	if (!mysql_num_rows($result3)) {
		showLog("Insert New!");
		$query4 = "INSERT INTO subaccount SET subaccount = '".$nickname."', id = '".$superid.";";
		showLog("$query4");
		$result4 = mysql_query($query4);
		checkSQLResult ($result4, $query4);
		$query5 = "SELECT pid FROM subaccount WHERE subaccount = '".$nickname."' AND id = '".$superid."';";
		showLog("$query5");
		$result5 = mysql_query($query5);
		checkSQLResult ($result5, $query5);
		if (!mysql_num_rows($result5)) {
			echo "er5: NewPID not found"; exit;
		} else {
			$row5 = mysql_fetch_assoc($result5);
			$newpid = $row5['pid'];
			showLog("NewPID => [".$newpid."]");
		}
	// stats
	#a
		$parm6a = "adpr, akl, akpr, atp, attp-0, attp-1, awin-0, awin-1, awybt, ban, bksgpm-0, bksgpm-1, bnspt, bp-1, brs, capa, cpt, crpt, cs, csgpm-0, csgpm-1, ctgpm-0, ctgpm-1, cts, dass, dcpt, dpm, dstrk, dths, ent, ent-1, ent-2, ent-3, expts, fe, fgm, fk, fm, fv, fw, gsco, hkl, hls, htp, kdr, kgpm-0, kgpm-1, kkls-0, kkls-1, kkls-2, kkls-3, klla, klls, klse, klsk, klstrk, kluav, kpm, ktt-0, ktt-1, ktt-2, ktt-3, lgdt, los, md, ovaccu, pdt, pdtc, resp, rnk, rnkcg, rps, rvs, sasl, slbcn, slbspn, slpts, sluav, spm, suic, tac, talw, tas, tasl, tasm, tcd, tcrd, tdmg, tdrps, tds, tgd, tgpm-0, tgpm-1, tgr, tid, tkls, toth, tots, trp, trpm-0, trpm-1, tt, ttp, tvdmg, twsc, unavl, unlc, win";
		$query6a  = "INSERT INTO `stats_a` SET ";
		$a_parm6a = explode(", ", $parm6a);
		foreach ($a_parm6a as $a_parm6a2) {
			if(isset($playerinfoData1[$a_parm6a2])) {
				$query6a .= " `".$a_parm6a2."`='".$playerinfoData1[$a_parm6a2]."',\n";
				if($a_parm6a2 == 'rnk') echo $playerinfoData1[$a_parm6a2];
			}
		}
		$query6a .= " `pid` = '".$newpid."';";
	#e
		$parm6b = "etp-0, etp-1, etp-2, etp-3, etp-4, etp-5, etp-6, etp-7, etp-8, etp-9, etp-10, etp-11, etpk-0, etpk-1, etpk-2, etpk-3, etpk-4, etpk-5, etpk-6, etpk-7, etpk-8, etpk-9, etpk-10, etpk-11";
		$query6b  = "INSERT INTO `stats_e` SET ";
		$a_parm6b = explode(", ", $parm6b);
		foreach ($a_parm6b as $a_parm6b2) {
			if(isset($playerinfoData1[$a_parm6b2])) {
				$query6b .= " `".$a_parm6b2."`='".$playerinfoData1[$a_parm6b2]."',\n";
			}
		}
		$query6b .= " `pid` = '".$newpid."';";
	#m
		$parm6c = "mbr-0-0, mbr-0-1, mbr-0-2, mbr-0-3, mbr-0-4, mbr-0-5, mbr-0-6, mbr-0-7, mbr-0-8, mbr-0-9, mbr-1-0, mbr-1-1, mbr-1-2, mbr-1-3, mbr-1-5, mlos-0-0, mlos-0-1, mlos-0-2, mlos-0-3, mlos-0-4, mlos-0-5, mlos-0-6, mlos-0-7, mlos-0-8, mlos-0-9, mlos-1-0, mlos-1-1, mlos-1-2, mlos-1-3, mlos-1-5, msc-0-0, msc-0-1, msc-0-2, msc-0-3, msc-0-4, msc-0-5, msc-0-6, msc-0-7, msc-0-8, msc-0-9, msc-1-0, msc-1-1, msc-1-2, msc-1-3, msc-1-5, mtt-0-0, mtt-0-1, mtt-0-2, mtt-0-3, mtt-0-4, mtt-0-5, mtt-0-6, mtt-0-7, mtt-0-8, mtt-0-9, mtt-1-0, mtt-1-1, mtt-1-2, mtt-1-3, mtt-1-5, mwin-0-0, mwin-0-1, mwin-0-2, mwin-0-3, mwin-0-4, mwin-0-5, mwin-0-6, mwin-0-7, mwin-0-8, mwin-0-9, mwin-1-0, mwin-1-1, mwin-1-2, mwin-1-3, mwin-1-5";
		$query6c  = "INSERT INTO `stats_m` SET ";
		$a_parm6c = explode(", ", $parm6c);
		foreach ($a_parm6c as $a_parm6c2) {
			if(isset($playerinfoData1[$a_parm6c2])) {
				$query6c .= " `".$a_parm6c2."`='".$playerinfoData1[$a_parm6c2]."',\n";
			}
		}
		$query6c .= " `pid` = '".$newpid."';";
	#v
		$parm6d = "vdstry-0, vdstry-1, vdstry-2, vdstry-3, vdstry-4, vdstry-5, vdstry-6, vdstry-7, vdstry-8, vdstry-9, vdstry-10, vdstry-11, vdstry-12, vdstry-13, vdths-0, vdths-1, vdths-2, vdths-3, vdths-4, vdths-5, vdths-6, vdths-7, vdths-8, vdths-9, vdths-10, vdths-11, vdths-12, vdths-13, vkdr-0, vkdr-1, vkdr-2, vkdr-3, vkdr-4, vkdr-5, vkdr-6, vkdr-7, vkdr-8, vkdr-9, vkdr-10, vkdr-11, vkdr-12, vkdr-13, vkls-0, vkls-1, vkls-2, vkls-3, vkls-4, vkls-5, vkls-6, vkls-7, vkls-8, vkls-9, vkls-10, vkls-11, vkls-12, vkls-13, vrkls-0, vrkls-1, vrkls-2, vrkls-3, vrkls-4, vrkls-5, vrkls-6, vrkls-7, vrkls-8, vrkls-9, vrkls-10, vrkls-11, vrkls-12, vrkls-13, vtp-0, vtp-1, vtp-2, vtp-3, vtp-4, vtp-5, vtp-6, vtp-7, vtp-8, vtp-9, vtp-10, vtp-11, vtp-12, vtp-13";
		$query6d  = "INSERT INTO `stats_v` SET ";
		$a_parm6d = explode(", ", $parm6d);
		foreach ($a_parm6d as $a_parm6d2) {
			if(isset($playerinfoData1[$a_parm6d2])) {
				$query6d .= " `".$a_parm6d2."`='".$playerinfoData1[$a_parm6d2]."',\n";
			}
		}
		$query6d .= " `pid` = '".$newpid."';";
	#w
		$parm6e = "waccu-0, waccu-1, waccu-2, waccu-3, waccu-4, waccu-5, waccu-6, waccu-7, waccu-8, waccu-9, waccu-10, waccu-11, waccu-12, waccu-13, waccu-14, waccu-15, waccu-16, waccu-17, waccu-18, waccu-19, waccu-20, waccu-21, waccu-22, waccu-23, waccu-24, waccu-25, waccu-26, waccu-27, waccu-28, waccu-29, waccu-30, wdths-0, wdths-1, wdths-2, wdths-3, wdths-4, wdths-5, wdths-6, wdths-7, wdths-8, wdths-9, wdths-10, wdths-11, wdths-12, wdths-13, wdths-14, wdths-15, wdths-16, wdths-17, wdths-18, wdths-19, wdths-20, wdths-21, wdths-22, wdths-23, wdths-24, wdths-25, wdths-26, wdths-27, wdths-28, wdths-29, wdths-30, whts-0, whts-1, whts-2, whts-3, whts-4, whts-5, whts-6, whts-7, whts-8, whts-9, whts-10, whts-11, whts-12, whts-13, whts-14, whts-15, whts-16, whts-17, whts-18, whts-19, whts-20, whts-21, whts-22, whts-23, whts-24, whts-25, whts-26, whts-27, whts-28, whts-29, whts-30, wkdr-0, wkdr-1, wkdr-2, wkdr-3, wkdr-4, wkdr-5, wkdr-6, wkdr-7, wkdr-8, wkdr-9, wkdr-10, wkdr-11, wkdr-12, wkdr-13, wkdr-14, wkdr-15, wkdr-16, wkdr-17, wkdr-18, wkdr-19, wkdr-20, wkdr-21, wkdr-22, wkdr-23, wkdr-24, wkdr-25, wkdr-26, wkdr-27, wkdr-28, wkdr-29, wkdr-30, wkls-0, wkls-1, wkls-2, wkls-3, wkls-4, wkls-5, wkls-6, wkls-7, wkls-8, wkls-9, wkls-10, wkls-11, wkls-12, wkls-13, wkls-14, wkls-15, wkls-16, wkls-17, wkls-18, wkls-19, wkls-20, wkls-21, wkls-22, wkls-23, wkls-24, wkls-25, wkls-26, wkls-27, wkls-28, wkls-29, wkls-30, wshts-0, wshts-1, wshts-2, wshts-3, wshts-4, wshts-5, wshts-6, wshts-7, wshts-8, wshts-9, wshts-10, wshts-11, wshts-12, wshts-13, wshts-14, wshts-15, wshts-16, wshts-17, wshts-18, wshts-19, wshts-20, wshts-21, wshts-22, wshts-23, wshts-24, wshts-25, wshts-26, wshts-27, wshts-28, wshts-29, wshts-30, wtp-0, wtp-1, wtp-2, wtp-3, wtp-4, wtp-5, wtp-6, wtp-7, wtp-8, wtp-9, wtp-10, wtp-11, wtp-12, wtp-13, wtp-14, wtp-15, wtp-16, wtp-17, wtp-18, wtp-19, wtp-20, wtp-21, wtp-22, wtp-23, wtp-24, wtp-25, wtp-26, wtp-27, wtp-28, wtp-29, wtp-30, wtpk-0, wtpk-1, wtpk-2, wtpk-3, wtpk-4, wtpk-5, wtpk-6, wtpk-7, wtpk-8, wtpk-9, wtpk-10, wtpk-11, wtpk-12, wtpk-13, wtpk-14, wtpk-15, wtpk-16, wtpk-17, wtpk-18, wtpk-19, wtpk-20, wtpk-21, wtpk-22, wtpk-23, wtpk-24, wtpk-25, wtpk-26, wtpk-27, wtpk-28, wtpk-29, wtpk-30";
		$query6e = "INSERT INTO `stats_w` SET ";
		$a_parm6e = explode(", ", $parm6e);
		foreach ($a_parm6e as $a_parm6e2) {
			if(isset($playerinfoData1[$a_parm6e2])) {
				$query6e .= " `".$a_parm6e2."`='".$playerinfoData1[$a_parm6e2]."',\n";
			}
		}
		$query6e .= " `pid` = '".$newpid."';";

		$result6a = mysql_query($query6a);
		checkSQLResult ($result6a, $query6a);
		$result6b = mysql_query($query6b);
		checkSQLResult ($result6b, $query6b);
		$result6c = mysql_query($query6c);
		checkSQLResult ($result6c, $query6c);
		$result6d = mysql_query($query6d);
		checkSQLResult ($result6d, $query6d);
		$result6e = mysql_query($query6e);
		checkSQLResult ($result6e, $query6e);
	// unlocks
		$query7 = "INSERT INTO `unlocks` (`pid`, `ukit`, `utree`, `uorder`) VALUES ";
		foreach($playerinfoData2 as $player_unlocks_name) {
			list($ukit,$utree,$uorder) = str_split($player_unlocks_name);
			$query7 .= "(".$newpid.",".$ukit.",".$utree.",".$uorder."),";
		}
		$query7 = substr($query7, 0, -1).";";
		$result7 = mysql_query($query7);
		checkSQLResult ($result7, $query7);
	// awards
		$query8 = "INSERT INTO `awards` (`pid`, `atype`, `aid`, `alvl`, `earned`, `first`) VALUES ";
		foreach($playerinfoData4 as $player_awards_name) {
			$atype = substr($player_awards_name['id'], 0,1);
			switch($atype) {
				case "1":
					$aid = substr($player_awards_name['id'], 1,2);
					$alvl = substr($player_awards_name['id'], 4,1);
				break;
				case "2":
				case "3":
				case "4":
					$aid = substr($player_awards_name['id'], 1,2);
					$alvl = $player_awards_name['level'];
				break;
			}
			
			$query8 .= "(".$newpid.",".$atype.",".$aid.",".$alvl.",".$player_awards_name['earned'].",".$player_awards_name['first']."),";
		}
		$query8 = substr($query8, 0, -1).";";
		$result8 = mysql_query($query8);
		checkSQLResult ($result8, $query8);
		showLog("Done!");

	} else {
		showLog("Update!");
		exit;
	}



	

}
function parsePlayerInfo($data) {
	$playerdata = array();	
	if(count($data)>0) {
		// put header and data in arrays
		$i=0;
		foreach($data as $line) {
			if($i==3) {
				$H = explode(chr(9), trim($line));
			}
			if($i==4) {
				$D = explode(chr(9), trim($line));
			}
			$i++;
		}
		
		// merge header and data
		if(count($H)>0) {
			$i=0;
			foreach($H as $part) {
				if($part!="H")
					$playerdata[$part] = $D[$i];
				$i++;
			}
		}
	}
	return $playerdata;
}
function parseUnlocks($data) {
	if(count($data)>0) {
		$i=0; $count=0;
		foreach($data as $line) {
			if($i>5 && stristr($line,"D")) {
				$parts = explode(chr(9), str_replace("\n","",$line));
				$unlocks[$count] = $parts[1];
				$count++;
			}
			$i++;
		}
	}
	return $unlocks;
}
function parseAwards($data) {
	
	// setup medal array
	$awards = array();
	$i=0;
	$first = true;
	foreach($data as $line) {
		if(substr($line,0,1)=="D") {
			if(!$first) {
				$parts = explode(chr(9),$line);
				
				$awards[$i]["id"] = $parts[1];
				$awards[$i]["level"] = $parts[2];		
				$awards[$i]["earned"] = $parts[3];		
				$awards[$i]["first"] = $parts[4];				
				
				$i++;
			} else {
				$first = false;
			}
		}
	}
	return $awards;
}
?>
