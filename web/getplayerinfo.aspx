<?php

$timestamp = time();
$authPIDAvcred = 0;

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('getplayerinfo.txt', $str);

if (isset($_GET["auth"]) AND $_GET["auth"] != "") {
    $auth = $_GET["auth"];
} else {
    echo "None: No error.";
    exit;
}
if (isset($_GET["mode"]) AND $_GET["mode"] != "") {
    $mode = $_GET["mode"];
} else {
    echo "None: No error.";
    exit;
}
if (isset($_GET["web"]) AND $_GET["web"] != "") {
    $web = $_GET["web"];
} else {
    $web = 0;
}

require_once("ea_support.php");
require_once('include/_ccconfig.php');
require_once ('include/rankSettings.php');
$bfcoding = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($auth)));
//if ((hexdec($code[6].$code[7].$code[4].$code[5].$code[2].$code[3].$code[0].$code[1])+708) < $timestamp) {	echo "ExpiredAuth: Expired authentication token";exit;	}
/*
  0  4C524F45 7
  8  64000000 15
  16 9703F104 23
  24 0000     27
  28 87C3     31
 */
$authPID = hexdec($code[22] . $code[23] . $code[20] . $code[21] . $code[18] . $code[19] . $code[16] . $code[17]);
//$authPID = substr("0000000" . $authPID, -8);
//echo $authPID;
if (hexdec($code[26] . $code[27] . $code[24] . $code[25]) == 1) {
    $clType = 'server';
    $isServer = true;
} else {
    $clType = 'client';
    $isServer = false;
}

//if(isset($_GET["pid"]) AND $_GET["pid"] != "" AND $authPID != $_GET["pid"]) {	echo "Invalid Params";exit;	}
$connection = @mysql_connect($db_host, $db_user, $db_pass);

@mysql_select_db($db_name);

//Rank Check
$query = "SELECT acdt, rnk, crpt FROM playerprogress WHERE pid = '" . $authPID . "'";
$result = mysql_query($query) or die(mysql_error());
$query = "";
if (mysql_num_rows($result)) {

    $res = mysql_fetch_assoc($result);
    foreach ($rankArray as $rid => $ri) {

        if ($res['crpt'] >= $ri && $res['crpt'] < $rankArray[$rid + 1]) {
            if ($res['rnk'] < $rid) {
                $rnkcg = $rid - $res['rnk'];
                $query = "UPDATE playerprogress SET rnkcg = 1, rnk = rnk + " . $rnkcg . " WHERE pid = '" . $authPID . "'";
            } else {
                $rnkcg = $rid;
                $query = "UPDATE playerprogress SET rnk = " . $rnkcg . " WHERE pid = '" . $authPID . "'";
            }

            if ($query) {
//            $query = "UPDATE subaccounts SET rnkcg = '" . $rnkcg . "', rnk = rnk + ".$rnkcg." WHERE pid = '".$authPID."'";


                $result = mysql_query($query);
            }
            break;
        }
    }
    if (!$res['acdt']) {
        $query = "UPDATE playerprogress SET acdt = " . $timestamp . " WHERE pid = '" . $authPID . "'";
        $result = mysql_query($query) or die(mysql_error());
    }
}

