<?php

$timestamp = time();

$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('getawardsinfo.txt', $str);
//$_GET['auth'] = "CLKUKGQ]xBOzSu7omJPsGw__";

if(isset($_GET["auth"]) AND $_GET["auth"] != "") {	$auth = $_GET["auth"];	} else {	echo "None: No error.";exit;	}




require_once("ea_support.php");
require_once('include/_ccconfig.php');

$bfcoding  = new ea_stats();
$code = $bfcoding->str2hex($bfcoding->DefDecryptBlock($bfcoding->getBase64Decode($auth)));
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
$query1 = "SELECT subaccount FROM `subaccount` WHERE id='".$authPID."' LIMIT 1";
$result1 = mysql_query($query1) or die(mysql_error());
if (!mysql_num_rows($result1)) {
	errorcode(104);
	exit;
} else {
	$row1 = mysql_fetch_array($result1);
}
$authPIDNick = rawurldecode($row1['subaccount']);

$Out = "O
H\tpid\tnick\tasof
D\t".$authPID."\t".$authPIDNick."\t".$timestamp."
H\taward\tlevel\twhen\tfirst";

	$query2 = "SELECT * FROM `awards` WHERE pid='".$authPID."'";
	$result2 = mysql_query($query2) or die(mysql_error());
	if (mysql_num_rows($result2)) {
		while ($row2 = mysql_fetch_array($result2)) {
			$award = $row2['atype'].str_pad($row2['aid'], 2, "0", STR_PAD_LEFT);
			if ($row2['atype'] == 1) {
				$award .= "_".$row2['alvl'];
				$level = 0;
				$earned = $row2['earned'];
				$first = 0;
			} elseif ($row2['atype'] == 2) {
				$level = $row2['alvl'];
				$earned = $row2['earned'];
				$first = $row2['first'];
			} elseif ($row2['atype'] == 3) {
				$level = 0;
				$earned = $row2['earned'];
				$first = 0;
			} elseif ($row2['atype'] == 4) {
				$level = $row2['alvl'];
				$earned = $row2['earned'];
				$first = $row2['first'];
			}
			if ($row2['atype'] == 1 OR $row2['atype'] == 3) {
				$first = 0;
				$level = 0;
			} else {
				$level = $row2['alvl'];
			}
			$Out .= "\nD\t".$award."\t".$level."\t".$earned."\t".$first;
		}
	}


