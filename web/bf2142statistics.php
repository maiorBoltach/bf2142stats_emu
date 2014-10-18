<?php
ob_start();











$LOG = 1;
function errorcode($errorcode=104) {
    $Out = "E\t" . $errorcode;
    $countOut = preg_replace('/[\t\n]/', '', $Out);
    print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
}

function chz($val) {
    if ($val == 0)
        return 1;
    else
        return $val;
}

$allow_db_changes = true;
$allow_db_show = true;

//Disable Zlib Compression
ini_set('zlib.output_compression', '0');

//Make Sure Script doesn't timeout even if the user disconnects!
set_time_limit(0);
ignore_user_abort(true);

// Import configuration
require('include/utils.php');
require_once('include/_ccconfig.php');
require_once ('include/rankSettings.php');
$cfg = new Config();
DEFINE("_ERR_RESPONSE", "E\nH\tresponse\nD\t<font color=\"red\">ERROR</font>: ");

// Open database connection
$connection = @mysql_connect($db_host, $db_user, $db_pass);
@mysql_select_db($db_name, $connection);


$query0 = "SELECT ip FROM `servers` WHERE authorised=1";
$result0 = mysql_query($query0);
checkSQLResult($result0, $query0);
$game_hosts = array();

if (mysql_num_rows($result0)) {
    while ($data0 = mysql_fetch_assoc($result0)) {
        $game_hosts[] = $data0['ip'];
    }
//print_r($game_hosts);
} else {
    $errmsg = "NOT FOUND Authorised gamehosts in DB";
    ErrorLog($errmsg, 0);
    die(_ERR_RESPONSE . $errmsg);
}

// Check Database Version
//$curdbver = getDbVer();
//
//if ($curdbver != $cfg->get('db_expected_ver')) {
//	$errmsg = "Database version expected: ".$cfg->get('db_expected_ver').", Found: {$curdbver}";
//	ErrorLog($errmsg, 1);
//	die();
//} else {
//	$errmsg = "Database version expected: ".$cfg->get('db_expected_ver').", Found: {$curdbver}";
//	ErrorLog($errmsg, 3);
//}
@mysql_close($connection);



// Get URL POST data
$rawdata = file_get_contents('php://input');
//$rawdata = file_get_contents('data.txt');
if ($LOG) {
$fp = fopen("logs/rawdata".time().".txt","a+");
fwrite($fp,$rawdata);
fflush($fp);
fclose($fp);
}
//$rawdata = file_get_contents('sinthetixData.txt');
file_put_contents("sinthetixData.txt", $rawdata);
// Seperate data
if ($rawdata) {
    $gooddata = explode('\\', $rawdata);
} else {
    $errmsg = "SNAPSHOT Data NOT found!";
    ErrorLog($errmsg, 1);
    die(_ERR_RESPONSE . $errmsg);
}

// Make key/value pairs
$prefix = $gooddata[0];
$mapname = strtolower($gooddata[1]);
$badtime = false;
for ($x = 2; $x < count($gooddata); $x += 2) {
    if ($gooddata[$x + 1] >= 184467440000) {
        $badtime = true;
        //$gooddata[$x + 1] = $gooddata[$x + 1] - 184467440000;
    }
    $data[$gooddata[$x]] = $gooddata[$x + 1];
}
//!print_r($data);
if ($LOG) {
$fp = fopen("logs/data".time().".txt","a+");
fwrite($fp,print_r($data,true));
fflush($fp);
fclose($fp);
}
// Import Backend Awards Data
require('include/data.awards.php');
$awardsdata = buildAwardsData($data['v']);
//print_r($awardsdata);
//$backendawardsdata = buildBackendAwardsData($data['v']);
// Generate SNAPSHOT Filename
//GMT +2:00
$offset = 2 * 60 * 60; //converting 2 hours to seconds.
$dateFormat = "d-m-Y";

$mapdate = gmdate('Ymd_Hi', (int) $data['mapstart'] + $offset);
$currentDate = gmdate('Ymd', (int) $data['mapstart'] + $offset);
$currdate = gmdate("Y-m-d", time() + $offset);
$stats_filename = '';
if ($prefix != '') {
    $stats_filename .= $prefix . '-';
}

if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != "") {
    $ip_s = $_SERVER['REMOTE_ADDR'];
}
$stats_filename .= $mapdate . '_' . $mapname . $cfg->get('stats_ext');

//	$file = @fopen( chkPath($cfg->get('stats_logs')) . $stats_filename, 'wb');
//	@fwrite($file, $rawdata);
//	@fclose($file);

$errmsg = "SNAPSHOT Data Logged (" . chkPath($cfg->get('stats_logs')) . $stats_filename . ")";
ErrorLog($errmsg, 3);


if (file_exists(chkPath($cfg->get('stats_logs')) . "")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "", 0777);
}
if (file_exists(chkPath($cfg->get('stats_logs')) . "bad")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "bad", 0777);
}
if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/BADTIME")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "bad/BADTIME", 0777);
}
if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/EOF")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "bad/EOF", 0777);
}
if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/NOTAUTH")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "bad/NOTAUTH", 0777);
}
if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/GAMEMOD")) {
    
} else {
    mkdir(chkPath($cfg->get('stats_logs')) . "bad/GAMEMOD", 0777);
}
// Check for Complete Snapshot data
//print_r($data);
// Check remote host is authorised (simple security check)
if (!checkIpAuth($game_hosts)) {
    $errmsg = "Unauthorised Access Attempted! (IP: " . $_SERVER['REMOTE_ADDR'] . ")";
    ErrorLog($errmsg, 0);
    $fn_src = chkPath($cfg->get('stats_logs')) . $stats_filename;
    if (!file_exists(chkPath($cfg->get('stats_logs')) . "bad/NOTAUTH/" . $ip_s)) mkdir(chkPath($cfg->get('stats_logs')) . "bad/NOTAUTH/" . $ip_s, 0777);
    $fn_dest = chkPath($cfg->get('stats_logs')) . "bad/NOTAUTH/" . $ip_s . "/" . $stats_filename;
    if (file_exists($fn_src)) {
        if (file_exists($fn_dest)) {
            $errmsg = "SNAPSHOT Data File Already Exists, Over-writing! ({$fn_src} -> {$fn_dest})";
            ErrorLog($errmsg, 2);
        }
        copy($fn_src, $fn_dest);
        // Remove the original ONLY if it copies
        if (file_exists($fn_dest)) {
            unlink($fn_src);
        }
    }
    $errmsg = "SNAPSHOT Data File Moved! ({$fn_src} -> {$fn_dest})";
    ErrorLog($errmsg, 3);
    die(_ERR_RESPONSE . $errmsg);
}

if ($badtime) {
    $errmsg = "SNAPSHOT Data has badtime!";
    ErrorLog($errmsg, 1);
    $fn_src = chkPath($cfg->get('stats_logs')) . $stats_filename;
    if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/BADTIME/" . $ip_s)) {
        
    } else {
        mkdir(chkPath($cfg->get('stats_logs')) . "bad/BADTIME/" . $ip_s, 0777);
    }
    $fn_dest = chkPath($cfg->get('stats_logs')) . "bad/BADTIME/" . $ip_s . "/" . $stats_filename;
    if (file_exists($fn_src)) {
        if (file_exists($fn_dest)) {
            $errmsg = "SNAPSHOT Data File Already Exists, Over-writing! ({$fn_src} -> {$fn_dest})";
            ErrorLog($errmsg, 2);
        }
        copy($fn_src, $fn_dest);
        // Remove the original ONLY if it copies
        if (file_exists($fn_dest)) {
            unlink($fn_src);
        }
    }
    $errmsg = "SNAPSHOT Data File Moved! ({$fn_src} -> {$fn_dest})";
    ErrorLog($errmsg, 3);
    die(_ERR_RESPONSE . $errmsg);
}
if ($data['EOF'] != 1) {
    $errmsg = "SNAPSHOT Data NOT complete!";
    ErrorLog($errmsg, 1);
    $fn_src = chkPath($cfg->get('stats_logs')) . $stats_filename;
    if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/EOF/" . $ip_s)) {
        
    } else {
        mkdir(chkPath($cfg->get('stats_logs')) . "bad/EOF/" . $ip_s, 0777);
    }
    $fn_dest = chkPath($cfg->get('stats_logs')) . "bad/EOF/" . $ip_s . "/" . $stats_filename;
    if (file_exists($fn_src)) {
        if (file_exists($fn_dest)) {
            $errmsg = "SNAPSHOT Data File Already Exists, Over-writing! ({$fn_src} -> {$fn_dest})";
            ErrorLog($errmsg, 2);
        }
        copy($fn_src, $fn_dest);
        // Remove the original ONLY if it copies
        if (file_exists($fn_dest)) {
            unlink($fn_src);
        }
    }
    die(_ERR_RESPONSE . $errmsg);
}