/*if ($web == 0) {
    if ($mode == "base" AND !$isServer) {
        //H.pid.nick.tid.gsco.crpt.rnk.rnkcg.tt.pdt.pdtc.kdr.ent-1.ent-2.ent-3.bp-1.unavl
        //$parm1 = "pid subaccount tid gsco rnk tac cs tt crpt klstrk bnspt dstrk rps resp tasl tasm awybt hls sasl tds win los unlc expts cpt dcpt twsc tcd slpts tcrd md ent ent-1 ent-2 ent-3 bp-1 wtp-30 htp hkl atp akl vtp-0 vtp-1 vtp-2 vtp-3 vtp-4 vtp-5 vtp-6 vtp-7 vtp-8 vtp-9 vtp-10 vtp-11 vtp-12 vtp-13 vtp-14 vtp-15 vkls-0 vkls-1 vkls-2 vkls-3 vkls-4 vkls-5 vkls-6 vkls-7 vkls-8 vkls-9 vkls-10 vkls-11 vkls-12 vkls-13 vkls-14 vkls-15 vdstry-0 vdstry-1 vdstry-2 vdstry-3 vdstry-4 vdstry-5 vdstry-6 vdstry-7 vdstry-8 vdstry-9 vdstry-10 vdstry-11 vdstry-12 vdstry-13 vdstry-14 vdstry-15 vdths-0 vdths-1 vdths-2 vdths-3 vdths-4 vdths-5 vdths-6 vdths-7 vdths-8 vdths-9 vdths-10 vdths-11 vdths-12 vdths-13 vdths-14 vdths-15 ktt-0 ktt-1 ktt-2 ktt-3 wkls-0 wkls-1 wkls-2 wkls-3 wkls-4 wkls-5 wkls-6 wkls-7 wkls-8 wkls-9 wkls-10 wkls-11 wkls-12 wkls-13 wkls-14 wkls-15 wkls-16 wkls-17 wkls-18 wkls-19 wkls-20 wkls-21 wkls-22 wkls-23 wkls-24 wkls-25 wkls-26 wkls-27 wkls-28 wkls-29 wkls-30 wkls-31 klsk klse etp-0 etp-1 etp-2 etp-3 etp-4 etp-5 etp-6 etp-7 etp-8 etp-9 etp-10 etp-11 etp-12 etp-13 etp-14 etp-15 etp-16 etpk-0 etpk-1 etpk-2 etpk-3 etpk-4 etpk-5 etpk-6 etpk-7 etpk-8 etpk-9 etpk-10 etpk-11 etpk-12 etpk-13 etpk-14 etpk-15 etpk-16 attp-0 attp-1 awin-0 awin-1 tgpm-0 tgpm-1 tgpm-2 kgpm-0 kgpm-1 kgpm-2 bksgpm-0 bksgpm-1 bksgpm-2 ctgpm-0 ctgpm-1 ctgpm-2 csgpm-0 csgpm-1 csgpm-2 trpm-0 trpm-1 trpm-2 klls attp-0 attp-1 awin-0 awin-1 pdt mtt-0-0 mtt-0-1 mtt-0-3 mtt-0-4 mtt-0-5 mtt-0-6 mtt-0-7 mtt-0-8 mtt-0-9 mwin-0-0 mwin-0-1 mwin-0-3 mwin-0-4 mwin-0-5 mwin-0-6 mwin-0-7 mwin-0-8 mwin-0-9 mbr-0-0 mbr-0-1 mbr-0-3 mbr-0-4 mbr-0-5 mbr-0-6 mbr-0-7 mbr-0-8 mbr-0-9 mkls-0-0 mkls-0-1 mkls-0-3 mkls-0-4 mkls-0-5 mkls-0-6 mkls-0-7 mkls-0-8 mkls-0-9 mtt-1-0 mtt-1-1 mtt-1-2 mtt-1-3 mtt-1-5 mwin-1-0 mwin-1-1 mwin-1-2 mwin-1-3 mwin-1-5 mlos-1-0 mlos-1-1 mlos-1-2 mlos-1-3 mlos-1-5 mbr-1-0 mbr-1-1 mbr-1-2 mbr-1-3 mbr-1-5 msc-1-0 msc-1-1 msc-1-2 msc-1-3 msc-1-5 mkls-1-0 mkls-1-1 mkls-1-2 mkls-1-3 mkls-1-5";
        //$parm1 = "pid nick tid gsco crpt rnk rnkcg tt pdt pdtc kdr ent-1 ent-2 ent-3 bp-1 unavl";
        $query1 = "SELECT p.pid, nick, tid, gsco, crpt, rnk, rnkcg, tt, pdt, pdtc, kdr, 0 AS `ent-1`, 0 AS `ent-2`, 0 AS `ent-3`, 0 AS `bp-1`, unavl, unlc FROM `subaccount` s
                        LEFT JOIN `playerprogress` p ON p.pid=s.id 
			LEFT JOIN `stats_e` e ON e.pid=p.pid			
		WHERE s.id='" . $authPID . "' LIMIT 1";

        //$mapData = getMapData($authPID);
        //foreach ($mapData['params'] as $field => $val) {
        //    $parm1.=$field . " ";
        // }
        //$param1 = rtrim($param1);*/
