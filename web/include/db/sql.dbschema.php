<?php

$sqlschema = array();

#
# Table structure for table `_version`
#

$sqlschema[] = array('Version',	
"CREATE TABLE `_version` (
  `dbver` varchar(20) NOT NULL default '',
  `dbdate` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`dbver`),
  KEY `dbver` (`dbver`)
) TYPE=MyISAM;");

#
# Table structure for table `army`
#

$sqlschema[] = array('Army Table',	
"CREATE TABLE `army` (
  `id` int(8) unsigned NOT NULL default '0',
  `time0` int(10) unsigned NOT NULL default '0',
  `win0` int(10) unsigned NOT NULL default '0',
  `loss0` int(10) unsigned NOT NULL default '0',
  `score0` int(10) unsigned NOT NULL default '0',
  `best0` int(10) unsigned NOT NULL default '0',
  `worst0` int(10) unsigned NOT NULL default '0',
  `brnd0` int(10) unsigned NOT NULL default '0',
  `time1` int(10) unsigned NOT NULL default '0',
  `win1` int(10) unsigned NOT NULL default '0',
  `loss1` int(10) unsigned NOT NULL default '0',
  `score1` int(10) unsigned NOT NULL default '0',
  `best1` int(10) unsigned NOT NULL default '0',
  `worst1` int(10) unsigned NOT NULL default '0',
  `brnd1` int(10) unsigned NOT NULL default '0',
  `time2` int(10) unsigned NOT NULL default '0',
  `win2` int(10) unsigned NOT NULL default '0',
  `loss2` int(10) unsigned NOT NULL default '0',
  `score2` int(10) unsigned NOT NULL default '0',
  `best2` int(10) unsigned NOT NULL default '0',
  `worst2` int(10) unsigned NOT NULL default '0',
  `brnd2` int(10) unsigned NOT NULL default '0',
  `time3` int(10) unsigned NOT NULL default '0',
  `win3` int(10) unsigned NOT NULL default '0',
  `loss3` int(10) unsigned NOT NULL default '0',
  `score3` int(10) unsigned NOT NULL default '0',
  `best3` int(10) unsigned NOT NULL default '0',
  `worst3` int(10) unsigned NOT NULL default '0',
  `brnd3` int(10) unsigned NOT NULL default '0',
  `time4` int(10) unsigned NOT NULL default '0',
  `win4` int(10) unsigned NOT NULL default '0',
  `loss4` int(10) unsigned NOT NULL default '0',
  `score4` int(10) unsigned NOT NULL default '0',
  `best4` int(10) unsigned NOT NULL default '0',
  `worst4` int(10) unsigned NOT NULL default '0',
  `brnd4` int(10) unsigned NOT NULL default '0',
  `time5` int(10) unsigned NOT NULL default '0',
  `win5` int(10) unsigned NOT NULL default '0',
  `loss5` int(10) unsigned NOT NULL default '0',
  `score5` int(10) unsigned NOT NULL default '0',
  `best5` int(10) unsigned NOT NULL default '0',
  `worst5` int(10) unsigned NOT NULL default '0',
  `brnd5` int(10) unsigned NOT NULL default '0',
  `time6` int(10) unsigned NOT NULL default '0',
  `win6` int(10) unsigned NOT NULL default '0',
  `loss6` int(10) unsigned NOT NULL default '0',
  `score6` int(10) unsigned NOT NULL default '0',
  `best6` int(10) unsigned NOT NULL default '0',
  `worst6` int(10) unsigned NOT NULL default '0',
  `brnd6` int(10) unsigned NOT NULL default '0',
  `time7` int(10) unsigned NOT NULL default '0',
  `win7` int(10) unsigned NOT NULL default '0',
  `loss7` int(10) unsigned NOT NULL default '0',
  `score7` int(10) unsigned NOT NULL default '0',
  `best7` int(10) unsigned NOT NULL default '0',
  `worst7` int(10) unsigned NOT NULL default '0',
  `brnd7` int(10) unsigned NOT NULL default '0',
  `time8` int(10) unsigned NOT NULL default '0',
  `win8` int(10) unsigned NOT NULL default '0',
  `loss8` int(10) unsigned NOT NULL default '0',
  `score8` int(10) unsigned NOT NULL default '0',
  `best8` int(10) unsigned NOT NULL default '0',
  `worst8` int(10) unsigned NOT NULL default '0',
  `brnd8` int(10) unsigned NOT NULL default '0',
  `time9` int(10) unsigned NOT NULL default '0',
  `win9` int(10) unsigned NOT NULL default '0',
  `loss9` int(10) unsigned NOT NULL default '0',
  `score9` int(10) unsigned NOT NULL default '0',
  `best9` int(10) unsigned NOT NULL default '0',
  `worst9` int(10) unsigned NOT NULL default '0',
  `brnd9` int(10) unsigned NOT NULL default '0',
  `time10` int(10) unsigned NOT NULL default '0',
  `win10` int(10) unsigned NOT NULL default '0',
  `loss10` int(10) unsigned NOT NULL default '0',
  `score10` int(10) unsigned NOT NULL default '0',
  `best10` int(10) unsigned NOT NULL default '0',
  `worst10` int(10) unsigned NOT NULL default '0',
  `brnd10` int(10) unsigned NOT NULL default '0',
  `time11` int(10) unsigned NOT NULL default '0',
  `win11` int(10) unsigned NOT NULL default '0',
  `loss11` int(10) unsigned NOT NULL default '0',
  `score11` int(10) unsigned NOT NULL default '0',
  `best11` int(10) unsigned NOT NULL default '0',
  `worst11` int(10) unsigned NOT NULL default '0',
  `brnd11` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");

#
# Table structure for table `awards`
#

$sqlschema[] = array('Awards Table',	
"CREATE TABLE `awards` (
  `id` int(8) unsigned NOT NULL default '0',
  `awd` mediumint(7) unsigned NOT NULL default '0',
  `level` int(10) unsigned NOT NULL default '0',
  `earned` int(10) unsigned NOT NULL default '0',
  `first` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`awd`,`level`)
) TYPE=MyISAM;");

