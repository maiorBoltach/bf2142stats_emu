<?php

// No Direct Access
defined( '_BF2142_ADMIN' ) or die( 'Restricted access' );

// Check DB Version
if ($cfg->get('db_expected_ver') == $dbver) {
	$dbver_txt = "Upgrade DB";
} else {
	$dbver_txt = "<font color=\"red\"><b>Upgrade DB</b></font>";
}

if (!checkSession()) {
?>
	<div class="main-menu">
		<b>Security</b></div>
	<ul class="sub-menu">
		<li><a href="?task=login">Login</a></li>
	</ul>
<?php
} elseif ($cfg->get('db_expected_ver') != $dbver) {
?>
	<div class="main-menu">
		<b>Database Manager</b></div>
	<ul class="sub-menu"><?php
		if ($dbver == '0.0.0') {?>
		<li><a href="?task=installdb"><font color=\"red\"><b>Install DB</b></font></a></li>
		<li><a href="?task=upgradedb">Upgrade DB</a></li><?php
		} else {?>
		<li><a href="?task=installdb">Install DB</a></li>
		<li><a href="?task=upgradedb"><font color=\"red\"><b>Upgrade DB</b></font></a></li><?php
		}?>
	</ul>
	<div class="main-menu">
		<b>Security</b></div>
	<ul class="sub-menu">
		<li><a href="?action=logout">Logout</a></li>
	</ul><?php
} else {
?>
	<div class="main-menu">
		<b>Configuration</b></div>
	<ul class="sub-menu">
		<li><a href="?task=editconfig">Edit Config</a></li>
		<li><a href="?task=testconfig">Test Config</a></li>
	</ul>
	<div class="main-menu">
		<b>Database Manager</b></div>
	<ul class="sub-menu"><?php
	if ($cfg->get('db_expected_ver') == $dbver) {?>
		<li><a href="?task=installdb">Install DB</a></li>
		<li><a href="?task=upgradedb">Upgrade DB</a></li><?php
	} elseif ($dbver == '0.0.0') {?>
		<li><a href="?task=installdb"><font color=\"red\"><b>Install DB</b></font></a></li>
		<li><a href="?task=upgradedb">Upgrade DB</a></li><?php
	} else {?>
		<li><a href="?task=installdb">Install DB</a></li>
		<li><a href="?task=upgradedb"><font color=\"red\"><b>Upgrade DB</b></font></a></li><?php
	}?>
		<li><a href="?task=cleardb">Clear DB</a></li>
		<li><a href="?task=backupdb">Backup DB</a></li>
		<li><a href="?task=restoredb">Restore DB</a></li>
	</ul>
	<div class="main-menu">
		<b>Player Manager</b></div>
	<ul class="sub-menu">
		<li><a href="?task=importplayers">Import Players</a></li>
		<li><a href="?task=banplayers">Ban Players</a></li>
		<li><a href="?task=unbanplayers">UnBan Players</a></li>
		<li><a href="?task=resetunlocks">Reset Unlocks</a></li>
		<li><a href="?task=deleteplayers">Delete Players</a></li>
		<li><a href="?task=mergeplayers">Merge Players</a></li>
	</ul>
	<div class="main-menu">
		<b>Clan Manager</b></div>
	<ul class="sub-menu">
		<li><a href="?task=clanman_white">Whitelists</a></li>
		<li><a href="?task=clanman_grey">Greylists</a></li>
	</ul>
	<div class="main-menu">
		<b>System Admin</b></div>
	<ul class="sub-menu">
		<li><a href="?task=serverinfo">Server Info</a></li>
		<li><a href="?task=mapinfo">Map Info</a></li>
		<li><a href="?task=validateranks">Validate Ranks</a></li>
		<li><a href="?task=checkawards">Check Awards</a></li>
		<li><a href="?task=importlogs">Import Logs</a></li>
	</ul>
	<div class="main-menu">
		<b>Security</b></div>
	<ul class="sub-menu">
		<li><a href="?action=logout">Logout</a></li>
	</ul>
<?php
}
?>