if ($web == 0) {
    if ($mode == "base" AND $isServer) {
        $parm1 = "p.pid subaccount tid gsco rnk tac cs tt crpt klstrk bnspt dstrk rps resp tasl tasm awybt hls sasl tds win los unlc expts cpt dcpt twsc tcd slpts tcrd md ent ent-1 ent-2 ent-3 bp-1 wtp-30 htp hkl atp akl vtp-0 vtp-1 vtp-2 vtp-3 vtp-4 vtp-5 vtp-6 vtp-7 vtp-8 vtp-9 vtp-10 vtp-11 vtp-12 vtp-13 vtp-14 vtp-15 vkls-0 vkls-1 vkls-2 vkls-3 vkls-4 vkls-5 vkls-6 vkls-7 vkls-8 vkls-9 vkls-10 vkls-11 vkls-12 vkls-13 vkls-14 vkls-15 vdstry-0 vdstry-1 vdstry-2 vdstry-3 vdstry-4 vdstry-5 vdstry-6 vdstry-7 vdstry-8 vdstry-9 vdstry-10 vdstry-11 vdstry-12 vdstry-13 vdstry-14 vdstry-15 vdths-0 vdths-1 vdths-2 vdths-3 vdths-4 vdths-5 vdths-6 vdths-7 vdths-8 vdths-9 vdths-10 vdths-11 vdths-12 vdths-13 vdths-14 vdths-15 ktt-0 ktt-1 ktt-2 ktt-3 wkls-0 wkls-1 wkls-2 wkls-3 wkls-4 wkls-5 wkls-6 wkls-7 wkls-8 wkls-9 wkls-10 wkls-11 wkls-12 wkls-13 wkls-14 wkls-15 wkls-16 wkls-17 wkls-18 wkls-19 wkls-20 wkls-21 wkls-22 wkls-23 wkls-24 wkls-25 wkls-26 wkls-27 wkls-28 wkls-29 wkls-30 wkls-31 klsk klse etp-0 etp-1 etp-2 etp-3 etp-4 etp-5 etp-6 etp-7 etp-8 etp-9 etp-10 etp-11 etp-12 etp-13 etp-14 etp-15 etp-16 etpk-0 etpk-1 etpk-2 etpk-3 etpk-4 etpk-5 etpk-6 etpk-7 etpk-8 etpk-9 etpk-10 etpk-11 etpk-12 etpk-13 etpk-14 etpk-15 etpk-16 attp-0 attp-1 awin-0 awin-1 tgpm-0 tgpm-1 tgpm-2 kgpm-0 kgpm-1 kgpm-2 bksgpm-0 bksgpm-1 bksgpm-2 ctgpm-0 ctgpm-1 ctgpm-2 csgpm-0 csgpm-1 csgpm-2 trpm-0 trpm-1 trpm-2 klls attp-0 attp-1 awin-0 awin-1 pdt mtt-0-0 mtt-0-1 mtt-0-3 mtt-0-4 mtt-0-5 mtt-0-6 mtt-0-7 mtt-0-8 mtt-0-9 mwin-0-0 mwin-0-1 mwin-0-3 mwin-0-4 mwin-0-5 mwin-0-6 mwin-0-7 mwin-0-8 mwin-0-9 mbr-0-0 mbr-0-1 mbr-0-3 mbr-0-4 mbr-0-5 mbr-0-6 mbr-0-7 mbr-0-8 mbr-0-9 mkls-0-0 mkls-0-1 mkls-0-3 mkls-0-4 mkls-0-5 mkls-0-6 mkls-0-7 mkls-0-8 mkls-0-9 mtt-1-0 mtt-1-1 mtt-1-2 mtt-1-3 mtt-1-5 mwin-1-0 mwin-1-1 mwin-1-2 mwin-1-3 mwin-1-5 mlos-1-0 mlos-1-1 mlos-1-2 mlos-1-3 mlos-1-5 mbr-1-0 mbr-1-1 mbr-1-2 mbr-1-3 mbr-1-5 msc-1-0 msc-1-1 msc-1-2 msc-1-3 msc-1-5 mkls-1-0 mkls-1-1 mkls-1-2 mkls-1-3 mkls-1-5";
            $query1 = "SELECT * FROM `playerprogress` s  
			LEFT JOIN `stats_a` a ON a.pid=s.pid
			LEFT JOIN `stats_e` e ON e.pid=a.pid
			LEFT JOIN `stats_m` m ON m.pid=a.pid
			LEFT JOIN `stats_v` v ON v.pid=a.pid
			LEFT JOIN `stats_w` w ON w.pid=a.pid
                WHERE s.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error().__LINE__);
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }
        if ($temp['rnk'] > ($temp['unavl'] + $temp['unlc'])) {
            $temp['unavl'] = ($temp['rnk'] - $temp['unlc'] - $temp['unavl']) + $temp['unavl'];
            //$query9 = "UPDATE `stats_a` SET unavl='".$temp['unavl']."' WHERE pid='".$authPID."'";
            $query9 = "UPDATE `playerprogress` SET unavl='" . $temp['unavl'] . "' WHERE pid='" . $authPID . "'";
            $result9 = mysql_query($query9) or die(mysql_error());
        }
        //$temp = preprecparam($temp);

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\t" . $clType;

        //$aparm1 = explode(" ", $parm1);
        //$temp['pid'] = $authPID;
        //$temp['nick'] = rawurldecode($temp['subaccount']);
        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($temp as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;
        }

        $Out .= $H . $D;

        if ($game_unlocks == 0) {
            $Out .= "\n" .
                    "H\tUnlockID";
            $query2 = "SELECT * FROM `unlocks` WHERE pid='" . $authPID . "'";
            $result2 = mysql_query($query2) or die(mysql_error());
            if (mysql_num_rows($result2)) {
                while ($row2 = mysql_fetch_array($result2)) {
                    $Out .= "\nD\t" . $row2['ukit'] . $row2['utree'] . $row2['uorder'];
                }
            }
        } elseif ($game_unlocks == 1) {
            $Out .= "\n" .
                    "H\tUnlockID";
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
            $Out .= "\n" .
                    "H\tUnlockID";
        }
    } else if ($mode == "base") {
        $parm1 = "p.pid subaccount tid gsco rnk tac cs tt crpt klstrk bnspt dstrk rps resp tasl tasm awybt hls sasl tds win los unlc expts cpt dcpt twsc tcd slpts tcrd md ent ent-1 ent-2 ent-3 bp-1 wtp-30 htp hkl atp akl vtp-0 vtp-1 vtp-2 vtp-3 vtp-4 vtp-5 vtp-6 vtp-7 vtp-8 vtp-9 vtp-10 vtp-11 vtp-12 vtp-13 vtp-14 vtp-15 vkls-0 vkls-1 vkls-2 vkls-3 vkls-4 vkls-5 vkls-6 vkls-7 vkls-8 vkls-9 vkls-10 vkls-11 vkls-12 vkls-13 vkls-14 vkls-15 vdstry-0 vdstry-1 vdstry-2 vdstry-3 vdstry-4 vdstry-5 vdstry-6 vdstry-7 vdstry-8 vdstry-9 vdstry-10 vdstry-11 vdstry-12 vdstry-13 vdstry-14 vdstry-15 vdths-0 vdths-1 vdths-2 vdths-3 vdths-4 vdths-5 vdths-6 vdths-7 vdths-8 vdths-9 vdths-10 vdths-11 vdths-12 vdths-13 vdths-14 vdths-15 ktt-0 ktt-1 ktt-2 ktt-3 wkls-0 wkls-1 wkls-2 wkls-3 wkls-4 wkls-5 wkls-6 wkls-7 wkls-8 wkls-9 wkls-10 wkls-11 wkls-12 wkls-13 wkls-14 wkls-15 wkls-16 wkls-17 wkls-18 wkls-19 wkls-20 wkls-21 wkls-22 wkls-23 wkls-24 wkls-25 wkls-26 wkls-27 wkls-28 wkls-29 wkls-30 wkls-31 klsk klse etp-0 etp-1 etp-2 etp-3 etp-4 etp-5 etp-6 etp-7 etp-8 etp-9 etp-10 etp-11 etp-12 etp-13 etp-14 etp-15 etp-16 etpk-0 etpk-1 etpk-2 etpk-3 etpk-4 etpk-5 etpk-6 etpk-7 etpk-8 etpk-9 etpk-10 etpk-11 etpk-12 etpk-13 etpk-14 etpk-15 etpk-16 attp-0 attp-1 awin-0 awin-1 tgpm-0 tgpm-1 tgpm-2 kgpm-0 kgpm-1 kgpm-2 bksgpm-0 bksgpm-1 bksgpm-2 ctgpm-0 ctgpm-1 ctgpm-2 csgpm-0 csgpm-1 csgpm-2 trpm-0 trpm-1 trpm-2 klls attp-0 attp-1 awin-0 awin-1 pdt mtt-0-0 mtt-0-1 mtt-0-3 mtt-0-4 mtt-0-5 mtt-0-6 mtt-0-7 mtt-0-8 mtt-0-9 mwin-0-0 mwin-0-1 mwin-0-3 mwin-0-4 mwin-0-5 mwin-0-6 mwin-0-7 mwin-0-8 mwin-0-9 mbr-0-0 mbr-0-1 mbr-0-3 mbr-0-4 mbr-0-5 mbr-0-6 mbr-0-7 mbr-0-8 mbr-0-9 mkls-0-0 mkls-0-1 mkls-0-3 mkls-0-4 mkls-0-5 mkls-0-6 mkls-0-7 mkls-0-8 mkls-0-9 mtt-1-0 mtt-1-1 mtt-1-2 mtt-1-3 mtt-1-5 mwin-1-0 mwin-1-1 mwin-1-2 mwin-1-3 mwin-1-5 mlos-1-0 mlos-1-1 mlos-1-2 mlos-1-3 mlos-1-5 mbr-1-0 mbr-1-1 mbr-1-2 mbr-1-3 mbr-1-5 msc-1-0 msc-1-1 msc-1-2 msc-1-3 msc-1-5 mkls-1-0 mkls-1-1 mkls-1-2 mkls-1-3 mkls-1-5";
        $query1 = "SELECT * FROM `subaccount` s
                        LEFT JOIN `playerprogress` p ON p.pid=s.id 			
			LEFT JOIN `stats_e` e ON e.pid=p.pid
			LEFT JOIN `stats_m` m ON m.pid=p.pid
			LEFT JOIN `stats_v` v ON v.pid=p.pid
			LEFT JOIN `stats_w` w ON w.pid=p.pid
		WHERE s.id='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }
        $temp = preprecparam($temp);

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\t" . $clType;

        $aparm1 = explode(" ", $parm1);

        $temp['pid'] = $authPID;
        $temp['subaccount'] = rawurldecode($temp['subaccount']);
        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($aparm1 as $parm1_) {
            $H .= "\t" . $parm1_;
            if (isset($temp[$parm1_])) {
                $D .= "\t" . $temp[$parm1_];
            } else {
                $D .= "\t0";
            }
        }
        foreach ($temp as $parm => $value) {
            $H .= "\t" . $parm;
            $D .= "\t" . $value;







        }
        $Out .= $H . $D;

        if ($game_unlocks == 0) {
            $Out .= "\n" .
                    "H\tUnlockID";
            $query2 = "SELECT * FROM `unlocks` WHERE pid='" . $authPID . "'";
            $result2 = mysql_query($query2) or die(mysql_error());
            if (mysql_num_rows($result2)) {
                while ($row2 = mysql_fetch_array($result2)) {
                    $Out .= "\nD\t" . $row2['ukit'] . $row2['utree'] . $row2['uorder'];
                }
            }
        } elseif ($game_unlocks == 1) {
            $Out .= "\n" .
                    "H\tUnlockID";
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
            $Out .= "\n" .
                    "H\tUnlockID";
        }
    } elseif ($mode == "base" AND !$isServer) {

        $parm2 = "pid nick tid gsco crpt rnk rnkcg tt pdt pdtc kdr ent-1 ent-2 ent-3 bp-1 unavl";
        $query2 = "SELECT * FROM `playerprogress` s " .
			"LEFT JOIN `stats_a` a ON a.pid=s.pid
			LEFT JOIN `stats_e` e ON e.pid=a.pid
			LEFT JOIN `stats_m` m ON m.pid=a.pid
			LEFT JOIN `stats_v` v ON v.pid=a.pid
			LEFT JOIN `stats_w` w ON w.pid=a.pid
                WHERE s.pid='" . $authPID . "' LIMIT 1";
        $result2 = mysql_query($query2) or die(mysql_error());
        if (!mysql_num_rows($result2)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result2);
        }
        $temp = preprecparam($temp);

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\t" . $clType;

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($parm1 as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;
        }

        $Out .= $H . $D;
      
    } elseif ($mode == "ply") {

        $parm1 = "pid nick tid klls klla dths suic klstrk dstrk spm kdr kpm dpm akpr adpr tots toth ovaccu ktt-0 ktt-1 ktt-2 ktt-3 kkls-0 kkls-1 kkls-2 kkls-3";

        $parm1 = explode(" ", $parm1);
        $query1 = "SELECT * FROM `playerprogress` p " .
                " WHERE p.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

        //$temp['pid'] = $authPID;
        //$temp['nick'] = rawurldecode($temp['subaccount']);
        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\tclient";





        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($parm1 as $parm1_) {
            $H .= "\t" . $parm1_;
            if (isset($temp[$parm1_])) {
                $D .= "\t" . $temp[$parm1_];
            } else {
                $D .= "\t0";
            }
        }

        $Out .= $H . $D;
    } elseif ($mode == "titan") {

        $query1 = "SELECT pid, nick, tid, tas, tdrps, tds, tgr, tgd, tcd, tcrd, tt AS ttp, trp, cts FROM `playerprogress` p " .
               // " WHERE p.pid='" . $authPID . "' AND gm=1 LIMIT 1";
			   " WHERE p.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\tclient";

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($temp as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;
        }

        $Out .= $H . $D;
    } elseif ($mode == "wrk") {

        $query1 = "SELECT pid, nick, tid, twsc, cpt, capa, dcpt, hls, rps, rvs, resp, talw, dass, tkls, tdmg, tvdmg, tasm, tasl, tac, cs, sasl, cts FROM `playerprogress` p " .
                " WHERE p.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\tclient";

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($temp as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;
        }

        $Out .= $H . $D;
    } elseif ($mode == "com") {

        $query1 = "SELECT p.pid, nick, tid, slbspn, sluav, kluav, cs, slpts, tasl, sasl, tac, slbcn, `wkls-27`, `csgpm-0`, `csgpm-1`, `csgpm-2` FROM `playerprogress` p " .
                " LEFT JOIN `stats_w` w ON w.pid = p.pid " .
                " WHERE p.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\tclient";

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($temp as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;
        }

        $Out .= $H . $D;
    } elseif ($mode == "wep") {
        $parm1 = "wtp-0 wtp-1 wtp-2 wtp-3 wtp-4 wtp-5 wtp-6 wtp-7 wtp-8 wtp-9 wtp-10 wtp-11 wtp-12 wtp-13 wtp-14 wtp-15 wtp-16 wtp-17 wtp-19 wtp-20 wtp-21 wtp-22 wtp-23 wtp-24 wtp-26 wtp-30 wtp-31 ";
        $parm1.= "wtpk-0 wtpk-1 wtpk-2 wtpk-3 wtpk-4 wtpk-5 wtpk-6 wtpk-7 wtpk-8 wtpk-9 wtpk-10 wtpk-11 wtpk-12 wtpk-13 wtpk-14 wtpk-15 wtpk-16 wtpk-17 wtpk-19 wtpk-20 wtpk-21 wtpk-22 wtpk-23 wtpk-24 wtpk-26 wtpk-30 wtpk-31 ";
        $parm1.= "wkls-0 wkls-1 wkls-2 wkls-3 wkls-4 wkls-5 wkls-6 wkls-7 wkls-8 wkls-9 wkls-10 wkls-11 wkls-12 wkls-13 wkls-14 wkls-15 wkls-16 wkls-17 wkls-19 wkls-20 wkls-21 wkls-22 wkls-23 wkls-24 wkls-26 wkls-30 wkls-31 ";
        $parm1.= "wdths-0 wdths-1 wdths-2 wdths-3 wdths-4 wdths-5 wdths-6 wdths-7 wdths-8 wdths-9 wdths-10 wdths-11 wdths-12 wdths-13 wdths-14 wdths-15 wdths-16 wdths-17 wdths-19 wdths-20 wdths-21 wdths-22 wdths-23 wdths-24 wdths-26 wdths-30 wdths-31 ";
        $parm1.= "wshts-0 wshts-1 wshts-2 wshts-3 wshts-4 wshts-5 wshts-6 wshts-7 wshts-8 wshts-9 wshts-10 wshts-11 wshts-12 wshts-13 wshts-14 wshts-15 wshts-16 wshts-17 wshts-19 wshts-20 wshts-21 wshts-22 wshts-23 wshts-24 wshts-26 wshts-30 wshts-31 ";
        $parm1.= "whts-0 whts-1 whts-2 whts-3 whts-4 whts-5 whts-6 whts-7 whts-8 whts-9 whts-10 whts-11 whts-12 whts-13 whts-14 whts-15 whts-16 whts-17 whts-19 whts-20 whts-21 whts-22 whts-23 whts-24 whts-26 whts-30 whts-31 ";
        $parm1.= "waccu-0 waccu-1 waccu-2 waccu-3 waccu-4 waccu-5 waccu-6 waccu-7 waccu-8 waccu-9 waccu-10 waccu-11 waccu-12 waccu-13 waccu-14 waccu-15 waccu-16 waccu-17 waccu-19 waccu-20 waccu-21 waccu-22 waccu-23 waccu-24 waccu-26 waccu-30 waccu-31 ";
        $parm1.= "wkdr-0 wkdr-1 wkdr-2 wkdr-3 wkdr-4 wkdr-5 wkdr-6 wkdr-7 wkdr-8 wkdr-9 wkdr-10 wkdr-11 wkdr-12 wkdr-13 wkdr-14 wkdr-15 wkdr-16 wkdr-17 wkdr-19 wkdr-20 wkdr-21 wkdr-22 wkdr-23 wkdr-24 wkdr-26 wkdr-30 wkdr-31";

        $parm1 = explode(" ", $parm1);
        $query1 = "SELECT * FROM `subaccount` s			
			LEFT JOIN `stats_w` w ON w.pid=s.id
		WHERE s.id='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

$aparm2 = explode(" ", $parm2);

        $temp['pid'] = $authPID;
        $temp['nick'] = rawurldecode($temp['subaccount']);
        $Out = "O\n" .
                "H\tpid\tnick\ttid\tasof\n" .
                "D\t" . $temp['pid'] . "\t" . $temp['nick'] . "\t0\t" . $timestamp;


        $Out .= "\n";


        $H = "H";
        $D = "\nD";
        foreach ($parm1 as $parm1_) {
            $H .= "\t" . $parm1_;
            if (isset($temp[$parm1_])) {
                $D .= "\t" . $temp[$parm1_];
            } else {
                $D .= "\t0";

































            }
        }

        $Out .= $H . $D;		
    } elseif ($mode == "ovr" AND !$isServer) {
	        $parm1 = "pid nick tid gsco tt crpt fgm fm fe fv fk fw win los acdt lgdt brs etp-3 pdt pdtc";
        $parm1 = explode(" ", $parm1);
        $query1 = "SELECT p.pid, nick, tid, gsco, tt, crpt, fgm, fm, fe, fv, fk, fw, wins AS win, los, acdt, lgdt, brs, `etp-3`, pdt, pdtc FROM `playerprogress` p 
                LEFT JOIN stats_e e ON e.pid = p.pid 
		WHERE p.pid='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $parm1 = mysql_fetch_assoc($result1);
        }


        //$temp = preprecparam($temp);
        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\t" . $clType;

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($parm1 as $parm => $val) {
            $H .= "\t" . $parm;
            $D .= "\t" . $val;


























































        }

        $Out .= $H . $D;
} elseif ($mode == "comp" AND !$isServer) {
        
          $parm1 = "pid nick tid gsco tt crpt fgm fm fe fv fk fw win los acdt lgdt brs etp-3 pdt pdtc";
          $query1 = "SELECT * FROM `playerprogress` p
          LEFT JOIN `stats_a` a ON a.pid=p.pid
          LEFT JOIN `stats_e` e ON e.pid=p.pid
          LEFT JOIN `stats_m` m ON m.pid=p.pid
          LEFT JOIN `stats_v` v ON v.pid=p.pid
          LEFT JOIN `stats_w` w ON w.pid=p.pid
          WHERE p.pid='".$authPID."' LIMIT 1";
          $result1 = mysql_query($query1) or die(mysql_error());
          if (!mysql_num_rows($result1)) {
          errorcode(104);
          exit;
          } else {
          $temp = mysql_fetch_assoc($result1);
          }
          if ($temp['rnk'] > ($temp['unavl']+$temp['unlc'])) {
          $temp['unavl'] = ($temp['rnk']-$temp['unlc']-$temp['unavl'])+$temp['unavl'];
          $query9 = "UPDATE `stats_a` SET unavl='".$temp['unavl']."' WHERE pid='".$authPID."'";
          $result9 = mysql_query($query9) or die(mysql_error());
          }
          $temp = preprecparam($temp);
          $Out = "O\n".
          "H\tasof\tcb\n".
          "D\t".$timestamp."\t".$clType;
         $Out .= "\n";
          $H =	"H";
          $D =	"\nD";
		 // print_r($temp);
          foreach($temp as $parm1=>$parm2) {
          if ($parm1=="wins") $parm1 = "win";
		  $H .= "\t".$parm1;
          if(isset($parm2)) {
          $D .= "\t".$parm2;
          } else {
          $D .= "\t0";
          }
          }

          $Out .= $H.$D;
 		 ///////////////////////////////////////////////////////////////////////////////
		 $parm1 = "pid nick tid gsco tt crpt fgm fm fe fv fk fw win los acdt lgdt brs etp-3 pdt pdtc";
         $authPID = $_GET['pid'];
		 $query1 = "SELECT * FROM `playerprogress` p
          LEFT JOIN `stats_a` a ON a.pid=p.pid
          LEFT JOIN `stats_e` e ON e.pid=p.pid
          LEFT JOIN `stats_m` m ON m.pid=p.pid
          LEFT JOIN `stats_v` v ON v.pid=p.pid
          LEFT JOIN `stats_w` w ON w.pid=p.pid
          WHERE p.pid='".$authPID."' LIMIT 1";
         
		  $result1 = mysql_query($query1) or die(mysql_error());
          if (!mysql_num_rows($result1)) {
          errorcode(104);
          exit;
          } else {
          $temp = mysql_fetch_assoc($result1);
          }
          if ($temp['rnk'] > ($temp['unavl']+$temp['unlc'])) {
          $temp['unavl'] = ($temp['rnk']-$temp['unlc']-$temp['unavl'])+$temp['unavl'];
          $query9 = "UPDATE `stats_a` SET unavl='".$temp['unavl']."' WHERE pid='".$authPID."'";
          $result9 = mysql_query($query9) or die(mysql_error());
          }
          $temp = preprecparam($temp);


          $Out .= "\n";

          $H =	"H";
          $D =	"\nD";
          foreach($temp as $parm1=>$parm2) {
		  if ($parm1=="wins") $parm1 = "win";
          $H .=  "\t".$parm1;
          if(isset($parm2)) {
          $D .= "\t".$parm2;
          } else {
          $D .= "\t0";
          }
          }

          $Out .= $H.$D;
		  ///////////////////////////////////////////////////////////////////////////

    } elseif ($mode == "veh") {
        $parm1 = "vtp-0 vtp-1 vtp-2 vtp-5 vtp-4 vtp-6 vtp-10 vtp-11 vtp-12 vtp-13 vtp-14 vtp-15 ";// время
        $parm1.= "vkls-0 vkls-1 vkls-2 vkls-5 vkls-4 vkls-6 vkls-10 vkls-11 vkls-12 vkls-13 vkls-14 vkls-15 "; // жертвы
        $parm1.= "vdths-0 vdths-1 vdths-2 vdths-5 vdths-4 vdths-6 vdths-10 vdths-11 vdths-12 vdths-13 vdths-14 vdths-15 ";// смерти
        $parm1.= "vkdr-0 vkdr-1 vkdr-2 vkdr-5 vkdr-4 vkdr-6 vkdr-10 vkdr-11 vkdr-12 vkdr-13 vkdr-14 vkdr-15 "; //П/С
        $parm1.= "vdstry-0 vdstry-1 vdstry-2 vdstry-5 vdstry-4 vdstry-6 vdstry-10 vdstry-11 vdstry-12 vdstry-13 vdstry-14 vdstry-15 ";// уничтоженно
        $parm1.= "vrkls-0 vrkls-1 vrkls-2 vrkls-5 vrkls-4 vrkls-6 vrkls-10 vrkls-11 vrkls-12 vrkls-13 vrkls-14 vrkls-15";// наезды

        $parm1 = explode(" ", $parm1);
        $query1 = "SELECT * FROM `subaccount` s			
			LEFT JOIN `stats_v` v ON v.pid=s.id
		WHERE s.id='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {

            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }

        $temp['pid'] = $authPID;
        $temp['nick'] = rawurldecode($temp['subaccount']);
        $Out = "O\n" .
                "H\tpid\tnick\ttid\tasof\n" .
                "D\t" . $temp['pid'] . "\t" . $temp['nick'] . "\t0\t" . $timestamp;

        $Out .= "\n";

        $H = "H";
        $D = "\nD";

        foreach ($parm1 as $parm1_) {
            $H .= "\t" . $parm1_;

            if (isset($temp[$parm1_])) {
                $D .= "\t" . $temp[$parm1_];
            } else {
                $D .= "\t0";
            }

        }

        $Out .= $H . $D;


    } elseif ($mode == "map") {

        $mapData = getMapData($authPID);
        //$fields['nick'] = rawurldecode($fields['subaccount']);
        $Out = "O\n" .
                "H\tpid\tnick\ttid\tasof\n" .
                "D\t" . $authPID . "\t" . $mapData['nick'] . "\t0\t" . $timestamp;

        $Out .= "\n";

        $H = "H";
        $D = "\nD";
        foreach ($mapData['params'] as $param => $val) {
            $H .= "\t" . $param;
            $D .= "\t" . $val;
        }
        $Out .= $H . $D;		
//} elseif(mode=ovr) {
//} elseif(mode=ovr) {
//} elseif(mode=ovr) {
    } else {
        exit;
    }
} else {
    if ($mode == "base") {

        $parm1 = "pid subaccount tid gsco rnk tac cs tt crpt klstrk bnspt dstrk rps resp tasl tasm awybt hls sasl tds win los unlc expts cpt dcpt twsc tcd slpts tcrd md ent ent-1 ent-2 ent-3 bp-1 wtp-30 htp hkl atp akl vtp-0 vtp-1 vtp-2 vtp-3 vtp-4 vtp-5 vtp-6 vtp-7 vtp-8 vtp-9 vtp-10 vtp-11 vtp-12 vtp-13 vtp-14 vtp-15 vkls-0 vkls-1 vkls-2 vkls-3 vkls-4 vkls-5 vkls-6 vkls-7 vkls-8 vkls-9 vkls-10 vkls-11 vkls-12 vkls-13 vkls-14 vkls-15 vdstry-0 vdstry-1 vdstry-2 vdstry-3 vdstry-4 vdstry-5 vdstry-6 vdstry-7 vdstry-8 vdstry-9 vdstry-10 vdstry-11 vdstry-12 vdstry-13 vdstry-14 vdstry-15 vdths-0 vdths-1 vdths-2 vdths-3 vdths-4 vdths-5 vdths-6 vdths-7 vdths-8 vdths-9 vdths-10 vdths-11 vdths-12 vdths-13 vdths-14 vdths-15 ktt-0 ktt-1 ktt-2 ktt-3 wkls-0 wkls-1 wkls-2 wkls-3 wkls-4 wkls-5 wkls-6 wkls-7 wkls-8 wkls-9 wkls-10 wkls-11 wkls-12 wkls-13 wkls-14 wkls-15 wkls-16 wkls-17 wkls-18 wkls-19 wkls-20 wkls-21 wkls-22 wkls-23 wkls-24 wkls-25 wkls-26 wkls-27 wkls-28 wkls-29 wkls-30 wkls-31 klsk klse etp-0 etp-1 etp-2 etp-3 etp-4 etp-5 etp-6 etp-7 etp-8 etp-9 etp-10 etp-11 etp-12 etp-13 etp-14 etp-15 etp-16 etpk-0 etpk-1 etpk-2 etpk-3 etpk-4 etpk-5 etpk-6 etpk-7 etpk-8 etpk-9 etpk-10 etpk-11 etpk-12 etpk-13 etpk-14 etpk-15 etpk-16 attp-0 attp-1 awin-0 awin-1 tgpm-0 tgpm-1 tgpm-2 kgpm-0 kgpm-1 kgpm-2 bksgpm-0 bksgpm-1 bksgpm-2 ctgpm-0 ctgpm-1 ctgpm-2 csgpm-0 csgpm-1 csgpm-2 trpm-0 trpm-1 trpm-2 klls attp-0 attp-1 awin-0 awin-1 pdt mtt-0-0 mtt-0-1 mtt-0-3 mtt-0-4 mtt-0-5 mtt-0-6 mtt-0-7 mtt-0-8 mtt-0-9 mwin-0-0 mwin-0-1 mwin-0-3 mwin-0-4 mwin-0-5 mwin-0-6 mwin-0-7 mwin-0-8 mwin-0-9 mbr-0-0 mbr-0-1 mbr-0-3 mbr-0-4 mbr-0-5 mbr-0-6 mbr-0-7 mbr-0-8 mbr-0-9 mkls-0-0 mkls-0-1 mkls-0-3 mkls-0-4 mkls-0-5 mkls-0-6 mkls-0-7 mkls-0-8 mkls-0-9 mtt-1-0 mtt-1-1 mtt-1-2 mtt-1-3 mtt-1-5 mwin-1-0 mwin-1-1 mwin-1-2 mwin-1-3 mwin-1-5 mlos-1-0 mlos-1-1 mlos-1-2 mlos-1-3 mlos-1-5 mbr-1-0 mbr-1-1 mbr-1-2 mbr-1-3 mbr-1-5 msc-1-0 msc-1-1 msc-1-2 msc-1-3 msc-1-5 mkls-1-0 mkls-1-1 mkls-1-2 mkls-1-3 mkls-1-5";
        $query1 = "SELECT * FROM `subaccount` s 
		    LEFT JOIN `playerprogress` p ON p.pid=s.id 
			LEFT JOIN `stats_a` a ON a.pid=s.pid
			LEFT JOIN `stats_e` e ON e.pid=p.pid
			LEFT JOIN `stats_m` m ON m.pid=p.pid
			LEFT JOIN `stats_v` v ON v.pid=p.pid
			LEFT JOIN `stats_w` w ON w.pid=p.pid
                WHERE s.id='" . $authPID . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if (!mysql_num_rows($result1)) {
            errorcode(104);
            exit;
        } else {
            $temp = mysql_fetch_assoc($result1);
        }
        $temp = preprecparam($temp);

        $Out = "O\n" .
                "H\tasof\tcb\n" .
                "D\t" . $timestamp . "\t" . $clType;

        $aparm1 = explode(" ", $parm1);

        $temp['pid'] = $authPID;
        $temp['subaccount'] = rawurldecode($temp['subaccount']);
        $Out .= "\n";

        $H = "H";
        $D = "\nD";




        /*
          foreach($aparm1 as $parm1_) {
          $H .= "\t".$parm1_;
          if(isset($temp[$parm1_])) {
          $D .= "\t".$temp[$parm1_];
          } else {
          $D .= "\t0";
          }
          }
         */
        foreach ($temp as $parm => $value) {
            $H .= "\t" . $parm;
            $D .= "\t" . $value;
        }





        $Out .= $H . $D;

        if ($game_unlocks == 0) {
            $Out .= "\n" .
                    "H\tUnlockID";
            $query2 = "SELECT * FROM `unlocks` WHERE pid='" . $authPID . "'";
            $result2 = mysql_query($query2) or die(mysql_error());
            if (mysql_num_rows($result2)) {
                while ($row2 = mysql_fetch_array($result2)) {
                    $Out .= "\nD\t" . $row2['ukit'] . $row2['utree'] . $row2['uorder'];
                }
            }
        } elseif ($game_unlocks == 1) {
            $Out .= "\n" .
                    "H\tUnlockID";
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
            $Out .= "\n" .
                    "H\tUnlockID";
        }
    }
}

