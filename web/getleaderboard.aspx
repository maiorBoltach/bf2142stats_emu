<?php

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('getleaderboard.txt', $str);
//auth=[hslbhg9zRog6VggiqFFaA__&pos=1&after=17&type=combatscore&gsa=zdKY7TfbhEBG2]Abh8lyKY__&
//$_GET['auth'] = "FuG6aZRMnpej44D7i6Ck9w__";
//$_GET['pos'] = "1";
//$_GET['after'] = "17";
//$_GET['type'] = "weapon";
//$_GET['id'] = "0";
//$_GET['type'] = "combatscore";

$timestamp = time();
if (isset($_GET["auth"]) AND $_GET["auth"] != "") {
    $auth = $_GET["auth"];
} else {
    echo "None: No error.";
    exit;
}
if (isset($_GET["pos"]) AND $_GET["pos"] != "" AND $_GET["pos"] >= 1) {
    $getpos = $_GET["pos"];
} else {
    errorcode(9981);
    exit;
}
if (isset($_GET["web"]) AND $_GET["web"] != "" AND $_GET["web"] == 1) {
    $getweb = $_GET["web"];
} else {
    $getweb = false;
}
if (isset($_GET["ccFilter"]) AND $_GET["ccFilter"] != "") {
    $ccFilter = $_GET["ccFilter"];
} else {
    $ccFilter = false;
}
if ($ccFilter != false) {
    $where_1 = " WHERE countryCode='" . $ccFilter . "'";
    $where_2 = " AND countryCode='" . $ccFilter . "'";
} else {
    $where_1 = "";
    $where_2 = "";
}
if (isset($_GET["after"]) AND $_GET["after"] != "" AND $_GET["after"] > 1) {
    if ($getweb != 1) {
        if ($_GET["after"] < 17) {
            $getafter = $_GET["after"];
        } else {
            $getafter = 18;
        }

    } else {
        if ($getweb == 1 AND $_GET["after"] < 50) {
            $getafter = $_GET["after"];
        } else {
            $getafter = 50;
        }
    }
} else {
    errorcode(9983);
}
if (isset($_GET["type"]) AND $_GET["type"] != "") {
    $gettype = $_GET["type"];
} else {
    errorcode(9984);
    exit;
}

if (isset($_GET["id"]) AND $_GET["id"] != "") {
    $id = $_GET["id"];
}





require_once("ea_support.php");
require_once('include/_ccconfig.php');

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
if (hexdec($code[26] . $code[27] . $code[24] . $code[25]) == 1) {
    $clType = 'server';
    $isServer = true;
} else {
    $clType = 'client';
    $isServer = false;
}

$connection = @mysql_connect($db_host, $db_user, $db_pass);

@mysql_select_db($db_name);
$query1 = "SELECT subaccount FROM `subaccount` WHERE id='" . $authPID . "' LIMIT 1";
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1) AND $getweb != 1) {
    errorcode(1041);
    exit;
} else {
    $row1 = mysql_fetch_array($result1);
}
$authPIDNick = rawurldecode($row1['subaccount']);