/*
SELECT * 
FROM `awards` 
LIMIT 0 , 30





O
H	pid	nick	asof
D	81260470	coathanger	1162844288
H	award	level	when	first
D	104_1	0	1161341461	0
D	104_2	0	1161616569	0
D	104_3	0	1161770391	0
D	200	53	1162738740	1161195361
D	201	90	1162768463	1161185001
D	202	126	1162770922	1161175250
D	204	1	1161228610	1161228610
D	302	0	1161228610	0
D	400	690	1162772669	1161175250
D	401	170	1162772669	1161178311
D	402	1	1161622680	1161622680
$	1512	$
*/
/*
AWARD_NAME_100_1    Basic Support Service Badge 
AWARD_NAME_101_1    Basic Recon Service Badge
AWARD_NAME_102_1    Basic Assault Service Badge
AWARD_NAME_103_1    Basic Engineer Service Badge
AWARD_NAME_104_1    Basic Squad Leader Badge
AWARD_NAME_105_1    Basic Collectors Badge
AWARD_NAME_106_1    Basic Pistol Commendation Badge
AWARD_NAME_107_1    Basic Explosive Gallantry Badge
AWARD_NAME_108_1    Basic Air Defense Badge
AWARD_NAME_109_1    Basic Commander Excellence Badge
AWARD_NAME_110_1    Basic Titan Commander Badge
AWARD_NAME_111_1    Basic Engineer Excellence Badge
AWARD_NAME_112_1    Basic Medic Excellence Badge
AWARD_NAME_113_1    Basic Resupply Service Badge
AWARD_NAME_114_1    Basic Armor Service Badge  
AWARD_NAME_115_1    Basic Aircraft Service Badge
AWARD_NAME_116_1    Basic Transport Service Badge
AWARD_NAME_117_1    Basic Titan Combat Excellence Badge
AWARD_NAME_118_1    Basic Titan Defense Excellence Badge
AWARD_NAME_119_1    Basic Titan Destruction Achievement Badge
AWARD_NAME_120_1    Arctic Combat Badge Bronze
AWARD_NAME_121_1    Vehicle Excellence Badge Bronze
 
AWARD_NAME_100_2    Veteran Support Service Badge 
AWARD_NAME_101_2    Veteran Recon Service Badge
AWARD_NAME_102_2    Veteran Assault Service Badge
AWARD_NAME_103_2    Veteran Engineer Service Badge
AWARD_NAME_104_2    Veteran Squad Leader Badge
AWARD_NAME_105_2    Veteran Collectors Badge
AWARD_NAME_106_2    Veteran Pistol Commendation Badge
AWARD_NAME_107_2    Veteran Explosive Gallantry Badge
AWARD_NAME_108_2    Veteran Air Defense Badge
AWARD_NAME_109_2    Veteran Commander Excellence Badge
AWARD_NAME_110_2    Veteran Titan Commander Badge
AWARD_NAME_111_2    Veteran Engineer Excellence Badge
AWARD_NAME_112_2    Veteran Medic Excellence Badge
AWARD_NAME_113_2    Veteran Resupply Service Badge
AWARD_NAME_114_2    Veteran Armor Service Badge  
AWARD_NAME_115_2    Veteran Aircraft Service Badge
AWARD_NAME_116_2    Veteran Transport Service Badge
AWARD_NAME_117_2    Veteran Titan Combat Excellence Badge
AWARD_NAME_118_2    Veteran Titan Defense Excellence Badge
AWARD_NAME_119_2    Veteran Titan Destruction Achievement Badge
AWARD_NAME_120_2    Arctic Combat Badge Silver 
AWARD_NAME_121_2    Vehicle Excellence Badge Silver 

AWARD_NAME_100_3    Expert Support Service Badge 
AWARD_NAME_101_3    Expert Recon Service Badge
AWARD_NAME_102_3    Expert Assault Service Badge
AWARD_NAME_103_3    Expert Engineer Service Badge
AWARD_NAME_104_3    Expert Squad Leader Badge
AWARD_NAME_105_3    Expert Collectors Badge
AWARD_NAME_106_3    Expert Pistol Commendation Badge
AWARD_NAME_107_3    Expert Explosive Gallantry Badge
AWARD_NAME_108_3    Expert Air Defense Badge
AWARD_NAME_109_3    Expert Commander Excellence Badge
AWARD_NAME_110_3    Expert Titan Commander Badge
AWARD_NAME_111_3    Expert Engineer Excellence Badge
AWARD_NAME_112_3    Expert Medic Excellence Badge
AWARD_NAME_113_3    Expert Resupply Service Badge
AWARD_NAME_114_3    Expert Armor Service Badge  
AWARD_NAME_115_3    Expert Aircraft Service Badge
AWARD_NAME_116_3    Expert Transport Service Badge
AWARD_NAME_117_3    Expert Titan Combat Excellence Badge
AWARD_NAME_118_3    Expert Titan Defense Excellence Badge
AWARD_NAME_119_3    Expert Titan Destruction Achievement Badge
AWARD_NAME_120_3    Arctic Combat Badge Gold 
AWARD_NAME_121_3    Vehicle Excellence Badge Gold 

AWARD_NAME_200    Bronze Star
AWARD_NAME_201    Silver Star
AWARD_NAME_202    Gold Star

AWARD_NAME_203    Distinguished Service Medal
AWARD_NAME_204    Infantry Combat Medal
AWARD_NAME_205    Meritorious Infantry Combat Medal
AWARD_NAME_206    Infantry Combat of Merit Medal
AWARD_NAME_207    Medal of Gallantry
AWARD_NAME_208    European Honorific Cross
AWARD_NAME_209    Distinguished Pan Asian Star
AWARD_NAME_210    Meritorious Conquest Medal
AWARD_NAME_211    Meritorious Titan Medal
AWARD_NAME_212    Aircraft Combat Medal
AWARD_NAME_213    Armor Service Medal
AWARD_NAME_214    Good Conduct Medal
AWARD_NAME_215    Honorable Service Medal
AWARD_NAME_216    Purple Heart
AWARD_NAME_217    Air Transport Transfer Medal
AWARD_NAME_218    Titan Medallion
AWARD_NAME_219    Ground Base Medallion

AWARD_NAME_300    Air Defense Ribbon
AWARD_NAME_301    Aircraft Service Ribbon
AWARD_NAME_302    HALO Ribbon
AWARD_NAME_303    Infantry Officer Ribbon
AWARD_NAME_304    Combat Commander Ribbon
AWARD_NAME_305    Distinguished Unit Service Ribbon
AWARD_NAME_306    Meritorious Unit Service Ribbon
AWARD_NAME_307    Valorous Unit Service Ribbon
AWARD_NAME_308    War College Ribbon
AWARD_NAME_309    Armored Service Ribbon
AWARD_NAME_310    Crew Service Ribbon
AWARD_NAME_311    Pac Duty Ribbon
AWARD_NAME_312    European Duty Ribbon
AWARD_NAME_313    Soldier Merit Ribbon
AWARD_NAME_314    Good Conduct Ribbon
AWARD_NAME_315    Legion Of Merit Ribbon
AWARD_NAME_316    Ground Base Defense Ribbon
AWARD_NAME_317    Aerial Service Ribbon
AWARD_NAME_318    Titan Aerial Defense Ribbon
AWARD_NAME_319    Titan Commander Ribbon
AWARD_NAME_320    Operation Snowflake Ribbon 
AWARD_NAME_321    Cold Front Unit Service Ribbon 
AWARD_NAME_322    Transporter Duty Ribbon 
AWARD_NAME_323    Meritorious Winterstrike Ribbon 

AWARD_NAME_400    Combat Efficiency Pin 
AWARD_NAME_401    Distinguished Combat Efficiency Pin 
AWARD_NAME_402    Problem solver Pin 
AWARD_NAME_403    Titan Destructor Pin 
AWARD_NAME_404    Troop Transporter Pin
AWARD_NAME_405    Wings of Glory Pin
AWARD_NAME_406    Titan Defender Pin
AWARD_NAME_407    Infiltrator Pin   
AWARD_NAME_408    Wheels of Hazard Pin
AWARD_NAME_409    Collectors Pin
AWARD_NAME_410    Explosive Efficiency Pin 
AWARD_NAME_411    Emergency Rescue Pin 
AWARD_NAME_412    Titan survival Pin
AWARD_NAME_413    Firearm Efficiency Pin 
AWARD_NAME_414    Clear skies Pin
AWARD_NAME_415    Close Combat Pin
AWARD_NAME_416    Assault Lines Attack Pin 
*/
$countOut = preg_replace('/[\t\n]/','',$Out);
print $Out."\n$\t".strlen($countOut)."\t$\n";
@mysql_close($connection);
function errorcode($errorcode=104) {
	$Out = "E\t".$errorcode;
	$countOut = preg_replace('/[\t\n]/','',$Out);
	print $Out."\n$\t".strlen($countOut)."\t$\n";
}
?>