$countOut = preg_replace('/[\t\n]/', '', $Out);
//$Out =  $Out."\n$\t".strlen($countOut)."\t$\n";
print $Out . "\n$\t" . strlen($countOut) . "\t$\n";

@mysql_close($connection);

function errorcode($errorcode=104) {
    $Out = "E\t" . $errorcode;
    $countOut = preg_replace('/[\t\n]/', '', $Out);
    print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
}

function getMapData($pid) {
    $params = array();
    $query1 = "SELECT * FROM `stats_m` m " .
            "LEFT JOIN subaccount s ON s.id = m.pid" .
            " WHERE m.pid='" . $pid . "' ORDER BY m.mapid ASC";
    $result1 = mysql_query($query1) or die(mysql_error());

    if (!mysql_num_rows($result1)) {
        errorcode(104);
        exit;
    } else {
        while ($fields = mysql_fetch_assoc($result1)) {
            if ($fields['gm'] == 3) {
                $fields['gm'] = 0;
            }
            $gmMapid = $fields['gm'] . '-' . $fields['mapid'];
            $mtt['mtt-' . $gmMapid] = $fields['mtt'];
            $mbr['mbr-' . $gmMapid] = $fields['mbr'];
            $mwin['mwin-' . $gmMapid] = $fields['mwin'];
            $mlos['mlos-' . $gmMapid] = $fields['mlos'];
            $msc['msc-' . $gmMapid] = $fields['msc'];
            if (!isset($nick)) {
                $nick = $fields['subaccount'];
            }
        }
    }
    $mapFields = array('mtt', 'mbr', 'mwin', 'mlos', 'msc');
    foreach ($mapFields as $mp) {
        foreach ($$mp as $mfield => $mval) {
            $params[$mfield] = $mval;
        }
    }
    $mapData['params'] = $params;
    $mapData['nick'] = $nick;
    return $mapData;
}
    
?>