if ($getweb == true) {
//---------------------------------------------------------------------------------	

//overallscore
//combatscore
//risingstar
//commanderscore
//teamworkscore
//efficiency


//getleaderboard.aspx?auth=GPOH[dE3]UWEmjU3LvBXvg__&pos=1&after=16&type=supremecommander&gsa=123abc456def789ghiABCD__

//&ccFilter=UA
//&dogTagFilter=1
//&ccFilter=UA&dogTagFilter=1


    $query2 = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `stats_a` a ON a.pid=s.id
		WHERE a.gsco >= 0";
    $result2 = mysql_query($query2) or die(mysql_error());
    if (!mysql_num_rows($result2)) {
        errorcode(1042);
        exit;
    } else {
        $row2 = mysql_fetch_array($result2);
    }

    $Out = "O
H\tsize\tasof
D\t" . ($row2[0]) . "\t" . $timestamp . "";


     $query3 = "
      SELECT res.tt,res.pid,s.name,res.gsco,res.rnk,res.rownumber,country,res.ent-3 FROM (
      SELECT 1 as tt,t.pid,t.gsco,t.rnk,t.ent-3,(SELECT COUNT(*)+1 FROM stats_a a WHERE a.gsco > t.gsco) AS rownumber
      FROM (SELECT * FROM stats_a where pid=".$authPID." ORDER BY gsco DESC) t
      UNION
      SELECT r.* from (
      SELECT 2 as tt ,t.pid,t.gsco,t.rnk,t.ent-3,(SELECT COUNT(*)+1 FROM stats_a a WHERE a.gsco > t.gsco) AS rownumber
      FROM (SELECT * FROM stats_a ORDER BY gsco DESC) t LIMIT ".($getpos-1).",".$getafter.") r
      ) res
      LEFT JOIN `subaccounts` s ON s.pid=res.pid
      LEFT JOIN `accounts` c ON c.id=s.superid
      "; 

    $query3 = "SELECT s.id,s.subaccount,a.gsco,a.rnk,a.ent-3,c.countryCode FROM `stats_a` a
			LEFT JOIN `subaccount` s ON s.id=a.pid
			LEFT JOIN `account` c ON c.profileid=s.profileid
		WHERE 1 
			ORDER BY gsco DESC
			LIMIT " . ($getpos - 1) . "," . $getafter . "";
    $result3 = mysql_query($query3) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row3 = mysql_fetch_array($result3)) {
        $Out .= "
H\tpos\tpid\tnick\tglobalscore\tplayerrank\tcountrycode\tVet";


	$Out .= "
D\t".$ic."\t".$row3['pid']."\t".rawurldecode($row3['name'])."\t".$row3['gsco']."\t".$row3['rnk']."\t".$row3['country']."\t".$row3['ent-3']."";
        $Out .= "
D\t" . $ic . "\t" . $row3['pid'] . "\t" . rawurldecode($row3['subaccount']) . "\t" . $row3['gsco'] . "\t" . $row3['rnk'] . "\t" . $row3['countryCode'] . "\t0";
        $ic++;
    }






















//---------------------------------------------------------------------------------	
} elseif ($gettype == "supremecommander" AND !$isServer) {

	echo "O
H\tsize\tasof
D\t23\t".$timestamp."
";
echo "H	rank	nick	Week	Date	Times	Vet
D	1	-*NORMAND*-	38	1321737736	1	False




$	558	$
";
exit;

    $query2 = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `stats_a` a ON a.pid=s.id
		WHERE a.gsco >= 0" . $where_2;
    $result2 = mysql_query($query2) or die(mysql_error());
    if (!mysql_num_rows($result2)) {
        errorcode(1042);
        exit;
    } else {
        $row2 = mysql_fetch_array($result2);
    }


    $Out = "O
H\tsize\tasof
D\t" . ($row2[0] + 2) . "\t" . $timestamp . "
H\trank\tnick\tWeek\tDate\tTimes\tVet";


    $query3 = "

SELECT
	res.tt, res.pid, res.subaccount, res.klls, res.dths, res.kdr, res.ovaccu, res.rnk, res.rownumber, res.countryCode, `ent-3` AS Vet
FROM
	(
	SELECT
		1 as tt, s.subaccount, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.kdr, t.ovaccu, (SELECT COUNT(*)+1 FROM stats_a a WHERE a.klls > t.klls) AS rownumber, `ent-3`
	FROM
		( SELECT * FROM stats_a where pid=" . $authPID . " ORDER BY klls DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, s.subaccount, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.kdr, t.ovaccu, (SELECT COUNT(*)+1 FROM stats_a a WHERE a.klls > t.klls) AS rownumber, `ent-3`
				FROM
					(SELECT * FROM stats_a ORDER BY klls DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
//echo $query3;exit;
    $result3 = mysql_query($query3) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row3 = mysql_fetch_array($result3)) {
        if ($row3['tt'] == 1 and $getweb != 1) {
            $Out .= "

D\t" . $row3['rnk'] . "\t" . rawurldecode($row3['subaccount']) . "\t0\t0\t0\t0";
//            $Out .= "






//H\trank\tpos\tpid\tnick\tKills\tDeaths\tkdr\tAccuracy\tplayerrank\tcountrycode\tVet\tdt";







        } else {
//			$Out .= "
//D\t".$row3['rownumber']."\t".$ic."\t".$row3['pid']."\t".rawurldecode($row3['account'])."\t".
//$row3['klls']."\t".
//$row3['dths']."\t".
//($row3['kdr']/100)."\t".
//($row3['ovaccu']/10000)."\t".
//$row3['rnk']."\t".$row3['countryCode']."\t1\t0";
        }
        $ic++;
    }

//-------------- Overalscore ---------------------------
} elseif ($gettype == "overallscore" AND !$isServer) {

    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";

    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.gsco, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.gsco, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.gsco > t.gsco) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY gsco DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.gsco, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.gsco > t.gsco) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY gsco DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";

    $Out .= "
H\trank\tpos\tpid\tnick\tglobalscore\tplayerrank\tcountrycode\tVet";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['gsco'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tglobalscore\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['gsco'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
//------------------ commanderscore -----------------    
} elseif ($gettype == "commanderscore" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.coscore.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.cs, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.cs, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.cs > t.cs) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY cs DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.cs, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.cs > t.cs) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY cs DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.coscore.playerrank.countrycode.Vet
    $Out .= "
