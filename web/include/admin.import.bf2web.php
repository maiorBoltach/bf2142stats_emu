<?php
// Import EA BF2142Web Gamespy Data
// $cfg = new Config();
// $connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
// @mysql_select_db($cfg->get('db_name'), $connection) or die("Database Error: " . mysql_error());

// Temp PID
$pid = 43890642;
//$pid = 76591835;

// BF2Web URL
$gsURL = "http://bf2142web.gamespy.com";
//$gsURL = "http://www.shadowlans.com";

	// Get Player Info: Base (getplayerinfo.aspx query)
	$playerinfoURL1 = $gsURL."/ASP/getplayerinfo.aspx?pid={$pid}&info=per*,cmb*,twsc,cpcp,cacp,dfcp,kila,heal,rviv,rsup,rpar,tgte,dkas,dsab,cdsc,rank,cmsc,kick,kill,deth,suic,ospm,klpm,klpr,dtpr,bksk,wdsk,bbrs,tcdr,ban,dtpm,lbtl,osaa,vrk,tsql,tsqm,tlwf,mvks,vmks,mvn*,vmr*,fkit,fmap,fveh,fwea,wtm-,wkl-,wdt-,wac-,wkd-,vtm-,vkl-,vdt-,vkd-,vkr-,atm-,awn-,alo-,abr-,ktm-,kkl-,kdt-,kkd-";
	$playerinfoBase = getPageContents($playerinfoURL1);
	if(!stristr(implode("\n",$playerinfoBase),"O\nH\tasof\n")) {
		die("Player with PID {$pid} doesn't exist on the stat server !");
	}
	$playerinfoData1 = parsePlayerInfo($playerinfoBase);
	
	// Get Player Info: Extra (getplayerinfo.aspx query)
	$playerinfoURL2 = $gsURL."/ASP/getplayerinfo.aspx?pid={$pid}&info=mtm-,mwn-,mls-"; 
	$playerinfoExtra = getPageContents($playerinfoURL2);
	$playerinfoData2 = parsePlayerInfo($playerinfoExtra);
	
	// Get Player Info: Unlocks (getunlocksinfo.aspx query)
	$unlocksinfoURL = $gsURL."/ASP/getunlocksinfo.aspx?pid={$pid}"; 
	$playerinfoUnlocks = getPageContents($unlocksinfoURL);
	$playerinfoData3 = parseUnlocks($playerinfoUnlocks);
	
	// Get Player Info: Awards (getawardsinfo.aspx query)
	$awardsinfoURL = $gsURL."/ASP/getawardsinfo.aspx?pid={$pid}"; 
	$playerinfoAwards = getPageContents($awardsinfoURL);
	$playerinfoData4 = parseAwards($playerinfoAwards);
	
	print_r($playerinfoData1);
	print_r($playerinfoData2);
	print_r($playerinfoData3);
	print_r($playerinfoData4);
	
	
	// Check if Player Already Exists	
	$query = "SELECT * FROM player WHERE id = {$pid}";
	$result = mysql_query($query);
	checkSQLResult ($result, $query);
	if (!mysql_num_rows($result))
	{
		$country = 'xx';
		// Insert information
		$query = "INSERT INTO player SET
			id = {$pid},
			name = '" . $playerinfoData1["nick"] . "',
			country = 'xx',
			time = " . $playerinfoData1["time"] . ",
			rounds = " . $playerinfoData1["mode0"] + $playerinfoData1["mode1"] + $playerinfoData1["mode2"] . ",
			ip = '0.0.0.0',
			score = " . $playerinfoData1["scor"] . ",
			cmdscore = " . $playerinfoData1["cdsc"] . ",
			skillscore = " . $playerinfoData1["cmsc"] . ",
			teamscore = " . $playerinfoData1["twsc"] . ",
			kills = " . $playerinfoData1["kill"] . ",
			deaths = " . $playerinfoData1["deth"] . ",
			captures = " . $playerinfoData1["cpcp"] . ",
			neutralizes = 0,
			captureassists = " . $playerinfoData1["cacp"] . ",
			neutralizeassists = 0,
			defends = " . $playerinfoData1["dfcp"] . ",
			damageassists = " . $playerinfoData1["kila"] . ",
			heals = " . $playerinfoData1["heal"] . ",
			revives = " . $playerinfoData1["rviv"] . ",
			ammos = " . $playerinfoData1["rsup"] . ",
			repairs = " . $playerinfoData1["rpar"] . ",
			targetassists = " . $playerinfoData1["tgte"] . ",
			driverspecials = " . $playerinfoData1["dsab"] . ",
			driverassists = " . $playerinfoData1["dkas"] . ",
			passengerassists = 0,
			teamkills = 0,
			teamdamage = 0,
			teamvehicledamage = 0,
			suicides = " . $playerinfoData1["suic"] . ",
			killstreak = " . $playerinfoData1["bksk"] . ",
			deathstreak = " . $playerinfoData1["wdsk"] . ",
			rank = " . $playerinfoData1["rank"] . ",
			banned = " . $playerinfoData1["ban"] . ",
			kicked = " . $playerinfoData1["kick"] . ",
			cmdtime = " . $playerinfoData1["tcdr"] . ",
			sqltime = " . $playerinfoData1["tsql"] . ",
			sqmtime = " . $playerinfoData1["tsqm"] . ",
			lwtime = " . $playerinfoData1["tlwf"] . ",
			wins = " . $playerinfoData1["wins"] . ",
			losses = " . $playerinfoData1["loss"] . ",
			availunlocks = 0,
			usedunlocks = 0,
			joined = " . $playerinfoData1["jond"] . ",
			rndscore = " . $playerinfoData1["bbrs"] . ",
			lastonline = " . $playerinfoData1["lbtl"] . ",
			mode0 = " . $playerinfoData1["mode0"] . ",
			mode1 = " . $playerinfoData1["mode1"] . ",
			mode2 = " . $playerinfoData1["mode2"] . "
		";
		$result = mysql_query($query);
		checkSQLResult ($result, $query);
				
		// Insert unlocks
		for ($i = 11; $i < 100; $i += 11)
		{
			$query = "INSERT INTO unlocks SET
				id = " . $playerinfoData1["pID_$x"] . ",
				kit = {$i},
				state = 'n'
			";
			$result = mysql_query($query);
			checkSQLResult ($result, $query);
		}
		for ($i = 111; $i < 556; $i += 111)
		{
			$query = "INSERT INTO unlocks SET
				id = " . $playerinfoData1["pID_$x"] . ",
				kit = {$i},
				state = 'n'
			";
			$result = mysql_query($query);
			checkSQLResult ($result, $query);
		}
	}
	else
	{
		$row = mysql_fetch_array($result);
				// Check IP
				if ($row['ip'] != $playerinfoData1["ip_$x"])
				{
					$query2 = "SELECT country FROM ip2nation WHERE ip < INET_ATON('" . $playerinfoData1["ip_$x"] . "') ORDER BY ip DESC LIMIT 1";
					$result2 = mysql_query($query2);
					checkSQLResult ($result2, $query2);
					if (!mysql_num_rows($result2)) {
						$country = 'xx';
					} else {
						$row2 = mysql_fetch_array($result2);
						$country = $row2['country'];
					}
				}
				else {$country = $row['country'];}

				// Verify/Correct Rank
				if ($cfg->get('stats_rank_check')) {
					$score = $row['score'] + $playerinfoData1["rs_$x"];
					$rank  = $playerinfoData1["rank_$x"];
					ErrorLog("Checking Rank for Player (".$playerinfoData1["pID_$x"].") : Score:{$score} : Rank:{$rank}",3);
					$expRank = array();
					
					// NOTE: Ranks 1SG/SGM/BG/MG/SMOC/GEN cannot be awarded here.
					if ($score >= 200000) {$expRank[0] = 20;$expRank[1] = 20;}
					elseif ($score >= 150000) {$expRank[0] = 17;$expRank[1] = 19;}
					elseif ($score >= 125000) {$expRank[0] = 16;$expRank[1] = 16;}
					elseif ($score >= 115000) {$expRank[0] = 15;$expRank[1] = 15;}
					elseif ($score >= 90000) {$expRank[0] = 14;$expRank[1] = 14;}
					elseif ($score >= 75000) {$expRank[0] = 13;$expRank[1] = 13;}
					elseif ($score >= 60000) {$expRank[0] = 12;$expRank[1] = 12;}
					elseif ($score >= 50000) {$expRank[0] = 9;$expRank[1] = 11;}
					elseif ($score >= 20000) {$expRank[0] = 7;$expRank[1] = 8;}
					elseif ($score >= 8000) {$expRank[0] = 6;$expRank[1] = 6;}
					elseif ($score >= 5000) {$expRank[0] = 5;$expRank[1] = 5;}
					elseif ($score >= 2500) {$expRank[0] = 4;$expRank[1] = 4;}
					elseif ($score >= 800) {$expRank[0] = 3;$expRank[1] = 3;}
					elseif ($score >= 500) {$expRank[0] = 2;$expRank[1] = 2;}
					elseif ($score >= 150) {$expRank[0] = 1;$expRank[1] = 1;}
					else {$expRank[0] = 0;$expRank[1] = 0;}
					
					// Only update if Rank is less than expected.
					if ($rank < $expRank[0] || $rank > $expRank[1]){
						// Rank seems to be messed up, will reset to minimum rank for this level
						$errmsg = "Rank Correction (".$playerinfoData1["pID_$x"]."): " .
							"Score:".$score."; " . 
							"Expected:".$expRank[0]."-".$expRank[1]."; " .
							"Found:".$playerinfoData1["rank_$x"]."; " .
							"New Rank:".$expRank[0];
						ErrorLog($errmsg,2);
						$playerinfoData1["rank_$x"] = $expRank[0];
					}
				} else {
					// Fail-safe in-case rank data was not obtained and reset to '0' in-game.
					$rank = $playerinfoData1["rank_$x"];
					$rank_db = $row['rank'];
					if ($rank_db > $rank) {
						// SNAPSHOT rank data appears to be incorrect, will use current db rank
						$playerinfoData1["rank_$x"] = $rank_db;
						$errmsg = "Rank Correction (".$playerinfoData1["pID_$x"]."), using db rank ({$rank_db})";
						ErrorLog($errmsg,2);
					}
				}
				
				// Calculate kill/deathstreak
				$killstreak = ($row['killstreak'] > $playerinfoData1["ks_$x"]) ? $row['killstreak'] : $playerinfoData1["ks_$x"];
				$deathstreak = ($row['deathstreak'] > $playerinfoData1["ds_$x"]) ? $row['deathstreak'] : $playerinfoData1["ds_$x"];
				
				// Calculate best round score
				$rndscore = ($row['rndscore'] > $playerinfoData1["rs_$x"]) ? $row['rndscore'] : $playerinfoData1["rs_$x"];
				
				// Check if Minimal Central Update
				if ($centralupdate == 2) {
					// Ignore any Rank Data in SnapShot as this could mess up current data
					$playerinfoData1["rank_$x"] = $row['rank'];
				}
				
				// Calculate rank change
				$chng = $decr = 0;
				if ($playerinfoData1["rank_$x"] != $row['rank'])
				{
					if ($playerinfoData1["rank_$x"] > $row['rank']) {$chng = 1;}
					else {$decr = 1;}
				}
				
				// Update information
				$query = "UPDATE player SET
					name = '" . $playerinfoData1["name_$x"] . "',
					country = '{$country}',
					time = `time` + " . $playerinfoData1["ctime_$x"] . ",
					rounds = `rounds` + {$complete},
					ip = '" . $playerinfoData1["ip_$x"] . "',
					score = `score` + " . $playerinfoData1["rs_$x"] . ",
					cmdscore = `cmdscore` + " . $playerinfoData1["cs_$x"] . ",
					skillscore = `skillscore` + " . $playerinfoData1["ss_$x"] . ",
					teamscore = `teamscore` + " . $playerinfoData1["ts_$x"] . ",
					kills = `kills` + " . $playerinfoData1["kills_$x"] . ",
					deaths = `deaths` + " . $playerinfoData1["deaths_$x"] . ",
					captures = `captures` + " . $playerinfoData1["cpc_$x"] . ",
					neutralizes = `neutralizes` + " . $playerinfoData1["cpn_$x"] . ",
					captureassists = `captureassists` + " . $playerinfoData1["cpa_$x"] . ",
					neutralizeassists = `neutralizeassists` + " . $playerinfoData1["cpna_$x"] . ",
					defends = `defends` + " . $playerinfoData1["cpd_$x"] . ",
					damageassists = `damageassists` + " . $playerinfoData1["ka_$x"] . ",
					heals = `heals` + " . $playerinfoData1["he_$x"] . ",
					revives = `revives` + " . $playerinfoData1["rev_$x"] . ",
					ammos = `ammos` + " . $playerinfoData1["rsp_$x"] . ",
					repairs = `repairs` + " . $playerinfoData1["rep_$x"] . ",
					targetassists = `targetassists` + " . $playerinfoData1["tre_$x"] . ",
					driverspecials = `driverspecials` + " . $playerinfoData1["drs_$x"] . ",
					driverassists = `driverassists` + " . $playerinfoData1["dra_$x"] . ",
					passengerassists = `passengerassists` + " . $playerinfoData1["pa_$x"] . ",
					teamkills = `teamkills` + " . $playerinfoData1["tmkl_$x"] . ",
					teamdamage = `teamdamage` + " . $playerinfoData1["tmdg_$x"] . ",
					teamvehicledamage = `teamvehicledamage` + " . $playerinfoData1["tmvd_$x"] . ",
					suicides = `suicides` + " . $playerinfoData1["su_$x"] . ",
					killstreak = {$killstreak},
					deathstreak = {$deathstreak},
					rank = " . $playerinfoData1["rank_$x"] . ",
					banned = `banned` + " . $playerinfoData1["ban_$x"] . ",
					kicked = `kicked` + " . $playerinfoData1["kck_$x"] . ",
					cmdtime = `cmdtime` + " . $playerinfoData1["tco_$x"] . ",
					sqltime = `sqltime` + " . $playerinfoData1["tsl_$x"] . ",
					sqmtime = `sqmtime` + " . $playerinfoData1["tsm_$x"] . ",
					lwtime = `lwtime` + " . $playerinfoData1["tlw_$x"] . ",
					wins = `wins` + {$wins},
					losses = `losses` + {$losses},
					rndscore = {$rndscore},
					lastonline = " . time() . ",
					mode0 = `mode0` + " . $globals['mode0'] . ",
					mode1 = `mode1` + " . $globals['mode1'] . ",
					mode2 = `mode2` + " . $globals['mode2'] . ",
					chng = {$chng},
					decr = {$decr}
					WHERE id = " . $playerinfoData1["pID_$x"] . "
				";
				$result = mysql_query($query);
				checkSQLResult ($result, $query);
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

// returns simple array of unlocks, e.g (11, 22, 55, 222)
function parseUnlocks($data) {
	if(count($data)>0) {
		$i=0; $count=0;
		foreach($data as $line) {
			if($i>5 && stristr($line,"s")) {
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
				$awards[$i]["when"] = $parts[3];		
				$awards[$i]["first"] = $parts[4];				
				
				$i++;
			} else {
				$first = false;
			}
		}
	}
	return $awards;
}

function getPageContents($url){	
	// Try file() first
	if( function_exists('file') && function_exists('fopen') && ini_get('allow_url_fopen') ) {
		//ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
		ini_set("user_agent","GameSpyHTTP/1.0");
		$results = @file($url);
	}
	
	// either there was no function, or it failed -- try curl
	if( !($results) && (function_exists('curl_exec')) ) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
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
		$results = explode("\n",trim($results));
		curl_close($curl_handle);
	}
	
	if( !$results ) // still nothing, forgetd a'bout it
	return false;
	
	return $results;
}

?>