//if ($data["gm"] > 2 OR $data["gm"] < 0) {
if (0) {
    $errmsg = "SNAPSHOT Data NOT True GameMod!";
    ErrorLog($errmsg, 1);
    $fn_src = chkPath($cfg->get('stats_logs')) . $stats_filename;
    if (file_exists(chkPath($cfg->get('stats_logs')) . "bad/GAMEMOD/" . $ip_s)) {
        
    } else {
        mkdir(chkPath($cfg->get('stats_logs')) . "bad/GAMEMOD/" . $ip_s, 0777);
    }
    $fn_dest = chkPath($cfg->get('stats_logs')) . "bad/GAMEMOD/" . $ip_s . "/" . $stats_filename;
    if (file_exists($fn_src)) {
        if (file_exists($fn_dest)) {
            $errmsg = "SNAPSHOT Data File Already Exists, Over-writing! ({$fn_src} -> {$fn_dest})";
            ErrorLog($errmsg, 2);
        }
        copy($fn_src, $fn_dest);
        // Remove the original ONLY if it copies
        if (file_exists($fn_dest)) {
            unlink($fn_src);
        }
    }
    die(_ERR_RESPONSE . $errmsg);
}




// SNAPSHOT Data OK
$errmsg = "SNAPSHOT Data Complete ({$mapname}:{$mapdate})";
ErrorLog($errmsg, 3);

// Create SNAPSHOT backup file
//if ($data['import'] != 1)
//{
// Tell the game server that the snapshot has been received
$out = "O\n" .
        "H\tresponse\tmapname\tmapstart\n" .
        "D\tOK\t" . $mapname . "\t" . $data['mapstart'] . "\n";
echo $out . "$\tOK\t$";
flush();
//}
//////////////////////////////////////////////////////
$connection = @mysql_connect($db_host, $db_user, $db_pass);
@mysql_select_db($db_name, $connection);
//////////////////////////////////////////////////////
// Global variables
$globals = array();
//Determine Round Time
$globals['roundtime'] = $data['mapend'] - $data['mapstart'];
// Initialise Other Global Data
$globals['mapscore'] = $globals['mapkills'] = $globals['mapdeaths'] = 0;
$globals['team1_pids'] = $globals['team2_pids'] = 0;   // Team Player Counts
$globals['team1_pids_end'] = $globals['team2_pids_end'] = 0; // Team Player Counts
$globals['custommap'] = 0;
// Determine GameMode
$globals['mode0'] = 0; // Mode: gpm_cq	= Conquest
$globals['mode1'] = 1; // Mode: gpm_ti	= Titan
$globals['mode2'] = 2; // Mode: gpm_sl	= Supply Lines
$globals['mode3'] = 3; // Mode: gpm_coop	= Co-op (ie, 'Bots)
if (isset($data["gm"])) {
    // Unknown will get set to 99, which effectively ignores this mode
    $globals["mode" . $data["gm"]] = 1;
    if ($data["gm"] == 3) {
        $data["gm"] = 0;
    }
}

//Sinth Comment
//if (isset($data["gm"]) AND $data["gm"] > 1) {
//    $data["gm"] = 0;
//}
// Check if this is a Central DB Snapshot update
/*
  if (isset($data["cdb_update"])) {
  $centralupdate = $data["cdb_update"];
  ErrorLog("Central SNAPSHOT Update Type: $centralupdate",3);
  } else {
  $centralupdate = 0;
  }
 */