H\trank\tpos\tpid\tnick\tcoscore\tplayerrank\tcountrycode\tVet";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['cs'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tcoscore\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['cs'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }

//--------------- combatscore ----------------------    
}  elseif ($gettype == "combatscore" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.Kills.Deaths.kdr.Accuracy.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.klls, res.dths, res.ovaccu, res.kdr, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.ovaccu, t.kdr, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.klls > t.klls) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY klls DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.ovaccu, t.kdr, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.klls > t.klls) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY klls DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.Kills.Deaths.kdr.Accuracy.playerrank.countrycode.Vet
    $Out .= "
H\trank\tpos\tpid\tnick\tKills\tDeaths\tkdr\tAccuracy\tplayerrank\tcountrycode\tVet";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['klls'] . "\t" . $row['dths'] . "\t" . $row['kdr'] . "\t" . $row['ovaccu'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tKills\tDeaths\tkdr\tAccuracy\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['klls'] . "\t" . $row['dths'] . "\t" . $row['kdr'] . "\t" . $row['ovaccu'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
//--------------- risingstar ----------------------    
}  elseif ($gettype == "risingstar" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.PercentChange.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.klls, res.dths, res.ovaccu, res.kdr, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.ovaccu, t.kdr, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.klls > t.klls) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY klls DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.klls, t.dths, t.ovaccu, t.kdr, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.klls > t.klls) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY klls DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.Kills.Deaths.kdr.Accuracy.playerrank.countrycode.Vet
    $Out .= "
H\trank\tpos\tpid\tnick\tPercentChange\tplayerrank\tcountrycode\tVet\tdt";
//H\trank\tpos\tpid\tnick\tkdr\tAccuracy\tplayerrank\tcountrycode\tVet";
    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['pc'] . "\t" . $row['kdr'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tPercentChange\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['pc'] . "\t" . $row['kdr'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
//--------------- teamworkscore ---------------------   
} elseif ($gettype == "teamworkscore" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.teamworkscore.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.twsc, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.twsc, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.cs > t.cs) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY cs DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.twsc, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.cs > t.cs) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY cs DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.teamworkscore.playerrank.countrycode.Vet
    $Out .= "