#
# Table structure for table `ip2nation`
#

$sqlschema[] = array('IP 2 Nation',	
"CREATE TABLE `ip2nation` (
  `ip` int(11) unsigned NOT NULL default '0',
  `country` char(2) NOT NULL default '',
  PRIMARY KEY  (`ip`),
  KEY `ip` (`ip`)
) TYPE=MyISAM;");

#
# Table structure for table `kills`
#

$sqlschema[] = array('Kills Table',	
"CREATE TABLE `kills` (
  `attacker` int(8) unsigned NOT NULL default '0',
  `victim` int(8) unsigned NOT NULL default '0',
  `count` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`attacker`,`victim`)
) TYPE=MyISAM;");

#
# Table structure for table `kits`
#

$sqlschema[] = array('Kits Table',	
"CREATE TABLE `kits` (
  `id` int(8) unsigned NOT NULL default '0',
  `time0` int(10) unsigned NOT NULL default '0',
  `kills0` int(10) unsigned NOT NULL default '0',
  `deaths0` int(10) unsigned NOT NULL default '0',
  `time1` int(10) unsigned NOT NULL default '0',
  `kills1` int(10) unsigned NOT NULL default '0',
  `deaths1` int(10) unsigned NOT NULL default '0',
  `time2` int(10) unsigned NOT NULL default '0',
  `kills2` int(10) unsigned NOT NULL default '0',
  `deaths2` int(10) unsigned NOT NULL default '0',
  `time3` int(10) unsigned NOT NULL default '0',
  `kills3` int(10) unsigned NOT NULL default '0',
  `deaths3` int(10) unsigned NOT NULL default '0',
  `time4` int(10) unsigned NOT NULL default '0',
  `kills4` int(10) unsigned NOT NULL default '0',
  `deaths4` int(10) unsigned NOT NULL default '0',
  `time5` int(10) unsigned NOT NULL default '0',
  `kills5` int(10) unsigned NOT NULL default '0',
  `deaths5` int(10) unsigned NOT NULL default '0',
  `time6` int(10) unsigned NOT NULL default '0',
  `kills6` int(10) unsigned NOT NULL default '0',
  `deaths6` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");


#
# Table structure for table `mapinfo`
#

$sqlschema[] = array('Map Info Table',	
"CREATE TABLE `mapinfo` (
  `id` smallint(3) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `score` int(10) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `times` int(10) unsigned NOT NULL default '0',
  `kills` int(10) unsigned NOT NULL default '0',
  `deaths` int(10) unsigned NOT NULL default '0',
  `custom` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idxMapName` (`name`)
) TYPE=MyISAM;");

#
# Table structure for table `maps`
#

$sqlschema[] = array('Maps Table',	
"CREATE TABLE `maps` (
  `id` int(8) unsigned NOT NULL default '0',
  `mapid` smallint(3) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `win` int(10) unsigned NOT NULL default '0',
  `loss` int(10) unsigned NOT NULL default '0',
  `best` int(10) unsigned NOT NULL default '0',
  `worst` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`mapid`)
) TYPE=MyISAM;");

#
# Table structure for table `player`
#

$sqlschema[] = array('Player Table',	
"CREATE TABLE `player` (
  `id` int(8) unsigned NOT NULL default '0',
  `name` varchar(45) NOT NULL default '',
  `country` char(2) NOT NULL default '',
  `time` int(10) unsigned NOT NULL default '0',
  `rounds` int(10) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `score` int(10) unsigned NOT NULL default '0',
  `cmdscore` int(10) unsigned NOT NULL default '0',
  `skillscore` int(10) unsigned NOT NULL default '0',
  `teamscore` int(10) unsigned NOT NULL default '0',
  `kills` int(10) unsigned NOT NULL default '0',
  `deaths` int(10) unsigned NOT NULL default '0',
  `captures` int(10) unsigned NOT NULL default '0',
  `neutralizes` int(10) unsigned NOT NULL default '0',
  `captureassists` int(10) unsigned NOT NULL default '0',
  `neutralizeassists` int(10) unsigned NOT NULL default '0',
  `defends` int(10) unsigned NOT NULL default '0',
  `damageassists` int(10) unsigned NOT NULL default '0',
  `heals` int(10) unsigned NOT NULL default '0',
  `revives` int(10) unsigned NOT NULL default '0',
  `ammos` int(10) unsigned NOT NULL default '0',
  `repairs` int(10) unsigned NOT NULL default '0',
  `targetassists` int(10) unsigned NOT NULL default '0',
  `driverspecials` int(10) unsigned NOT NULL default '0',
  `driverassists` int(10) unsigned NOT NULL default '0',
  `passengerassists` int(10) unsigned NOT NULL default '0',
  `teamkills` int(10) unsigned NOT NULL default '0',
  `teamdamage` int(10) unsigned NOT NULL default '0',
  `teamvehicledamage` int(10) unsigned NOT NULL default '0',
  `suicides` int(10) unsigned NOT NULL default '0',
  `killstreak` int(10) unsigned NOT NULL default '0',
  `deathstreak` int(10) unsigned NOT NULL default '0',
  `rank` tinyint(2) unsigned NOT NULL default '0',
  `banned` int(10) unsigned NOT NULL default '0',
  `kicked` int(10) unsigned NOT NULL default '0',
  `cmdtime` int(10) unsigned NOT NULL default '0',
  `sqltime` int(10) unsigned NOT NULL default '0',
  `sqmtime` int(10) unsigned NOT NULL default '0',
  `lwtime` int(10) unsigned NOT NULL default '0',
  `wins` int(10) unsigned NOT NULL default '0',
  `losses` int(10) unsigned NOT NULL default '0',
  `availunlocks` tinyint(1) unsigned NOT NULL default '0',
  `usedunlocks` tinyint(1) unsigned NOT NULL default '0',
  `joined` int(10) unsigned NOT NULL default '0',
  `rndscore` int(10) unsigned NOT NULL default '0',
  `lastonline` int(10) unsigned NOT NULL default '0',
  `chng` tinyint(1) unsigned NOT NULL default '0',
  `decr` tinyint(1) unsigned NOT NULL default '0',
  `mode0` int(10) unsigned NOT NULL default '0',
  `mode1` int(10) unsigned NOT NULL default '0',
  `mode2` int(10) unsigned NOT NULL default '0',
  `permban` tinyint(1) unsigned NOT NULL default '0',
  `clantag` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `ScoreIdx` (`score`),
  KEY `CmdScoreIdx` (`cmdscore`),
  KEY `TeamScoreIdx` (`teamscore`),
  KEY `KillsIdx` (`kills`)
) TYPE=MyISAM;");

#
# Table structure for table `player_history`
#

$sqlschema[] = array('Player History Table',	
"CREATE TABLE  `player_history` (
	`id` int(8) unsigned NOT NULL default '0',
	`timestamp` int(10) unsigned NOT NULL default '0',
	`time` int(10) unsigned NOT NULL default '0',
	`score` int(10) unsigned NOT NULL default '0',
	`cmdscore` int(10) unsigned NOT NULL default '0',
	`skillscore` int(10) unsigned NOT NULL default '0',
	`teamscore` int(10) unsigned NOT NULL default '0',
	`kills` int(10) unsigned NOT NULL default '0',
	`deaths` int(10) unsigned NOT NULL default '0',
	`rank` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`, `timestamp`),
  KEY (`score`)
) TYPE=MyISAM;");