// Minimum player & time check
if ($data['pc'] >= $cfg->get('stats_players_min') && $globals['roundtime'] >= $cfg->get('stats_min_game_time')) {

    ErrorLog("Begin Processing ($mapname)...", 3);

    /*     * ******************************
     * Check for 'Custom Map'
     * ****************************** */
    if ($data['m'] == 99) {
        // Set Custom Map Bit
        $globals['custommap'] = 1;
        // Check for existing data
        $query = "SELECT id FROM mapinfo WHERE name = '{$mapname}'";
        $result = mysql_query($query);
        checkSQLResult($result, $query);
        if (mysql_num_rows($result)) {
            // Get Existing MapID#
            $rowmapid = mysql_fetch_array($result);
            $mapid = $rowmapid['id'];
            ErrorLog(" - Existing Custom Map ($mapid)...", 3);
        } else {
            // Get next Map ID#
            $query = "SELECT MAX(id) as `id` FROM mapinfo WHERE id >= " . $cfg->get('game_custom_mapid');
            $result = mysql_query($query);
            checkSQLResult($result, $query);
            if (mysql_num_rows($result) == 1) {
                $rowmapid = mysql_fetch_array($result);
                if (is_null($rowmapid['id']) || $rowmapid['id'] < $cfg->get('game_custom_mapid')) {
                    $mapid = $cfg->get('game_custom_mapid');
                } else {
                    $mapid = $rowmapid['id'] + 1;
                }
            } else {
                $mapid = $cfg->get('game_custom_mapid');
            }
            ErrorLog(" - New Custom Map ($mapid)...", 2);
        }
    } elseif ($data['m'] >= $cfg->get('game_custom_mapid')) {
        // Set Custom Map Bit
        $globals['custommap'] = 1;
        $mapid = $data['m'];
        ErrorLog(" - Predefined Custom Map ($mapid)...", 3);
    } else {
        $mapid = $data['m'];
        ErrorLog(" - Standard Map ($mapid)...", 3);
    }

    ErrorLog("Found {$data['pc']} Player(s)...", 3);

    /*     * ******************************
     * Process 'Player Data'
     * ****************************** */
    $totalplayers = $data['pc'];
    for ($x = 0; $x < $totalplayers; $x++) {
        //echo "[".$data["pid_$x"].", ".$data["ctime_$x"].">=".$cfg->get('stats_min_player_game_time')."] ";
        // Check player exisits in SNAPSHOT and that they meet the minimum required play time
        if (isset($data["pid_$x"]) AND $data["pid_$x"] != "" AND ($data["tt_$x"] >= $cfg->get('stats_min_player_game_time'))) {
            if ($data["pid_$x"] > 0) {
                // Set global variables
                $globals['mapscore'] += $data["gsco_$x"];
                $globals['mapkills'] += $data["klls_$x"];
                $globals['mapdeaths'] += $data["dths_$x"];

                /*                 * ******************************
                 * Process 'Player'
                 * ****************************** */
                ErrorLog("Processing Player (" . $data["pid_$x"] . ")", 3);
                $query1 = "SELECT * FROM subaccount s " .
                        " LEFT JOIN `playerprogress` p ON p.pid=s.id" .
                        " LEFT JOIN `stats_a` a ON a.pid=p.pid" .
                        " LEFT JOIN `stats_e` e ON e.pid=p.pid" .
                        " LEFT JOIN `stats_m` m ON m.pid=p.pid" .
                        " LEFT JOIN `stats_v` v ON v.pid=p.pid" .
                        " LEFT JOIN `stats_w` w ON w.pid=p.pid" .
                        " WHERE s.id=" . $data["pid_$x"] . " AND a._gm=" . $data["gm"] . " AND a._date='" . $currdate . "' LIMIT 1";
                $result1 = mysql_query($query1);
                checkSQLResult($result1, $query1);
                if (!mysql_num_rows($result1)) {
                    $query1 = "SELECT * FROM subaccount s " .
                            " LEFT JOIN `playerprogress` p ON p.pid=s.id" .
                            " LEFT JOIN `stats_a` a ON a.pid=p.pid" .
                            " LEFT JOIN `stats_e` e ON e.pid=p.pid" .
                            " LEFT JOIN `stats_m` m ON m.pid=p.pid" .
                            " LEFT JOIN `stats_v` v ON v.pid=p.pid" .
                            " LEFT JOIN `stats_w` w ON w.pid=p.pid" .
                            " WHERE s.id=" . $data["pid_$x"] . " LIMIT 1";
                    $result1 = mysql_query($query1);

                    checkSQLResult($result1, $query1);
                }

                if (!mysql_num_rows($result1)) {
                    ErrorLog("Player (" . $data["pid_$x"] . ") not found in `subaccount`.", 3);
                    continue;
                }
                $data2 = mysql_fetch_assoc($result1);

                //AND m.gm=".$data["gm"]." AND m.mapid=".$data["m"]." 
                $query = "SELECT * from stats_m m WHERE m.pid = " . $data["pid_$x"] . " AND m.gm=" . $data["gm"] . " AND m.mapid=" . $data["m"] . " LIMIT 1";
                $res = mysql_query($query);
                $dataMap = mysql_fetch_assoc($res);


                $query3p = "";
                $query3a = "";
                $query3w = "";
                $query3e = "";
                $query3v = "";
                $query3m = "";

                if ($data["c_$x"]) {
                    $complete = 1;
                } else {
                    $complete = 0;
                }

                //gsco	Global Score --- for rankup and disconnect
                //awybt	--- for rankup and disconnect
                //bnspt	for rankup and disconnect
                //expts	for rankup and disconnect
                //akl	???
                //avcred	???
                //bp-1	???
                //ent	???
                //ent-1	???
                //ent-2	???
                //ent-3	???
                //hkl	???
                //klsk	???
                //md	???
                //sasl	???
                //tid	???
                //unavl	???
                //unlc	???
                //vet	???
                //date               

                $query3a .= " `_date`='" . $currdate . "',";


                //nick
                if (!isset($data2['nick'])) {
                    $query3p .= " `nick`='" . $data["nick_$x"] . "',";
                }

                //ban	total bans na server
                if (isset($data["ban_$x"]) AND $data["ban_$x"] > 0) {
                    $query3p .= " `ban`=(`ban`+" . $data["ban_$x"] . "),";
                }
                //kick	total kicks from servers
                if (isset($data["kick_$x"]) AND $data["kick_$x"] > 0) {
                    $query3p .= " `kick`=(`kick`+" . $data["kick_$x"] . "),";
                }
                //dass	Driver Assists
                if (isset($data["dass_$x"]) AND $data["dass_$x"] > 0) {
                    $query3p .= " `dass`=(`dass`+" . $data["dass_$x"] . "),";
                }
                //capa	Capture Assists CPs
                //capams	Missile Assists Silos
                if (isset($data["capa_$x"]) AND $data["capa_$x"] > 0) {
//Sinth commented                    
//                    if ($data['gm'] != 1) {
//                        $query3a .= " `capa`=(`capa`+" . $data["capa_$x"] . "),";
//                    } else {
//                        $query3a .= " `capams`=(`capams`+" . $data["capa_$x"] . "),";
//                    }
                    $query3p .= " `capa`=(`capa`+" . $data["capa_$x"] . "),";
                    $query3a .= " `_capa`=(`_capa`+" . $data["capa_$x"] . "),";
                }
                //cpt	Captured CPs                
                if (isset($data["cpt_$x"]) AND $data["cpt_$x"] > 0) {
//Sinth commented                    
//                    if ($data['gm'] != 1) {
//                        $query3a .= " `cpt`=(`cpt`+" . $data["cpt_$x"] . "),";
//                    } else {
//                        $query3a .= " `cts`=(`cts`+" . $data["cpt_$x"] . "),";
//                    }
                    $query3p .= " `cpt`=(`cpt`+" . $data["cpt_$x"] . "),";
                    $query3a .= " `_cpt`=(`_cpt`+" . $data["cpt_$x"] . "),";
                }

                //cts Captured Missile Silos
                if (isset($data["cpt_$x"]) AND $data["cpt_$x"] > 0 AND $data["gm"] == 1) {
                    $query3p .= " `cts`=(`cts`+" . $data["cpt_$x"] . "),";
                }

                //ncpt	Neutralized CPs
                //nmst	Neutralized Missile Silos
                if (isset($data["ncpt_$x"]) AND $data["ncpt_$x"] > 0) {
//Sinth Commented                    
//                    if ($data['gm'] != 1) {
//                        $query3a .= " `ncpt`=(`ncpt`+" . $data["ncpt_$x"] . "),";
//                    } else {
//                        $query3a .= " `nmst`=(`nmst`+" . $data["ncpt_$x"] . "),";
//                    }
                    $query3p .= " `ncpt`=(`ncpt`+" . $data["ncpt_$x"] . "),";
                }
                //dcpt	Defended CPs
                //dmst	Defended Missile Silos
                if (isset($data["dcpt_$x"]) AND $data["dcpt_$x"] > 0) {
//Sinth Commented                    
//                    if ($data['gm'] != 1) {
//                        $query3a .= " `dcpt`=(`dcpt`+" . $data["dcpt_$x"] . "),";
//                    } else {
//                        $query3a .= " `dmst`=(`dmst`+" . $data["dcpt_$x"] . "),";
//                    }
                    $query3p .= " `dcpt`=(`dcpt`+" . $data["dcpt_$x"] . "),";
                    $query3a .= " `_dcpt`=(`_dcpt`+" . $data["dcpt_$x"] . "),";
                }

                //gsco	Global Score
                if (isset($data["gsco_$x"]) AND $data["gsco_$x"] > 0) {
                    $query3p .= " `gsco`=(`gsco`+" . $data["gsco_$x"] . "),";
                    $query3a .= " `_gsco`=(`_gsco`+" . $data["gsco_$x"] . "),";
//Map data
                    //$query3m .= " `msc-" . $data['gm'] . "-" . $data['m'] . "`=(`msc-" . $data['gm'] . "-" . $data['m'] . "`+" . $data["gsco_$x"] . "),";
                    $query3m .= " `gm`=" . $data['gm'] . ", `mapid`=" . $data['m'] . ", `msc`=`msc`+" . $data["gsco_$x"] . ",";

                    //$m_array = array("mbr","mlos","msc","mtt","mwin");
                    // "mbr-".$data['gm']."-".$data['m'];
                    if ($dataMap["mbr"] < $data["gsco_$x"]) {
                        $query3m .= " `mbr`='" . $data["gsco_$x"] . "',";
                    }
                }
                //crpt	Career Point
                if (isset($data["crpt_$x"]) AND $data["crpt_$x"] > 0) {
                    $query3p .= " `crpt`=(`crpt`+" . $data["crpt_$x"] . "),";
                    $query3a .= " `_crpt`=(`_crpt`+" . $data["crpt_$x"] . "),";
                }
                //cs	Commander Score
                //csgpm-0	Conquest Commander Score
                //csgpm-1	Titan Commander Score
                if (isset($data["cs_$x"]) AND $data["cs_$x"] > 0) {
                    $query3p .= " `cs`=(`cs`+" . $data["cs_$x"] . "),";
//Sinth Commented                    
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `csgpm-0`=(`csgpm-0`+" . $data["cs_$x"] . "),";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `csgpm-1`=(`csgpm-1`+" . $data["cs_$x"] . "),";
//                    }
                }
                //twsc	Teamwork Score
                if (isset($data["twsc_$x"]) AND $data["twsc_$x"] > 0) {
                    $query3p .= " `twsc`=(`twsc`+" . $data["twsc_$x"] . "),";
                    $query3a .= " `_twsc`=(`_twsc`+" . $data["twsc_$x"] . "),";
                }
                //brs	Best Round Score
                if (!isset($data2["brs"]) || $data2["brs"] < $data["gsco"] AND $data["gsco_$x"] > 0) {
                    $query3p .= " `brs`='" . $data["gsco_$x"] . "',";
                }
                //pdt	Unique Dog Tags Collected
                //pdtc	Dog Tags Collected
                if (isset($data["pdt_$x"]) AND $data["pdt_$x"] > 0) {
                    $query3p .= " `pdt`=(`pdtc`+" . $data["pdt_$x"] . "),";
                }
                if (isset($data["pdtc_$x"]) AND $data["pdtc_$x"] > 0) {
                    $query3p .= " `pdtc`=(`pdtc`+" . $data["pdtc_$x"] . "),";
                }

                //hls	Heals
                if (isset($data["hls_$x"]) AND $data["hls_$x"] > 0) {
                    $query3p .= " `hls`=(`hls`+" . $data["hls_$x"] . "),";
                    $query3a .= " `_hls`=(`_hls`+" . $data["hls_$x"] . "),";
                }
                //rvs	Revives
                if (isset($data["rvs_$x"]) AND $data["rvs_$x"] > 0) {
                    $query3p .= " `rvs`=(`rvs`+" . $data["rvs_$x"] . "),";
                    $query3a .= " `_rvs`=(`_rvs`+" . $data["rvs_$x"] . "),";
                }
                //rps	Repairs
                if (isset($data["rps_$x"]) AND $data["rps_$x"] > 0) {
                    $query3p .= " `rps`=(`rps`+" . $data["rps_$x"] . "),";
                    $query3a .= " `_rps`=(`_rps`+" . $data["rps_$x"] . "),";
                }
                //resp	Re-supplies
                if (isset($data["resp_$x"]) AND $data["resp_$x"] > 0) {
                    $query3p .= " `resp`=(`resp`+" . $data["resp_$x"] . "),";
                    $query3a .= " `_resp`=(`_resp`+" . $data["resp_$x"] . "),";
                }

                //rnk	Rank
                if (isset($data["rnk_$x"]) && $data["rnk_$x"] != 0) {
                    if ($data2['rnk'] < 41) {
                        $query3p .= " `rnk`='" . $data["rnk_$x"] . "',";
                    }
                    if ($data["rnk_$x"] > ($data2["unavl"] + $data2["unlc"])) {
                        $query3p .= " `unavl`='" . ($data["rnk_$x"] - $data2["unlc"] - $data2["unavl"]) . "',";
                    }
                }

                //rnkcg	RankUp?
                if (isset($data["rnkcg_$x"])) {
                    $query3p .= " `rnkcg`='" . $data["rnkcg_$x"] . "',";
                }

                //slbcn	Spawn Beacons Deployed
                if (isset($data["slbcn_$x"]) AND $data["slbcn_$x"] > 0) {
                    $query3p .= " `slbcn`=(`slbcn`+" . $data["slbcn_$x"] . "),";
                }
                //sluav	Spawn Dron Deployed
                if (isset($data["sluav_$x"]) AND $data["sluav_$x"] > 0) {
                    $query3p .= " `sluav`=(`sluav`+" . $data["sluav_$x"] . "),";
                }
                //slbspn	Spawns On Squad Beacons
                if (isset($data["slbspn_$x"]) AND $data["slbspn_$x"] > 0) {
                    $query3p .= " `slbspn`=(`slbspn`+" . $data["slbspn_$x"] . "),";
                }
                //slpts	points za zrozeni na SLS BEACONu
                if (isset($data["slpts_$x"]) AND $data["slpts_$x"] > 0) {
                    $query3p .= " `slpts`=(`slpts`+" . $data["slpts_$x"] . "),";
                }

                //tac	Time As Commander
                //ctgpm-0	Conquest Commander Time
                //ctgpm-1	Titan Commander Time
                if (isset($data["tac_$x"]) AND $data["tac_$x"] > 0) {
                    $query3p .= " `tac`=(`tac`+" . $data["tac_$x"] . "),";
                    $query3a .= " `_tac`=(`_tac`+" . $data["tac_$x"] . "),";
//Sinth Comm
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `ctgpm-0`=(`ctgpm-0`+" . $data["tac_$x"] . "),";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `ctgpm-0`=(`ctgpm-0`+" . $data["tac_$x"] . "),";
//                    }
                }

                //talw	Time As Lone Wolf
                if (isset($data["talw_$x"]) AND $data["talw_$x"] > 0) {
                    $query3p .= " `talw`=(`talw`+" . $data["talw_$x"] . "),";
                    $query3a .= " `_talw`=(`_talw`+" . $data["talw_$x"] . "),";
                }

                //tasl	Time As Squad Leader
                if (isset($data["tasl_$x"]) AND $data["tasl_$x"] > 0) {
                    $query3p .= " `tasl`=(`tasl`+" . $data["tasl_$x"] . "),";
                    $query3a .= " `_tasl`=(`_tasl`+" . $data["tasl_$x"] . "),";
                }

                //tasm	Time As Squad Member
                if (isset($data["tasm_$x"]) AND $data["tasm_$x"] > 0) {
                    $query3p .= " `tasm`=(`tasm`+" . $data["tasm_$x"] . "),";
                    $query3a .= " `_tasm`=(`_tasm`+" . $data["tasm_$x"] . "),";
                }


                //tas	Titan Attack Score
                if (isset($data["tas_$x"]) AND $data["tas_$x"] > 0) {
                    $query3p .= " `tas`=(`tas`+" . $data["tas_$x"] . "),";
                }
                //tcd	Titan Components Destroyed
                if (isset($data["tcd_$x"]) AND $data["tcd_$x"] > 0) {
                    $query3p .= " `tcd`=(`tcd`+" . $data["tcd_$x"] . "),";
                }
                //tcrd	Titan Cores Destroyed
                if (isset($data["tcrd_$x"]) AND $data["tcrd_$x"] > 0) {
                    $query3p .= " `tcrd`=(`tcrd`+" . $data["tcrd_$x"] . "),";
                }
                //tdrps	Titan Drops
                if (isset($data["tdrps_$x"]) AND $data["tdrps_$x"] > 0) {
                    $query3p .= " `tdrps`=(`tdrps`+" . $data["tdrps_$x"] . "),";
                }
                //tds	Titan Defend Score
                if (isset($data["tds_$x"]) AND $data["tds_$x"] > 0) {
                    $query3p .= " `tds`=(`tds`+" . $data["tds_$x"] . "),";
                }
                //tgd	Titan Guns Destroyed
                if (isset($data["tgd_$x"]) AND $data["tgd_$x"] > 0) {
                    $query3p .= " `tgd`=(`tgd`+" . $data["tgd_$x"] . "),";
                }
                //tgr	Titan Guns Repaired
                if (isset($data["tgr_$x"]) AND $data["tgr_$x"] > 0) {
                    $query3p .= " `tgr`=(`tgr`+" . $data["tgr_$x"] . "),";
                }

                //win	Wins
                //los	Losses
                $wins = $data2['win'];
                $losses = $data2['los'];
                $pWin = false;
                if ($data["c_$x"]) {
                    if ($data['win'] == $data["t_$x"]) {
                        $query3p .= " `wins`=(`wins`+1),";
                        $query3a .= " `_wins`=(`_wins`+1),";
                        $wins++;
                        $query3m .= " `mwin`=(`mwin` + 1),";
                    } else {
                        $query3p .= " `los`=(`los`+1),";
                        $query3a .= " `_losses`=(`_losses`+1),";
                        $losses++;
                        $query3m .= " `mlos`=(`mlos` + 1),";
                    }
                }
                //wlr	rate wins/losses
                //$query3a .= " `wlr`='" . (($wins / chz($losses)) * 100) . "',";
                //atp	time played in armor
                if (isset($data["atp_$x"]) AND $data["atp_$x"] > 0) {
                    $query3a .= " `_atp`=(`_atp`+" . $data["atp_$x"] . "),";
                }
                //htp	time played in transport
//Sinth comm
//                if (isset($data["htp_$x"]) AND $data["htp_$x"] > 0) {
//                    $query3a .= " `htp`=(`htp`+" . $data["htp_$x"] . "),";
//                }
                //tt	Time Played
                //ttp	Titan Time Played
                //tgpm-0	Conquest Time
                //tgpm-1	Titan Time
                if (isset($data["tt_$x"]) AND $data["tt_$x"] > 0) {
                    $query3p .= " `tt`=(`tt`+" . $data["tt_$x"] . "),";
                    $query3a .= " `_ttp`=(`_ttp`+" . $data["tt_$x"] . "),";
//Sinth comm                    
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `tgpm-0`=(`tgpm-0`+" . $data["tt_$x"] . "),";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `ttp`=(`ttp`+" . $data["tt_$x"] . "),";
//                        $query3a .= " `tgpm-1`=(`tgpm-1`+" . $data["tt_$x"] . "),";
//                    }
                    $query3m .= " `mtt`=(`mtt`+" . $data["tt_$x"] . "),";
                }
                //spm	Score Per Minute
                $query3p .= " `spm`='" . (($data2["gsco"] + $data["gsco_$x"]) / chz(($data2["tt"] + $data["tt_$x"]) / 60)) . "',";
                $query3a .= " `_spm`='" . (($data2["_gsco"] + $data["gsco_$x"]) / chz(($data2["_ttp"] + $data["tt_$x"]) / 60)) . "',";


                //Played for Team
                $query3a .= " `_t`='" . $data["t_$x"] . "',";

                //attp-0	time played za EU
                //attp-1	time played za PAC
//Sinth Comm
//                if ($data["t_$x"] == 2) {
//                    //EU
//                    $query3a .= " `attp-0`=(`attp-0`+1),";
//                    if ($data['win'] == $data["t_$x"]) {
//                        $query3a .= " `awin-0`=(`awin-0`+1),";
//                    }
//                } elseif ($data["t_$x"] == 1) {
//                    //PAC
//                    $query3a .= " `attp-1`=(`attp-1`+1),";
//                    if ($data['win'] == $data["t_$x"]) {
//                        $query3a .= " `awin-1`=(`awin-1`+1),";
//                    }
//                }
                //trpm-0	Conquest Rounds Played
                //trpm-1	Titan Rounds Played
                //trp	Titan Rounds Played
                $query3a .= " `_gm`=" . $data['gm'] . ",";
//Sinth comm
                if ($data['gm'] == 1) {
//                    $query3a .= " `trpm-0`=(`trpm-0`+1),";
//                } elseif ($data['gm'] == 1) {
                     $query3p .= " `trp`=(`trp`+1),";  
//                    $query3a .= " `trpm-1`=(`trpm-1`+1),";
                }
                //kdths-0	deads as Recon
                if (isset($data["kdths-0_$x"]) AND $data["kdths-0_$x"] > 0) {
                    $query3p .= " `kdths-0`=(`kdths-0`+" . $data["kdths-0_$x"] . "),";
                }
                //kdths-1	deads as Assault
                if (isset($data["kdths-1_$x"]) AND $data["kdths-1_$x"] > 0) {
                    $query3p .= " `kdths-1`=(`kdths-1`+" . $data["kdths-1_$x"] . "),";
                }
                //kdths-2	deads as Engineer
                if (isset($data["kdths-2_$x"]) AND $data["kdths-2_$x"] > 0) {
                    $query3p .= " `kdths-2`=(`kdths-2`+" . $data["kdths-2_$x"] . "),";
                }
                //kdths-3	deads as Support
                if (isset($data["kdths-3_$x"]) AND $data["kdths-3_$x"] > 0) {
                    $query3p .= " `kdths-3`=(`kdths-3`+" . $data["kdths-3_$x"] . "),";
                }
                //kkls-0	Kills As Recon
                if (isset($data["kkls-0_$x"]) AND $data["kkls-0_$x"] > 0) {
                    $query3p .= " `kkls-0`=(`kkls-0`+" . $data["kkls-0_$x"] . "),";
                }
                //kkls-1	Kills As Assault
                if (isset($data["kkls-1_$x"]) AND $data["kkls-1_$x"] > 0) {
                    $query3p .= " `kkls-1`=(`kkls-1`+" . $data["kkls-1_$x"] . "),";
                }
                //kkls-2	Kills As Engineer
                if (isset($data["kkls-2_$x"]) AND $data["kkls-2_$x"] > 0) {
                    $query3p .= " `kkls-2`=(`kkls-2`+" . $data["kkls-2_$x"] . "),";
                }
                //kkls-3	Kills As Support
                if (isset($data["kkls-3_$x"]) AND $data["kkls-3_$x"] > 0) {
                    $query3p .= " `kkls-3`=(`kkls-3`+" . $data["kkls-3_$x"] . "),";
                }
                //ktt-0	Time As Recon
                if (isset($data["ktt-0_$x"]) AND $data["ktt-0_$x"] > 0) {
                    $query3p .= " `ktt-0`=(`ktt-0`+" . $data["ktt-0_$x"] . "),";
                }
                //ktt-1	Time As Assault
                if (isset($data["ktt-1_$x"]) AND $data["ktt-1_$x"] > 0) {
                    $query3p .= " `ktt-1`=(`ktt-1`+" . $data["ktt-1_$x"] . "),";
                }
                //ktt-2	Time As Engineer
                if (isset($data["ktt-2_$x"]) AND $data["ktt-2_$x"] > 0) {
                    $query3p .= " `ktt-2`=(`ktt-2`+" . $data["ktt-2_$x"] . "),";
                }
                //ktt-3	Time As Support
                if (isset($data["ktt-3_$x"]) AND $data["ktt-3_$x"] > 0) {
                    $query3p .= " `ktt-3`=(`ktt-3`+" . $data["ktt-3_$x"] . "),";
                }

                //klls	Kills
                //kgpm-0	Conquest Kills
                //kgpm-1	Titan Kills
                if (isset($data["klls_$x"]) AND $data["klls_$x"] > 0) {
                    $query3p .= " `klls`=(`klls`+" . $data["klls_$x"] . "),";
                    $query3a .= " `_klls`=(`_klls`+" . $data["klls_$x"] . "),";
//Sinth comm                    
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `kgpm-0`=(`kgpm-0`+" . $data["klls_$x"] . "),";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `kgpm-1`=(`kgpm-1`+" . $data["klls_$x"] . "),";
//                    }
                }

                //kpm	Kills Per Minute (*100)
                $query3p .= " `kpm`='" . (($data2["klls"] + $data["klls_$x"]) / chz(($data2["tt"] + $data["tt_$x"]) / 60)) . "',";
                $query3a .= " `_kpm`='" . (($data2["_klls"] + $data["klls_$x"]) / chz(($data2["_ttp"] + $data["tt_$x"]) / 60)) . "',";


                //klla	Kill Assists
                if (isset($data["klla_$x"]) AND $data["klla_$x"] > 0) {
                    $query3p .= " `klla`=(`klla`+" . $data["klla_$x"] . "),";
                }

                //klse	Kills by explo ??
                //kluav	Kills With Gun Drone
                if (isset($data["kluav_$x"]) AND $data["kluav_$x"] > 0) {
                    $query3p .= " `kluav`=(`kluav`+" . $data["kluav_$x"] . "),";
                }

                //klstrk	Kills Streak
                //bksgpm-0	Conquest Best kill streak
                //bksgpm-1	Titan Best kill streak
                if (isset($data["klstrk_$x"]) AND $data["klstrk_$x"] > 0 AND $data2['klstrk'] < $data["klstrk_$x"]) {
                    $query3p .= " `klstrk`='" . $data["klstrk_$x"] . "',";
//Sinth comm                    
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `bksgpm-0`='" . $data["klstrk_$x"] . "',";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `bksgpm-1`='" . $data["klstrk_$x"] . "',";
//                    }
                }

                //tkls	Team Kills
                if (isset($data["tkls_$x"]) AND $data["tkls_$x"] > 0) {
                    $query3p .= " `tkls`=(`tkls`+" . $data["tkls_$x"] . "),";
                }

                //tdmg	Team Damage
                if (isset($data["tdmg_$x"]) AND $data["tdmg_$x"] > 0) {
                    $query3p .= " `tdmg`=(`tdmg`+" . $data["tdmg_$x"] . "),";
                }
                //tvdmg	Team Vehicle Damage
                if (isset($data["tvdmg_$x"]) AND $data["tvdmg_$x"] > 0) {
                    $query3p .= " `tvdmg`=(`tvdmg`+" . $data["tvdmg_$x"] . "),";
                }

                //dths	Deaths
                //dgpm-0	total deads in modu Conquest
                //dgpm-1	total deads in modu Titan
                if (isset($data["dths_$x"]) AND $data["dths_$x"] > 0) {
                    $query3p .= " `dths`=(`dths`+" . $data["dths_$x"] . "),";
                    $query3a .= " `_dths`=(`_dths`+" . $data["dths_$x"] . "),";
//Sinth commented                    
//                    if ($data['gm'] == 0) {
//                        $query3a .= " `dgpm-0`=(`dgpm-0`+" . $data["dths_$x"] . "),";
//                    } elseif ($data['gm'] == 1) {
//                        $query3a .= " `dgpm-1`=(`dgpm-1`+" . $data["dths_$x"] . "),";
//                    }
                }

                //dpm	Deaths Per Minute
                $query3p .= " `dpm`='" . (($data2["dths"] + $data["dths_$x"]) / chz(($data2["tt"] + $data["tt_$x"]) / 60)) . "',";
                $query3a .= " `_dpm`='" . (($data2["_dths"] + $data["dths_$x"]) / chz(($data2["_ttp"] + $data["tt_$x"]) / 60)) . "',";

                //adpr	Deaths Per Round
                //$query3a .= " `adpr`='" . (($data2["dths"] + $data["dths_$x"]) / chz($data2['trpm-0'] + $data2['trpm-1']) * 100) . "',";
                //dstrk	Worst Death Streak
                if (isset($data["dstrk_$x"]) AND $data["dstrk_$x"] > 0 AND $data2['dstrk'] < $data["dstrk_$x"]) {
                    $query3p .= " `dstrk`='" . $data["dstrk_$x"] . "',";
                }

                //kdr	Kills/Deaths Ratio
                $query3p .= " `kdr`='" . (($data2["klls"] + $data["klls_$x"]) / chz(($data2["dths"] + $data["dths_$x"]))) . "',";

                //suic	Suicides
                if (isset($data["suic_$x"]) AND $data["suic_$x"] > 0) {
                    $query3p .= " `suic`=(`suic`+" . $data["suic_$x"] . "),";
                }

                //toth	Total Hits
                if (isset($data["toth_$x"]) AND $data["toth_$x"] > 0) {
                    $query3p .= " `toth`=(`toth`+" . $data["toth_$x"] . "),";
                    $query3a .= " `_toth`=(`_toth`+" . $data["toth_$x"] . "),";
                }
                //tots	Total Fired
                if (isset($data["tots_$x"]) AND $data["tots_$x"] > 0) {
                    $query3p .= " `tots`=(`tots`+" . $data["tots_$x"] . "),";
                    $query3a .= " `_tots`=(`_tots`+" . $data["tots_$x"] . "),";
                }

                //fe	Favorite Equipment
                //fgm	Favorite Game Mode
                //fk	Favorite Kit
                //fm	Favorite Map
                //fv	Favorite Vehicle
                //fw	Favorite Weapon
                //ovaccu	Accuracy (*100)
                $query3p .= " `ovaccu`='" . (($data2["toth"] + $data["toth_$x"]) / chz(($data2["tots"] + $data["tots_$x"]))) . "',";
                $query3a .= " `_ovaccu`='" . (($data2["_toth"] + $data["toth_$x"]) / chz(($data2["_tots"] + $data["tots_$x"]))) . "',";

                //lgdt Time of last game
                $query3p .= " `lgdt`='" . intval($data["mapend"]) . "',";
                $query3a .= " `_lgdt`='" . intval($data["mapend"]) . "',";

                //--------------------------------------------------------------------------------------------------------------------
                $w_array = array("wdths", "wkls", "wtp", "wbf", "wbh"); // "waccu-",
                for ($w = 0; $w <49; $w++) {
                    foreach ($w_array as $w_temp) {
                        if (isset($data[$w_temp . "-" . $w . "_" . $x]) AND $data[$w_temp . "-" . $w . "_" . $x] != 0) {
                            $query3w .= " `$w_temp-$w`=(`$w_temp-$w` + " . $data[$w_temp . "-" . $w . "_" . $x] . "),";
                        }
                    }
                    if ($data["wbf-" . $w . "_" . $x] > 0 OR $data["wbh-" . $w . "_" . $x] > 0) {
                        $query3w .= " `waccu-$w`='" . (($data2["wbh-" . $w] + $data["wbh-" . $w . "_" . $x]) / chz($data2["wbf-" . $w] + $data["wbf-" . $w . "_" . $x])) . "',";
                    }
                }
                //echo "======".$query3w."=======";
                //--------------------------------------------------------------------------------------------------------------------
                $e_array = array("kdths", "kkls", "ktt");
                for ($e = 0; $e < 4; $e++) {
                    foreach ($e_array as $e_temp) {
                        if (isset($data[$e_temp . "-" . $e . "_" . $x]) AND $data[$e_temp . "-" . $e . "_" . $x] != 0) {
                            $query3e .= " `$e_temp-$e`=(`$e_temp-$e` + " . $data[$e_temp . "-" . $e . "_" . $x] . "),";
                        }
                    }
                }
                //--------------------------------------------------------------------------------------------------------------------
                $v_array = array("vdths", "vkls", "vrkls", "vtp", "vbf", "vbh", "vdstry"); // "vaccu-",
                for ($v = 0; $v < 15; $v++) {
                    foreach ($v_array as $v_temp) {
                        if (isset($data[$v_temp . "-" . $v . "_" . $x]) AND $data[$v_temp . "-" . $v . "_" . $x] != 0) {
                            $query3v .= " `$v_temp-$v`=(`$v_temp-$v` + " . $data[$v_temp . "-" . $v . "_" . $x] . "),";
                        }
                    }
                    if ($data["vbf-" . $v . "_" . $x] > 0 OR $data["vbh-" . $v . "_" . $x] > 0) {
                        $query3v .= " `vaccu-$v`='" . (($data2["vbh-" . $v] + $data["vbh-" . $v . "_" . $x]) / chz($data2["vbf-" . $v] + $data["vbf-" . $v . "_" . $x])) . "',";
                    }
                    if (isset($data["vkls-" . $v . "_" . $x]) && isset($data["vdths-" . $v . "_" . $x]) && $data["vkls-" . $v . "_" . $x] > 0) {
                        if (!$data["vdths-" . $v . "_" . $x]) {
                            $data["vdths-" . $v . "_" . $x] = 1;
                        }
                        $query3v .= " `vkdr-$v`=" . $data["vkls-" . $v . "_" . $x] . "/" . $data["vdths-" . $v . "_" . $x] . ",";
                    } else {
                        $query3v .= " `vkdr-$v`=0,";
                    }
                }
                //--------------------------------------------------------------------------------------------------------------------
                //--------------------------------------------------------------------------------------------------------------------
                //--------------------------------------------------------------------------------------------------------------------
                //Playerprogress
                if ($query3p) {
                    $query = "SELECT * FROM `playerprogress` WHERE pid='" . $data["pid_$x"] . "' LIMIT 1";
                    $res = mysql_query($query);
                    if (!mysql_num_rows($res)) {
                        $query = "INSERT INTO playerprogress SET " . rtrim($query3p, ",") . ", `pid`='" . $data["pid_$x"] . "'";
                    } else {
                        $query = "UPDATE playerprogress SET " . rtrim($query3p, ",") . " WHERE `pid`='" . $data["pid_$x"] . "'";
                    }
                    $res = mysql_query($query);
			if (!$res) {
if ($LOG) {
$fp = fopen("logs/error".time().".txt","a+");
fwrite($fp,$query."
".mysql_error());
fflush($fp);
fclose($fp);
}
}


                }

                if ($query3a) {
                    //stats_a
                    $query = "SELECT * FROM `stats_a` WHERE pid='" . $data["pid_$x"] . "' AND _gm='" . $data["gm"] . "' AND _date='" . $currdate . "' LIMIT 1";
                    $res = mysql_query($query);
                    if (!mysql_num_rows($res)) {
                        $query = "INSERT INTO stats_a SET " . rtrim($query3a, ",") . ", `pid`='" . $data["pid_$x"] . "'";
                    } else {
                        $query = "UPDATE stats_a SET " . rtrim($query3a, ",") . " WHERE `pid`='" . $data["pid_$x"] . "' AND _gm='" . $data["gm"] . "' AND _date='" . $currdate . "'";
                    }
                    $res = mysql_query($query);
                }

                if ($query3m) {
                    //stats_m
                    $query = "SELECT * from stats_m m WHERE pid='" . $data["pid_$x"] . "' AND m.gm=" . $data["gm"] . " AND m.mapid=" . $data["m"] . " LIMIT 1";
                    $res = mysql_query($query);
                    if (!mysql_num_rows($res)) {
                        $query = "INSERT INTO stats_m SET " . rtrim($query3m, ",") . ", `pid`='" . $data["pid_$x"] . "'";
                    } else {
                        $query = "UPDATE stats_m SET " . rtrim($query3m, ",") . " WHERE `pid`='" . $data["pid_$x"] . "' AND gm='" . $data["gm"] . "'";
                    }
                    $res = mysql_query($query);
                }

                $table_array = array("w", "e", "v");
                foreach ($table_array as $word_) {
                    $query3 = "query3" . $word_;
                    if ($$query3 != "") {
                        //echo "[".$$query3."]";
                        $query2 = "SELECT * FROM `stats_" . $word_ . "` WHERE pid='" . $data["pid_$x"] . "' LIMIT 1";
                        $result2 = mysql_query($query2);
                        checkSQLResult($result2, $query2);
                        if (!mysql_num_rows($result2)) {
                            ErrorLog("Player (" . $data["pid_$x"] . ") not found in `stats_" . $word_ . "` - make new.", 3);
                            $$query3 = "INSERT INTO stats_" . $word_ . " SET " . rtrim($$query3, ",") . ", `pid`='" . $data["pid_$x"] . "'";
                        } else {
                            ErrorLog("Player (" . $data["pid_$x"] . ") found in `stats_" . $word_ . "` - make update.", 3);
                            $$query3 = rtrim($$query3, ",");
                            $$query3 = "UPDATE stats_" . $word_ . " SET " . rtrim($$query3, ",") . " WHERE `pid`='" . $data["pid_$x"] . "'";
                        }
                        if ($allow_db_changes)
                            $result3 = mysql_query($$query3);
                        else if ($allow_db_show)
                            echo '<br />' . $$query3 . '<br />===<br />';
                        checkSQLResult($result3, $$query3);
                        ErrorLog(">>>>>>>>>>" . $$query3 . "", 3);
                    }
                }

                //Update Playerprogress                
                $query = "SELECT * FROM playerprogress WHERE pid = '" . $data["pid_$x"] . "'";
                $result = mysql_query($query) or die(mysql_error());
                if (mysql_num_rows($result)) {

                    //favorite kit
                    $pp = mysql_fetch_assoc($result);
                    $params = "ktt-0 ktt-1 ktt-2 ktt-3";
                    $params = explode(" ", $params);
                    $fields = array();
                    foreach ($params as $param) {
                        $fields[$param] = $pp[$param];
                    }

                    $fk = mySort($fields, true);


                    $query = "SELECT * FROM stats_m WHERE pid = '" . $data["pid_$x"] . "'";
                    $result = mysql_query($query) or die(mysql_error());
                    if (mysql_num_rows($result)) {
                        while ($pp = mysql_fetch_assoc($result)) {

                            //favorite map
                            if (!$fmp["mapid-" . $pp['mapid']]) {
                                $fmp["mapid-" . $pp['mapid']] = 0;
                            }
                            $fmp["mapid-" . $pp['mapid']]++;

                            //favorite game
                            if ($pp['gm'] == 3) {
                                $pp['gm'] = 0;
                            }
                            if (!$fg[$pp['gm']]) {
                                $fg["gm-" . $pp['gm']] = 0;
                            }
                            $fg["gm-" . $pp['gm']]++;
                        }

                        $fgm = mySort($fg, true);
                        $fm = mySort($fmp, true);
                    }

                    //favorite vehicle
                    $query = "SELECT * FROM stats_v WHERE pid = '" . $data["pid_$x"] . "'";
                    $result = mysql_query($query) or die(mysql_error());
                    if (mysql_num_rows($result)) {



                        $pp = mysql_fetch_assoc($result);
                        $params = "vtp-0 vtp-1 vtp-2 vtp-3 vtp-4 vtp-5 vtp-6 vtp-7 vtp-8 vtp-9 vtp-10 vtp-11 vtp-12 vtp-13 ";
                        $params = explode(" ", $params);
                        $fields = array();
                        foreach ($params as $param) {
                            $fields[$param] = $pp[$param];
                        }

                        $fv = mySort($fields, true);
                    }

                    //favorite weapon
                    $query = "SELECT * FROM stats_w WHERE pid = '" . $data["pid_$x"] . "'";
                    $result = mysql_query($query) or die(mysql_error());
                    if (mysql_num_rows($result)) {



                        $pp = mysql_fetch_assoc($result);
                        $params = "wtp-0 wtp-1 wtp-2 wtp-3 wtp-4 wtp-5 wtp-6 wtp-7 wtp-8 wtp-9 wtp-10 wtp-11 wtp-12 wtp-13 wtp-14 wtp-15 wtp-16 wtp-17 wtp-18 wtp-19 wtp-20 ";
                        $params.= "wtp-21 wtp-22 wtp-23 wtp-24 wtp-25 wtp-26 wtp-27 wtp-28 wtp-29 wtp-30 wtp-31 wtp-32 wtp-33 wtp-34 wtp-35 wtp-36 wtp-37 wtp-38 wtp-39 wtp-40 wtp-41 wtp-42";
                        $params = explode(" ", $params);
                        $fields = array();
                        foreach ($params as $param) {
                            $fields[$param] = $pp[$param];
                        }
                        $fw = mySort($fields, true);
                    }
                    $pquery = "";
                    if ($fgm) {
                        $pquery.= "fgm=" . $fgm . ",";
                    }
                    if ($fm) {
                        $pquery.= "fm=" . $fm . ",";
                    }
                    if ($fk) {
                        $pquery.= "fk=" . $fk . ",";
                    }
                    if ($fv) {
                        $pquery.= "fv=" . $fv . ",";
                    }
                    if ($fw) {
                        $pquery.= "fw=" . $fw . ",";
                    }
                    //Save favorite data
                    $query = "UPDATE playerprogress SET " . rtrim($pquery, ",") . " WHERE `pid`='" . $data["pid_$x"] . "'";
                    $result = mysql_query($query) or die(mysql_error());
                }
                //Rank Check
                $query = "SELECT rnk, crpt from playerprogress WHERE pid = '" . $data["pid_$x"] . "'";
                $result = mysql_query($query) or die(mysql_error());
                $query = "";
                if (mysql_num_rows($result)) {
                    $res = mysql_fetch_assoc($result);
                    foreach ($rankArray as $rid => $ri) {
                        if ($res['crpt'] >= $ri && $res['crpt'] < $rankArray[$rid + 1]) {
                            if ($res['rnk'] < $rid) {                                
                                $rnkcg = $rid - $res['rnk'];
                                $query = "UPDATE playerprogress SET rnkcg = 1, rnk = rnk + " . $rnkcg . " WHERE pid = '" . $data["pid_$x"] . "'";
                            } else {
                                $rnkcg = $rid;
                                $query = "UPDATE playerprogress SET rnk = " . $rnkcg . " WHERE pid = '" . $data["pid_$x"] . "'";
                            }

                            if ($query) {
                                $result = mysql_query($query);
                            }
                            break;
                        }
                    }
                }
            } else {
                ErrorLog("Player with PID: " . $data["pid_$x"] . " => not valid ", 2);
            }
        } else {
            if ($totalplayers < $cfg->get('stats_players_max')) {
                // Data Hole Detected, increment total player count
                $totalplayers++;
                ErrorLog("Data Hole Detected, Player Count now: $totalplayers", 2);
            } else {
                // Too many "data holes" break out!
                ErrorLog("Data Hole Limit Reached: $totalplayers", 1);
                break;
            }
        }
        ErrorLog("End Loop $x", 3);
    }
    /*     * ******************************
     * Process 'Awards'
     * ****************************** */
    ErrorLog("Processing Award Data", 3);
    $medals_array = array();
    foreach ($data as $medal_name => $value) {
        if (preg_match("/^medal/", $medal_name)) {
            preg_match_all("/medal(\w+)_(\d+)/", $medal_name, $test);
            $pawd = $test[1][0];
            $awpid = $test[2][0];
            if (isset($awardsdata[$pawd])) {
                $medals_array[$data['pid_' . $awpid]][$pawd] = $value;
            }
        }
    }
    foreach ($medals_array as $awpid => $pawards) {
        if ($awpid == 0) {
            continue;
        }
        foreach ($pawards as $paward_name => $paward_value) {
            preg_match_all("/(\d)(\d+)/", $awardsdata[$paward_name][0], $test2);
            $atype = $test2[1][0];
            $aid = $test2[2][0];
            $aid *= 1;
            $alvl = $paward_value;
            // Check if Player already has Award
            //if($atype == 1) { $qbage = " AND alvl = ".$alvl.""; } else { $qbage = ""; }
            $query = "SELECT * FROM awards WHERE pid = " . $awpid . " AND atype = " . $atype . " AND aid = " . $aid . " " . $qbage;
         //   echo "<br />".$query;
            $awdresult = mysql_query($query);
            checkSQLResult($awdresult, $query);
            $num_rows = mysql_num_rows($awdresult);
            // Check if player has award
            // Recieveing these awards multiple times is NOT supported...yet!
            if ($atype == 4) {
                $qlvl = ", alvl = " . $alvl;
            } else {
                $qlvl = "";
            }
          // echo "<br/><br/>".$awardsdata['Cep'][1].'<br/><br/>';
            if ($awardsdata[$paward_name][1] == 1) {
             //   echo "[".$atype.$aid.$alvl."=>".$awardsdata[$paward_name][1]."] ".$paward_name."<br/>";
                // NEW multiple or UPDATE multiple//
                if ($num_rows > 0) {
                    $query = "UPDATE awards SET alvl=(alvl+" . $alvl . "), earned = " . time() . " WHERE pid = " . $awpid . " AND atype = " . $atype . " AND aid = " . $aid . "";
                } else {
                    $query = "INSERT INTO awards SET pid = " . $awpid . ", atype = " . $atype . ", aid = " . $aid . ", earned = " . time() . ", first = " . time() . "" . $qlvl;
                }
            //    echo '<br/>'.$query;
                if ($allow_db_changes)
                    $result = mysql_query($query); else if ($allow_db_show)
    //                echo '<br />' . $query . '<br />===<br />';
                checkSQLResult($result, $query);
            } elseif ($awardsdata[$paward_name][1] == 0) {
			//echo "<br/>---[".$atype.$aid.$alvl."=>".$awardsdata[$paward_name][1]."] ".$paward_name."<br/>";
            if ($num_rows == 0) {
	            	$query = "INSERT INTO awards SET pid = " . $awpid . ", atype = " . $atype . ", aid = " . $aid . ", earned = " . time() . ", first = " . time() . "" . $qlvl;
            	} else {
            	if ($atype == 1)
                    $query = "UPDATE awards SET alvl=".$alvl.", earned = " . time() . " WHERE pid = " . $awpid . " AND atype = " . $atype . " AND aid = " . $aid . "";
                    else $query = "";
            	}
           	 if ($allow_db_changes)
                   $result = mysql_query($query) or die(mysql_error()); else if ($allow_db_show)
             //      echo '<br />' . $query . '<br />===<br />';
               checkSQLResult($result, $query);
            }
        }
    }



    /*     * ******************************
     * Process 'Server'
     * ****************************** */
    // Note: Code borrowed from release by ArmEagle (armeagle@gmail.com)
    /*
      $gamesrv_ip   = $_SERVER['REMOTE_ADDR'];
      $gamesrv_name = $_SERVER['REMOTE_HOST'];
      ErrorLog("Processing Game Server: {$gamesrv_ip}",3);
      $gamesrv_port = ($data['gameport']) ? $data['gameport'] : 16567;	//Set to Default if no data
      $gamesrv_queryport = ($data['queryport']) ? $data['queryport'] : 29900;	//Set to Default if no data
      $query = "SELECT * FROM servers WHERE ip = '{$gamesrv_ip}' AND prefix = '{$prefix}'";
      $result = mysql_query($query);
      checkSQLResult ($result, $query);
      if (!mysql_num_rows($result)) {
      $query = "INSERT INTO servers SET ".
      "ip = '{$gamesrv_ip}', ".
      "game = '{$prefix}', ".
      "prefix = '{$prefix}', ".
      "gport = '{$gamesrv_port}', ".
      "port = {$gamesrv_queryport}, ".
      "lastupdate = NOW() ";
      if ($allow_db_changes) $result = mysql_query($query); else if ($allow_db_show) echo '<br />'.$query.'<br />===<br />';
      checkSQLResult ($result, $query);
      $serverid = mysql_insert_id();
      } else {
      $row = mysql_fetch_assoc($result);
      $query = "UPDATE servers SET ".
      "gport = '{$gamesrv_port}', ".
      "port = {$gamesrv_queryport}, ".
      "lastupdate = NOW() ".
      "WHERE ip = '{$gamesrv_ip}' AND prefix = '{$prefix}' ";
      if ($allow_db_changes) $result = mysql_query($query); else if ($allow_db_show) echo '<br />'.$query.'<br />===<br />';
      checkSQLResult ($result, $query);
      $serverid = $row['id'];
      }
     */
    /*     * ******************************
     * Process 'MapInfo'
     * ****************************** */


    ErrorLog("Processing Map Info Data ({$mapname}:{$mapid})", 3);
    $query = "SELECT * FROM mapinfo WHERE id = {$mapid}";
    $result = mysql_query($query);
    checkSQLResult($result, $query);
    if (!mysql_num_rows($result)) {
        $query = "INSERT INTO mapinfo SET
			id = {$mapid},
			name = '{$mapname}',
			score = {$globals['mapscore']},
			time = {$globals['roundtime']},
			times = 1,
			kills = {$globals['mapkills']},
			deaths = {$globals['mapdeaths']},
			custom = {$globals['custommap']}
		";
        if ($allow_db_changes)
            $result = mysql_query($query); else if ($allow_db_show)
            echo '<br />' . $query . '<br />===<br />';
        checkSQLResult($result, $query);
    } else {
        $row = mysql_fetch_array($result);
        $query = "UPDATE mapinfo SET
			score = `score` + {$globals['mapscore']},
			time = `time` + {$globals['roundtime']},
			times = `times` + 1,
			kills = `kills` + {$globals['mapkills']},
			deaths = `deaths` + {$globals['mapdeaths']},
			custom = {$globals['custommap']}
			WHERE id = {$mapid}
		";
        $result = mysql_query($query);
        checkSQLResult($result, $query);
    }

    /*     * ******************************
     * Process 'RoundInfo'
     * ****************************** */
    /*
      ErrorLog("Processing Round History Data",3);
      $query = "INSERT INTO round_history SET
      `timestamp` = {$data[mapstart]},
      `mapid` = {$mapid},
      `time` = {$globals[roundtime]},
      `team1` = {$data[ra1]},
      `team2` = {$data[ra2]},
      `tickets1` = {$data[rs1]},
      `tickets2` = {$data[rs2]},
      `pids1` = {$globals[team1_pids]},
      `pids1_end` = {$globals[team1_pids_end]},
      `pids2` = {$globals[team2_pids]},
      `pids2_end` = {$globals[team2_pids_end]}
      ";
      if ($allow_db_changes) $result = mysql_query($query); else if ($allow_db_show) echo '<br />'.$query.'<br />===<br />';
      checkSQLResult ($result, $query);
     */
    /*     * ******************************
     * Process 'SMoC/GEN'
     * ****************************** */
    /*
      omero, 2006-04-15
      do check for SMOC and General Ranks,
      only for non-AI players
     */
    /*
      ErrorLog("Processing SMOC and General Ranks",3);
      smocCheck();
      genCheck();
     */
    /*     * ******************************
     * Process 'Archive Data File'
     * ****************************** */
    if ($cfg->get('stats_move_logs')) {
        if (file_exists(chkPath($cfg->get('stats_logs_store')) . $ip_s)) {
            
        } else {
            mkdir(chkPath($cfg->get('stats_logs_store')) . $ip_s, 0777);
        }

        $fn_src = chkPath($cfg->get('stats_logs')) . $stats_filename;
        $fn_dest = chkPath($cfg->get('stats_logs_store')) . $ip_s . '/' . $stats_filename;

        if (file_exists($fn_src)) {
            if (file_exists($fn_dest)) {
                $errmsg = "SNAPSHOT Data File Already Exists, Over-writing! ({$fn_src} -> {$fn_dest})";
                ErrorLog($errmsg, 2);
            }
            copy($fn_src, $fn_dest);

            // Remove the original ONLY if it copies
            if (file_exists($fn_dest)) {
                unlink($fn_src);
            }
        }

        $errmsg = "SNAPSHOT Data File Moved! ({$fn_src} -> {$fn_dest})";
        ErrorLog($errmsg, 3);
    }
    $errmsg = "SNAPSHOT Data File Processed: {$stats_filename}";
    ErrorLog($errmsg, -1);
}

// Close database connection
@mysql_close($connection);



/* * **************************************************
 *                 Helper Functions  Not Used         *
 * ************************************************** */

// Compile Awards from SNAPSHOT
function getAwards() {
    global $data, $x, $awards, $awardsdata;

    foreach ($awardsdata as $award) {
        $awdkey = $award[1] . "_$x";
        if (isset($data[$awdkey])) {
            $awards[] = $award[0];
            $awards[] = ($award[2] == 0) ? $data[$awdkey] : $award[2];
        }
    }
}

// Check for Backend Awards
//print_r($medals_array);

function checkBackendAwards() {
    global $data, $x, $awardsdata;
    $medals_array = array();
    foreach ($data as $medal_name => $value) {
        if (preg_match("/^medal/", $medal_name)) {
            preg_match_all("/medal(\w+)_(\d+)/", $medal_name, $test);
            $pawd = $test[1][0];
            $awpid = $test[2][0];
            if (isset($awardsdata[$pawd])) {
                $medals_array[$data['pid_' . $awpid]][$pawd] = $value;
            }
        }
    }
    foreach ($medals_array as $awpid => $pawards) {
        foreach ($pawards as $paward_name => $paward_value) {
            preg_match_all("/(\d)(\d+)/", $awardsdata[$paward_name][0], $test2);
            $atype = $test2[1][0];
            $aid = $test2[2][0];
            $alvl = $paward_value;
            // Check if Player already has Award
            if ($atype == 1) {
                $qbage = "AND (alvl = {$alvl})";
            } else {
                $qbage = "";
            }
            $query = "SELECT * FROM awards WHERE (pid = {$awpid}) AND (atype = {$atype}) AND (aid = {$aid}) " . $qbage;

//echo $query;
            $awdresult = mysql_query($query);
            checkSQLResult($awdresult, $query);
            // Check if player has award
//print_r($awardsdata);
            // Recieveing these awards multiple times is NOT supported...yet!
            if (!mysql_num_rows($awdresult) OR $awardsdata[$paward_name][1] == 1) {
                // NEW or UPDATE multiple//
                if ($awardsdata[$paward_name][1] == 1 AND mysql_num_rows($awdresult) > 0) {
                    $rowawd = mysql_fetch_array($awdresult);
                    $query = "UPDATE awards SET alvl=(alvl+1), earned = " . time() . " WHERE (pid = {$awpid}) AND (atype = {$atype}) AND (aid = {$aid})";
                } else {
                    $query = "INSERT INTO awards SET pid = {$awpid}, atype = {$atype}, aid = {$aid}, earned = " . time() . ", first = " . time() . "";
                }

            } else {
                $rowawd = mysql_fetch_array($awdresult);
                $query = "UPDATE awards SET alvl=(alvl+1), earned = " . $mapdate . " WHERE (pid = {$awpid}) AND (atype = {$atype}) AND (aid = {$aid})";
            }
            if ($allow_db_changes)
                $result = mysql_query($query); else if ($allow_db_show)
                echo '<br />' . $query . '<br />===<br />';
            checkSQLResult($result, $query);
        }
    }
}
$string = ob_get_contents();
echo $string;
if ($LOG) {
$fp = fopen("logs/output".time().".txt","a+");
fwrite($fp,$string);
fflush($fp);
fclose($fp);
}

?>