H\trank\tpos\tpid\tnick\tteamworkscore\tplayerrank\tcountrycode\tVet";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['twsc'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tteamworkscore\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['twsc'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }

//---------------- efficiency ---------------------    
} elseif ($gettype == "efficiency" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="
H\trank\tpos\tpid\tnick\tEfficiency\tplayerrank\tcountrycode\tVet
D";
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.spm, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.spm, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.spm > t.spm) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM playerprogress where pid=" . $authPID . " ORDER BY spm DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, t.nick, c.countryCode, t.pid, t.rnk, t.spm, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.spm > t.spm) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM playerprogress ORDER BY spm DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.teamworkscore.playerrank.countrycode.Vet
    $Out .= "
H	rank	pos	pid	nick	Efficiency	playerrank	countrycode	Vet	dt";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['spm'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tefficiency\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['spm'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
 
//------------------- weapon -----------    
} elseif ($gettype == "weapon" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.kills.deaths.accuracy.kdr.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.`wkls-".$id."` AS kills, res.`wdths-".$id."` AS deaths, res.`waccu-".$id."` AS accuracy, res.`wkdr-".$id."` AS kdr, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, p.nick, c.countryCode, p.pid, p.rnk, w.`wkls-".$id."`, w.`wdths-".$id."`, w.`waccu-".$id."`, w.`wkdr-".$id."`, (SELECT COUNT(*)+1 FROM `stats_w` ww WHERE ww.`wkls-".$id."` > w.`wkls-".$id."`) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM `stats_w` where pid=" . $authPID . " ORDER BY `wkls-".$id."` DESC ) w
		LEFT JOIN `subaccount` s ON s.id=w.pid 
		LEFT JOIN `playerprogress` p ON p.pid=w.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, p.nick, c.countryCode, p.pid, p.rnk, w.`wkls-".$id."`, w.`wdths-".$id."`, w.`waccu-".$id."`, w.`wkdr-".$id."`, (SELECT COUNT(*)+1 FROM `stats_w` ww WHERE ww.`wkls-".$id."` > w.`wkls-".$id."`) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM `stats_w` ORDER BY `wkls-".$id."` DESC) w
					LEFT JOIN `subaccount` s ON s.id=w.pid 
                                        LEFT JOIN `playerprogress` p ON p.pid=w.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.kills.deaths.accuracy.kdr.playerrank.countrycode.Vet






    $Out .= "
H\trank\tpos\tpid\tnick\tkills\tdeaths\tkdr\taccuracy\tplayerrank\tcountrycode\tVet";







    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
	if ($row['pid'] == "") continue;
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['kills'] . "\t" . $row['deaths'] . "\t" .(round($row['kills']/$row['deaths'],2)) ."\t".$row['accuracy']."\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tkills\tdeaths\taccuracy\tplayerrank\tcountrycode\tVet\tdt";
        } else {
		if ($row['deaths'] == 0) $dth = 1; else $dth = $row['deaths'];
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['kills'] . "\t" . $row['deaths']  ."\t".(round($row['kills']/$dth,2))."\t".$row['accuracy']."\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
//------------------- vehicle -----------    
} elseif ($gettype == "vehicle" AND !$isServer) {
    
    $query = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `playerprogress` p ON p.pid=s.id
		WHERE p.gsco >= 0" . $where_2;
    $res = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($res)) {
        errorcode(1042);
        exit;
    } else {
        $row = mysql_fetch_array($res);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row[0] + 2) . "\t" . $timestamp;

    $Out.="\n";
//H.rank.pos.pid.nick.kills.deaths.roadkills.playerrank.countrycode.Vet
    $query = "

SELECT
	res.rnk, res.pid, res.pos, res.nick, res.`vkls-".$id."` AS kills, res.`vdths-".$id."` AS deaths, res.`vrkls-".$id."` AS roadkills, res.countryCode, res.Vet
