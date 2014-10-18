<?php
/*
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=BF2142StatisticsConfig.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=GameLogic.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=ObjectManager.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=PlayerManager.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=Timer.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=TriggerManager.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=__init__.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=constants.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=endofround.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=fragalyzer_log.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=medal_data.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=medals.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=miniclient.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=rank.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=snapshot.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=stats.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=unlocks.py& HTTP/1.0 "404" 169 "-" "-" "-"
62.1.195.3 - - [27/Aug/2007:19:39:15 +0300] GET /rankedcore.php?sid=1001&mod=mods/bf2142&file=Ranked-README.txt& HTTP/1.0 "404" 169 "-" "-" "-"
*/

if(isset($_GET["sid"]) AND $_GET["sid"] != "") {	$sid = $_GET["sid"];	} else {	echo "None: No error.";exit;	}
if(isset($_GET["mod"]) AND $_GET["mod"] != "") {	$mod = $_GET["mod"];	} else {	echo "None: No error.";exit;	}
if(isset($_GET["file"]) AND $_GET["file"] != "") {	$file = $_GET["file"];	} else {	echo "None: No error.";exit;	}

// py/python/bf2/

//echo file_get_contents("py/python/bf2/stats/".$file);
echo file_get_contents("py/python/bf2/".$file);

?>