#
# Table structure for table `round_history`
#

$sqlschema[] = array('Round History Table',
	"CREATE TABLE `round_history` (
		`id` SERIAL,
		`timestamp` int(10) unsigned NOT NULL default '0',
		`mapid` int(8) unsigned NOT NULL default '0',
		`time` int(10) unsigned NOT NULL default '0',
		`team1` tinyint(2) unsigned NOT NULL default '0',
		`team2` tinyint(2) unsigned NOT NULL default '0',
		`tickets1` int(10) unsigned NOT NULL default '0',
		`tickets2` int(10) unsigned NOT NULL default '0',
		`pids1` int(10) unsigned NOT NULL default '0',
		`pids1_end` int(10) unsigned NOT NULL default '0',
		`pids2` int(10) unsigned NOT NULL default '0',
		`pids2_end` int(10) unsigned NOT NULL default '0',
		PRIMARY KEY  (`id`),
		KEY `timestamp` (`timestamp`),
		KEY `mapid` (`mapid`)
	) TYPE=MyISAM;");

#
# Table structure for table `server`
#

$sqlschema[] = array('Servers Table',	
"CREATE TABLE `servers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`ip` varchar(15) NOT NULL default '',
	`prefix` varchar(30) NOT NULL default '',
	`name` varchar(100) default NULL,
	`port` int(6) unsigned default '0',
	`queryport` int(6) unsigned NOT NULL default '0',
	`lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `ip-prefix-unq` (`ip`,`prefix`)
) TYPE=MyISAM;");

#
# Table structure for table `unlocks`
#

$sqlschema[] = array('Unlocks Table',	
"CREATE TABLE `unlocks` (
  `id` int(8) unsigned NOT NULL default '0',
  `kit` smallint(3) unsigned NOT NULL default '0',
  `state` char(1) NOT NULL default 'n',
  PRIMARY KEY  (`id`,`kit`)
) TYPE=MyISAM;");

#
# Table structure for table `vehicles`
#

$sqlschema[] = array('Vehicles Table',	
"CREATE TABLE `vehicles` (
  `id` int(8) unsigned NOT NULL default '0',
  `time0` int(10) unsigned NOT NULL default '0',
  `time1` int(10) unsigned NOT NULL default '0',
  `time2` int(10) unsigned NOT NULL default '0',
  `time3` int(10) unsigned NOT NULL default '0',
  `time4` int(10) unsigned NOT NULL default '0',
  `time5` int(10) unsigned NOT NULL default '0',
  `time6` int(10) unsigned NOT NULL default '0',
  `timepara` int(10) unsigned NOT NULL default '0',
  `kills0` int(10) unsigned NOT NULL default '0',
  `kills1` int(10) unsigned NOT NULL default '0',
  `kills2` int(10) unsigned NOT NULL default '0',
  `kills3` int(10) unsigned NOT NULL default '0',
  `kills4` int(10) unsigned NOT NULL default '0',
  `kills5` int(10) unsigned NOT NULL default '0',
  `kills6` int(10) unsigned NOT NULL default '0',
  `deaths0` int(10) unsigned NOT NULL default '0',
  `deaths1` int(10) unsigned NOT NULL default '0',
  `deaths2` int(10) unsigned NOT NULL default '0',
  `deaths3` int(10) unsigned NOT NULL default '0',
  `deaths4` int(10) unsigned NOT NULL default '0',
  `deaths5` int(10) unsigned NOT NULL default '0',
  `deaths6` int(10) unsigned NOT NULL default '0',
  `rk0` int(10) unsigned NOT NULL default '0',
  `rk1` int(10) unsigned NOT NULL default '0',
  `rk2` int(10) unsigned NOT NULL default '0',
  `rk3` int(10) unsigned NOT NULL default '0',
  `rk4` int(10) unsigned NOT NULL default '0',
  `rk5` int(10) unsigned NOT NULL default '0',
  `rk6` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");

#
# Table structure for table `weapons`
#

$sqlschema[] = array('Weapons Table',	
"CREATE TABLE `weapons` (
  `id` int(8) unsigned NOT NULL default '0',
  `time0` int(10) unsigned NOT NULL default '0',
  `time1` int(10) unsigned NOT NULL default '0',
  `time2` int(10) unsigned NOT NULL default '0',
  `time3` int(10) unsigned NOT NULL default '0',
  `time4` int(10) unsigned NOT NULL default '0',
  `time5` int(10) unsigned NOT NULL default '0',
  `time6` int(10) unsigned NOT NULL default '0',
  `time7` int(10) unsigned NOT NULL default '0',
  `time8` int(10) unsigned NOT NULL default '0',
  `knifetime` int(10) unsigned NOT NULL default '0',
  `c4time` int(10) unsigned NOT NULL default '0',
  `handgrenadetime` int(10) unsigned NOT NULL default '0',
  `claymoretime` int(10) unsigned NOT NULL default '0',
  `shockpadtime` int(10) unsigned NOT NULL default '0',
  `atminetime` int(10) unsigned NOT NULL default '0',
  `tacticaltime` int(10) unsigned NOT NULL default '0',
  `grapplinghooktime` int(10) unsigned NOT NULL default '0',
  `ziplinetime` int(10) unsigned NOT NULL default '0',
  `kills0` int(10) unsigned NOT NULL default '0',
  `kills1` int(10) unsigned NOT NULL default '0',
  `kills2` int(10) unsigned NOT NULL default '0',
  `kills3` int(10) unsigned NOT NULL default '0',
  `kills4` int(10) unsigned NOT NULL default '0',
  `kills5` int(10) unsigned NOT NULL default '0',
  `kills6` int(10) unsigned NOT NULL default '0',
  `kills7` int(10) unsigned NOT NULL default '0',
  `kills8` int(10) unsigned NOT NULL default '0',
  `knifekills` int(10) unsigned NOT NULL default '0',
  `c4kills` int(10) unsigned NOT NULL default '0',
  `handgrenadekills` int(10) unsigned NOT NULL default '0',
  `claymorekills` int(10) unsigned NOT NULL default '0',
  `shockpadkills` int(10) unsigned NOT NULL default '0',
  `atminekills` int(10) unsigned NOT NULL default '0',
  `deaths0` int(10) unsigned NOT NULL default '0',
  `deaths1` int(10) unsigned NOT NULL default '0',
  `deaths2` int(10) unsigned NOT NULL default '0',
  `deaths3` int(10) unsigned NOT NULL default '0',
  `deaths4` int(10) unsigned NOT NULL default '0',
  `deaths5` int(10) unsigned NOT NULL default '0',
  `deaths6` int(10) unsigned NOT NULL default '0',
  `deaths7` int(10) unsigned NOT NULL default '0',
  `deaths8` int(10) unsigned NOT NULL default '0',
  `knifedeaths` int(10) unsigned NOT NULL default '0',
  `c4deaths` int(10) unsigned NOT NULL default '0',
  `handgrenadedeaths` int(10) unsigned NOT NULL default '0',
  `claymoredeaths` int(10) unsigned NOT NULL default '0',
  `shockpaddeaths` int(10) unsigned NOT NULL default '0',
  `atminedeaths` int(10) unsigned NOT NULL default '0',
  `ziplinedeaths` int(10) unsigned NOT NULL default '0',
  `grapplinghookdeaths` int(10) unsigned NOT NULL default '0',
  `tacticaldeployed` int(10) unsigned NOT NULL default '0',
  `grapplinghookdeployed` int(10) unsigned NOT NULL default '0',
  `ziplinedeployed` int(10) unsigned NOT NULL default '0',
  `fired0` int(10) unsigned NOT NULL default '0',
  `fired1` int(10) unsigned NOT NULL default '0',
  `fired2` int(10) unsigned NOT NULL default '0',
  `fired3` int(10) unsigned NOT NULL default '0',
  `fired4` int(10) unsigned NOT NULL default '0',
  `fired5` int(10) unsigned NOT NULL default '0',
  `fired6` int(10) unsigned NOT NULL default '0',
  `fired7` int(10) unsigned NOT NULL default '0',
  `fired8` int(10) unsigned NOT NULL default '0',
  `knifefired` int(10) unsigned NOT NULL default '0',
  `c4fired` int(10) unsigned NOT NULL default '0',
  `claymorefired` int(10) unsigned NOT NULL default '0',
  `handgrenadefired` int(10) unsigned NOT NULL default '0',
  `shockpadfired` int(10) unsigned NOT NULL default '0',
  `atminefired` int(10) unsigned NOT NULL default '0',
  `hit0` int(10) unsigned NOT NULL default '0',
  `hit1` int(10) unsigned NOT NULL default '0',
  `hit2` int(10) unsigned NOT NULL default '0',
  `hit3` int(10) unsigned NOT NULL default '0',
  `hit4` int(10) unsigned NOT NULL default '0',
  `hit5` int(10) unsigned NOT NULL default '0',
  `hit6` int(10) unsigned NOT NULL default '0',
  `hit7` int(10) unsigned NOT NULL default '0',
  `hit8` int(10) unsigned NOT NULL default '0',
  `knifehit` int(10) unsigned NOT NULL default '0',
  `c4hit` int(10) unsigned NOT NULL default '0',
  `claymorehit` int(10) unsigned NOT NULL default '0',
  `handgrenadehit` int(10) unsigned NOT NULL default '0',
  `shockpadhit` int(10) unsigned NOT NULL default '0',
  `atminehit` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");

?>