FROM
	(
	SELECT
		1 as tt, p.nick, c.countryCode, p.pid, p.rnk, v.`vkls-".$id."`, v.`vdths-".$id."`, v.`vrkls-".$id."`, (SELECT COUNT(*)+1 FROM `stats_v` vv WHERE vv.`vkls-".$id."` > v.`vkls-".$id."`) AS pos, 0 AS Vet
	FROM
		( SELECT * FROM `stats_v` where pid=" . $authPID . " ORDER BY `vkls-".$id."` DESC ) v
		LEFT JOIN `subaccount` s ON s.id=v.pid 
		LEFT JOIN `playerprogress` p ON p.pid=v.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, p.nick, c.countryCode, p.pid, p.rnk, v.`vkls-".$id."`, v.`vdths-".$id."`, v.`vrkls-".$id."`, (SELECT COUNT(*)+1 FROM `stats_v` vv WHERE vv.`vkls-".$id."` > v.`vkls-".$id."`) AS pos, 0 AS Vet
				FROM
					(SELECT * FROM `stats_v` ORDER BY `vkls-".$id."` DESC) v
					LEFT JOIN `subaccount` s ON s.id=v.pid 
                                        LEFT JOIN `playerprogress` p ON p.pid=v.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    
//H.rank.pos.pid.nick.kills.deaths.roadkills.playerrank.countrycode.Vet.dt
    $Out .= "
H\trank\tpos\tpid\tnick\tkills\tdeaths\troadkills\tplayerrank\tcountrycode\tVet";

    $res = mysql_query($query) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row = mysql_fetch_array($res)) {
        if ($row['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['kills'] . "\t" . $row['deaths'] . "\t" . $row['roadkills'] .  "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tkills\tdeaths\troadkills\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row['rnk'] . "\t" . $row['pos'] . "\t" . $row['pid'] . "\t" . rawurldecode($row['nick']) . "\t" . $row['kills'] . "\t" . $row['deaths'] . "\t" . $row['roadkills'] . "\t" . $row['rnk'] . "\t" . $row['countryCode'] . "\t0\t0";
        }
        $ic++;
    }

} elseif (!$isServer) {
    $query2 = "SELECT count(*) FROM `subaccount` s
		LEFT JOIN `account` c ON c.profileid=s.profileid
		LEFT JOIN `stats_a` a ON a.pid=s.id
		WHERE a.gsco >= 0" . $where_2;
    $result2 = mysql_query($query2) or die(mysql_error());
    if (!mysql_num_rows($result2)) {
        errorcode(1042);
        exit;
    } else {
        $row2 = mysql_fetch_array($result2);
    }
    $Out = "O
H\tsize\tasof
D\t" . ($row2[0] + 2) . "\t" . $timestamp . "
H\trank\tpos\tpid\tnick\tglobalscore\tplayerrank\tcountrycode\tVet";
    $query3 = "
SELECT
	res.tt, res.pid, res.subaccount, res.gsco, res.rnk, res.rownumber, countryCode, `ent-3` AS Vet
FROM
	(
	SELECT
		1 as tt, s.subaccount, c.countryCode, t.pid, t.gsco, t.rnk, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.gsco > t.gsco) AS rownumber, `ent-3`
	FROM
		( SELECT * FROM playerprogress p where pid=" . $authPID . " ORDER BY gsco DESC ) t
		LEFT JOIN `subaccount` s ON s.id=t.pid 
		LEFT JOIN `account` c ON c.profileid=s.profileid
		" . $where_1 . "
	UNION
		SELECT
			r.*
		FROM 
			( SELECT 2 as tt, s.subaccount, c.countryCode, t.pid, t.gsco, t.rnk, (SELECT COUNT(*)+1 FROM playerprogress p WHERE p.gsco > t.gsco) AS rownumber, `ent-3`
				FROM
					(SELECT * FROM playerprogress ORDER BY gsco DESC) t
					LEFT JOIN `subaccount` s ON s.id=t.pid 
					LEFT JOIN `account` c ON c.profileid=s.profileid
					" . $where_1 . "
				LIMIT " . ($getpos - 1) . "," . $getafter . "
			) r
	) res
";
    $result3 = mysql_query($query3) or die(mysql_error());
    $ic = ($getpos - 1);
    while ($row3 = mysql_fetch_array($result3)) {
        if ($row3['tt'] == 1 and $getweb != 1) {
            $Out .= "
D\t" . $row3['rnk'] . "\t" . $row3['rownumber'] . "\t" . $row3['pid'] . "\t" . rawurldecode($row3['subaccount']) . "\t" . $row3['gsco'] . "\t" . $row3['rnk'] . "\t" . $row3['countryCode'] . "\t0";
            $Out .= "
H\trank\tpos\tpid\tnick\tglobalscore\tplayerrank\tcountrycode\tVet\tdt";
        } else {
            $Out .= "
D\t" . $row3['rownumber'] . "\t" . $ic . "\t" . $row3['pid'] . "\t" . rawurldecode($row3['subaccount']) . "\t" . $row3['gsco'] . "\t" . $row3['rnk'] . "\t" . $row3['countryCode'] . "\t0\t0";
        }
        $ic++;
    }
} else {
    
}



/*

  E 998 $ 4 $ ---> !pos=1 !after=17 !type=overallscore

  if (isset($_GET["pos"]) AND $_GET["pos"] != "" AND $_GET["pos"] > 1) {	$getPOS = $_GET["pos"];	} else {	errorcode(998);exit;	}
  if (isset($_GET["after"]) AND $_GET["after"] != "" AND $_GET["after"] < 18 AND $_GET["after"] > 0) {	$getafter = $_GET["after"];	} else {	errorcode(998);exit;	}
  if (isset($_GET["type"]) AND $_GET["type"] != "") {	$gettype = $_GET["type"];	} else {	errorcode(998);exit;	}

  E 998 $ 4 $
  getleaderboard.aspx?auth='.$auth.'&pos=1&after=17&type=overallscore



  O

  H	size	asof
  D	419269	1162837710

  H	rank	pos	pid	nick	globalscore	playerrank	countrycode	Vet
  D	10	10	81260470	coathanger	23267	40	US	1

  H	rank	pos	pid	nick	globalscore	playerrank	countrycode	Vet	dt
  D	1	1	78333455	bftest11	38441	18	GB	0	0
  D	2	2	81278799	Skunk_2142	33402	40	CA	0	0
  D	3	3	81242951	WoKeN	30640	40	US	1	0
  D	4	4	81209964	KAIN	29844	40	DE	0	0
  D	5	5	81243016	serguinho	23939	40	AT	1	0
  D	6	6	81286874	Pallares	23853	40	ES	1	0
  D	7	7	81285172	line.iq	23301	40	DE	1	0
  D	8	8	65896813	S_Jackson	23270	40	GB	1	0
  D	9	9	81239550	RA4EVAH	23268	40	NL	0	0
  D	10	10	81260470	coathanger	23267	40	US	1	0
  D	11	11	81350339	s2n][ksk	22091	39	DE	1	0
  D	12	12	81269430	HarveyCamper	21897	36	US	1	0
  D	13	13	81167017	syn1cal	21708	36	CA	1	0
  D	14	14	81346902	xXx[GER]	20476	35	DE	0	0
  D	15	15	81218946	EPoX	20256	40	FR	1	0
  D	16	16	81246378	Edge.	20049	39	US	0	0
  D	17	17	81285438	Djflexxer	20040	39	CH	1	0
  D	18	18	81242447	WarpaQ	19778	39	US	1	0
  $	712	$










































 */
$countOut = preg_replace('/[\t\n]/', '', $Out);
print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
@mysql_close($connection);

function errorcode($errorcode=104) {
    $Out = "E\t" . $errorcode;
    $countOut = preg_replace('/[\t\n]/', '', $Out);
    print $Out . "\n$\t" . strlen($countOut) . "\t$\n";
}

?>