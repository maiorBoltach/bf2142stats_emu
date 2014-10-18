<?php 

// No Direct Access
defined( '_BF2142_ADMIN' ) or die( 'Restricted access' );

if (!checkSession()) {
	showLoginForm();
} else {
	// Do Tasks
	$task = $_GET['task'] ? $_GET['task'] : $_POST['task'];

	switch ($task) {
		case "home":
			showMainForm();
			break;
		case "editconfig":
			showConfigForm();
			break;
		case "testconfig":
			showUnderConstruction();
			//showConfigTestForm();
			break;
		case "clanman_white":
			showUnderConstruction();
			//showClanManagerForm();
			break;
		case "clanman_grey":
			showUnderConstruction();
			//showClanManagerForm();
			break;
		case "banplayers":
			showUnderConstruction();
			//showBanPlayersForm();
			break;
		case "unbanplayers":
			showUnderConstruction();
			//showUnBanPlayersForm();
			break;
		case "resetunlocks":
			showUnderConstruction();
			//showResetUnlocksForm();
			break;
		case "mergeplayers":
			showUnderConstruction();
			//showMergePlayersForm();
			break;
		case "deleteplayers":
			showUnderConstruction();
			//showDeletePlayersForm();
			break;
		case "installdb":
			showUnderConstruction();
			//showInstallDBForm();
			break;
		case "upgradedb":
			showUnderConstruction();
			//showUpgradeDBForm();
			break;
		case "cleardb":
			//showUnderConstruction();
			showClearDBForm();
			break;
		case "backupdb":
			showUnderConstruction();
			//showBackupDBForm();
			break;
		case "restoredb":
			showUnderConstruction();
			//showRestoreDBForm();
			break;
		case "serverinfo":
			showServerInfo();
			break;
		case "mapinfo":
			showUnderConstruction();
			//showMapInfo();
			break;
		case "validateranks":
			showUnderConstruction();
			//showValidateRanksForm();
			break;
		case "checkawards":
			showUnderConstruction();
			//showCheckAwardsForm();
			break;
		case "importlogs":
			//showUnderConstruction();
			showImportLogsForm();
			break;
		case "importplayers":
			//showUnderConstruction();
			showImportPlayersForm();
			break;
		default:
			showLoginForm();
			break;
	}
}
	
// **************	
// Functions	
// **************

function showLoginForm() {
	$loginAttempts = !isset($_POST['loginAttempts']) ? 1 : $_POST['loginAttempts'];
?>
	<div class="content-head">
		<div class="desc-title">Login</div>
		<div class="description">
		<i>Description:</i> Welcome to the Battlefield 2 Private Statistics Administration Tool. This tool
		will allow you to manage the operation of your central database and global configuration items.
		<div class="auth-info">Authorisation required, please login.</div>
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" name="adminform">
		<input type="hidden" name="loginAttempts" value="<?php echo $loginAttempts;?>">
		<input type="hidden" name="task" value="<?php echo $task;?>">
		<input type="hidden" name="action" value="login">
		
		
		<table border="0" width="260px" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Login Form</td>
			</tr>
			<tr>
				<td width="70" align="right" valign="middle" class="form-text">Username:</td>
				<td align="center" valign="middle"><input type="text" name="formUser" size="20" tabindex="1" class="inputbox"></td>
			</tr>
			<tr>
				<td width="70" align="right" valign="middle" class="form-text">Password:</td>
				<td align="center" valign="middle" height="28"><input type="password" name="formPassword" size="20" tabindex="2" class="inputbox"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="center"><button name="login" class="button" type="submit">Login</button>&nbsp;&nbsp;
				<button name="reset" class="button" type="reset">Cancel</button></td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showMainForm() {
	$readme = "_readme.txt";
?>
	<div class="content-head">
		<div class="desc-title">Welcome Page</div>
		<div class="description">
		<i>Description:</i> The utility allows you to easily manange your Battlefiled 2 Private Statistics Database server.
		Please be careful when using this tool as many of the functions *CANNOT* be undone without a previous database backup.
		</div>
	</div>
	<div class="readme"><pre><?php
	if(file_exists($readme)) {
		$filename = fopen($readme, 'r');
		$data = fread($filename, filesize($readme));
		fclose($filename);
	} else {
		$data = "File ".$readme." not found";
	}
	echo htmlspecialchars($data);
	?>
	</pre></div>
<?php
}

function showUnderConstruction() {
?>
	<div class="content-head">
		<div class="desc-title">Area Under Construction</div>
		<div class="description">
		<i>Description:</i> This area is under construction. This functionality will be added in a future 
		release.
		</div>
	</div>
	<div class="content" align="center">
		<table border="0">
			<tr>
				<td height="60" style="text-size: 26px;">Under Construction</td>
			</tr>
			<tr>
				<td height="60" style="text-size: 26px;">Under Construction</td>
			</tr>
			<tr>
				<td height="60" style="text-size: 26px;">Under Construction</td>
			</tr>
		</table>
	</div>
<?php
}

function showConfigForm() {
	global $cfg;
	
	$i = 0;
?>
	<div class="content-head">
		<div class="desc-title">Global Configuration</div>
		<div class="description">
		<i>Description:</i> This area allows you to alter the configuration of the Battlefield 2 Private
		Statistics system. This only alters the global settings defined on the "Gamespy" database server.
		To alter in-game configurations, please edit the "python/bf2/BF2142StatisticsConfig.py" file on your
		game server.
		</div>
	</div>
	<div class="content">
		<form method="POST" action="index.php" onSubmit="return configvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="saveconfig">
		<input type="hidden" name="ext_ip" value="<?php echo get_ext_ip(); ?>">
		<input type="hidden" name="cfg__db_expected_ver" value="<?php echo $cfg->get('db_expected_ver'); ?>">
		
		<table border="0" width="95%" style="border: 2px solid #808080;">
			<tr>
				<td colspan="3" class="form-head">Global Configuration</td>
			</tr>
			
			<tr>
				<td colspan="3" align="left" class="form-section-head">Database Config:</td>
			</tr>
			<tr>
				<td width="120" align="right" valign="middle" class="form-text">Server:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="cfg__db_host" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_host'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">MySQL Database Host. Typically LOCALHOST.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Database:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_name" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_name'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Database Name to store stats.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Username:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_user" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_user'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Username with rights to Database.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Password:</td>
				<td align="left" valign="middle">
				<input type="password" name="cfg__db_pass" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_pass'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Password for Database Username.</td>
			</tr>
			
			<tr>
				<td colspan="3" align="left" class="form-section-head">Stats Processing Options:</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">SNAPSHOT Log Extension:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_ext" size="10" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_ext'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Extension for SNAPSHOT logs (Default: '.stats').</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">SNAPSHOT Log Path:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_logs" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_logs'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Path to store SNAPSHOT logs during processing (Include trailing '/').</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Archive SNAPSHOTS:</td>
				<td align="left" valign="middle">
					<select name="cfg__stats_move_logs" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="1"<?php echo ($cfg->get('stats_move_logs')==1)?" selected":""; ?>>Yes</option>
						<option value="0"<?php echo ($cfg->get('stats_move_logs')==0)?" selected":""; ?>>No</option>
					</select>
				</td>
				<td align="left" valign="top" class="form-desc">Archive SNAPSHOTS logs after processing</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">SNAPSHOT Archive Path:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_logs_store" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_logs_store'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Path to archive SNAPSHOT logs after processing (Include trailing '/').</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Min. Game Time (Global):</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_min_game_time" size="5" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_min_game_time'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Minimum game time of total round in SNAPSHOT before processing (Seconds)?</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Min. Game Time (Player):</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_min_player_game_time" size="5" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_min_player_game_time'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Minimum game time for each player in SNAPSHOT before processing (Seconds)?</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Min. Players:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_players_min" size="5" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_players_min'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Minimum players in SNAPSHOT before processing?</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Max. Players:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__stats_players_max" size="5" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('stats_players_max'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Maximum players in SNAPSHOT before stopping processing (used to stop data hole loops)?</td>
			</tr>
			<tr>
				<td colspan="3" align="left" class="form-section-head">Global Game Server Configuration:</td>
			</tr>
			<tr>
				<td align="right" valign="top" class="form-text">Auth Game Servers:</td>
				<td align="left" valign="top">
				<textarea name="cfg__game_hosts" rows="4" cols="16" tabindex="<?php echo $i++; ?>" class="inputbox"><?php echo implode("\n",$cfg->get('game_hosts')); ?></textarea></td>
				<td align="left" valign="top" class="form-desc">Authorised Game Servers. Enter one <a href="http://en.wikipedia.org/wiki/IPv4" target="_blank">IPv4 Address</a>
				per line (Supports CIDR x.x.x.x/y notation).</td>
			</tr>
			<tr>
				<td align="right" valign="top" class="form-text">Custom MapID:</td>
				<td align="left" valign="top">
				<input type="text" name="cfg__game_custom_mapid" size="10" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('game_custom_mapid'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Default Custom MapID. This will be used for the first custom map detetced, all
				others will increment from this value (Default: 700).<br>
				NOTE: All Custom MapID's will be assigned based on the HIGHEST existing MapID.<br>
				WARNING: Set this ONLY once or you may lose access to you custom map data!</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Unlocks Option:</td>
				<td align="left" valign="middle">
					<select name="cfg__game_unlocks" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="0"<?php echo ($cfg->get('game_unlocks')==0)?" selected":""; ?>>Earned</option>
						<option value="1"<?php echo ($cfg->get('game_unlocks')==1)?" selected":""; ?>>All Unlocked</option>
						<option value="-1"<?php echo ($cfg->get('game_unlocks')==-1)?" selected":""; ?>>Disabled</option>
					</select>
				</td>
				<td align="left" valign="top" class="form-desc">Global Unlocks handling</td>
			</tr>
			<tr>
				<td colspan="3" align="left" class="form-section-head">Error Reporting Options:</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Error Log:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__debug_log" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('debug_log'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Location of Error Log File.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Error Level:</td>
				<td align="left" valign="middle">
					<select name="cfg__debug_lvl" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="0"<?php echo ($cfg->get('debug_lvl')==0)?" selected":""; ?>>Security (0)</option>
						<option value="1"<?php echo ($cfg->get('debug_lvl')==1)?" selected":""; ?>>Errors (1)</option>
						<option value="2"<?php echo ($cfg->get('debug_lvl')==2)?" selected":""; ?>>Warning (2)</option>
						<option value="3"<?php echo ($cfg->get('debug_lvl')==3)?" selected":""; ?>>Notice (3)</option>
						<option value="4"<?php echo ($cfg->get('debug_lvl')==4)?" selected":""; ?>>Detailed (4)</option>
					</select>
				</td>
				<td align="left" valign="top" class="form-desc">Error Logging Level (Includes all message above selected option).</td>
			</tr>
			
			<tr>
				<td colspan="3" align="left" class="form-section-head">Admin Config:</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">DB Backup Extension:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__admin_backup_ext" size="10" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('admin_backup_ext'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Extension for Database Backup files (Defualt: .bak).</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">DB Backup Path:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__admin_backup_path" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('admin_backup_path'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Path to store database backup data (Include trailing '/'). This should be an absolute
				path as it is MySQL using it, not PHP (execpt for restores, then PHP needs it).</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Admin User:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__admin_user" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('admin_user'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Username for access to BF2142 Stats Admin System.<br>
				NOTE: You will be forced to re-logon after this has been saved.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Admin Password:</td>
				<td align="left" valign="middle">
				<input type="password" name="cfg__admin_pass" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('admin_pass'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Password for access to BF2142 Stats Admin System.<br>
				NOTE: You will be forced to re-logon after this has been saved.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Auth Admin IP's:</td>
				<td align="left" valign="middle">
				<textarea name="cfg__admin_hosts" rows="4" cols="16" tabindex="<?php echo $i++; ?>" class="inputbox"><?php echo implode("\n",$cfg->get('admin_hosts')); ?></textarea></td>
				<td align="left" valign="top" class="form-desc">Authorised IP Addresses for Admin System (Localhost is ALWAYS enabled).
				Enter one <a href="http://en.wikipedia.org/wiki/IPv4" target="_blank">IPv4 Address</a> per line (Supports CIDR x.x.x.x/y notation).</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Admin Log File:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__admin_log" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('admin_log'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">File to log admin actions. Leave blank to disable.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Admin Page Size:</td>
				<td align="left" valign="middle">
					<select name="cfg__admin_page_size" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="10" <?php echo ($cfg->get('admin_page_size')==10)?" selected":""; ?>>10</option>
						<option value="25" <?php echo ($cfg->get('admin_page_size')==25)?" selected":""; ?>>25</option>
						<option value="50" <?php echo ($cfg->get('admin_page_size')==50)?" selected":""; ?>>50</option>
						<option value="75" <?php echo ($cfg->get('admin_page_size')==75)?" selected":""; ?>>75</option>
						<option value="100" <?php echo ($cfg->get('admin_page_size')==100)?" selected":""; ?>>100</option>
						<option value="150" <?php echo ($cfg->get('admin_page_size')==150)?" selected":""; ?>>150</option>
					</select>
				<td align="left" valign="top" class="form-desc">Number of records to return per page.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Ignore AI Players:</td>
				<td align="left" valign="middle">
					<select name="cfg__admin_ignore_ai" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="0"<?php echo ($cfg->get('admin_ignore_ai')==0)?" selected":""; ?>>No</option>
						<option value="1"<?php echo ($cfg->get('admin_ignore_ai')==1)?" selected":""; ?>>Yes</option>
					</select>
				</td>
				<td align="left" valign="top" class="form-desc">Ignore AI players in player lists?</td>
			</tr>
			
			<tr>
				<td colspan="3" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Update</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
<?php
}

function showConfigTestForm() {
?>
	<div class="content-head">
		<div class="desc-title">Test Configuration</div>
		<div class="description">
		<i>Description:</i> This area will allow you test the setup and configuration of your "Gamespy"
		database server.<br>
		<b>Note:</b> During this test, sample data will be loaded into your database. This will be removed after
		the test.
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="testconfig">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Test Configuration</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This 
				process will perform some basic tests to validate your configuration. It is recommended you 
				take a backup of your database BEFORE proceeding!<br>
				Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showClanManagerForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Clan Manager</div>
		<div class="description">
		<i>Description:</i> You have a private server, with you own private statistics system. 
		You want to open it up to the world and allow others to use it, but you don't want others
		impersonating your Clan. Manage your real Clan Members here! You can support multiple
		Clan's or just one. Simply, enter your desired Clan Tag at the top, then select all your
		members. This Clan Manager allows you to specify who really belongs to your Clan.
		</div>
	</div>
	<?php buildPlayerList($task,""); ?>
	</div>
<?php
}

function showBanPlayersForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Ban Players</div>
		<div class="description">
		<i>Description:</i> This option allows you to ban players from using your game servers.
		</div>
	</div>
<?php
	buildPlayerList($task,"AND `permban` = 0");
}

function showUnBanPlayersForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">UnBan Players</div>
		<div class="description">
		<i>Description:</i> This option allows you to un-ban previously banned players to allow them to use your game servers.
		</div>
	</div>
<?php
	buildPlayerList($task,"AND `permban` = 1");
}

function showResetUnlocksForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Reset Unlocks</div>
		<div class="description">
		<i>Description:</i> This option allows you to reset the selected unlocks of individual players. This will allow players
		to re-select their unlocked weapons.
		</div>
	</div>
<?php
	buildPlayerList($task,"");
}

function showMergePlayersForm() {
	global $cfg;
	
	$i = 0;
	
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	// Build Player List
	$player_list = "";
	$query = "SELECT id, name FROM player WHERE ip <> '127.0.0.1' {$where} ORDER BY name;";
	$result = mysql_query($query) or die(mysql_error());
	if( mysql_num_rows( $result ) )	{
		while( $row = mysql_fetch_array( $result ) ) {
			$player_list .= "<option value='{$row[id]}'>{$row[name]} ({$row[id]})</option>\n";
		}
	}
	// Close database connection
	@mysql_close($connection);
?>
	<div class="content-head">
		<div class="desc-title">Merge Players</div>
		<div class="description">
		<i>Description:</i> The option allows you to merge data from two players in one single player. This would
		generally be used because a player is issued with a new PlayerID and do not wish to lose their existing stats.<br><br>
		<span style="color: red;"><b>WARNING:</b></span> Only the Target player is left the source player is deleted
		as part of this process!
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="mergeplayers">
		<table border="0" width="60%" style="border: 2px solid #808080;">
			<tr>
				<td colspan="3" class="form-head">Select Players</td>
			</tr>
			<tr>
				<td align="center" valign="middle" style="font-size: 20px;font-weight: bold;">Source</td>
				<td align="center" valign="middle" width="15" >&nbsp;</td>
				<td align="center" valign="middle" style="font-size: 20px;font-weight: bold;">Target</td>
			</tr>
			<tr>
				<td align="center" valign="middle">
					<select name="source_pid" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="0" selected>&lt;Source Player&gt;</option>
						<?php echo $player_list; ?>
					</select>
				</td>
				<td align="center" valign="middle" style="font-size: 30px">=&gt;</td>
				<td align="center" valign="middle">
					<select name="target_pid" tabindex="<?php echo $i++; ?>" class="inputbox">
						<option value="0" selected>&lt;Target Player&gt;</option>
						<?php echo $player_list; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showDeletePlayersForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Delete Players</div>
		<div class="description">
		<i>Description:</i> This option will allow you to delete individual player data within the BF2142 Private Stats Database. Please use
		withy caution.<br /><br />
		<i>Note:</i> Please ensure you a have a full backup of your existing database before proceeding!
		</div>
	</div>
<?php
	buildPlayerList($task,"");
}


function showInstallDBForm() {
	global $cfg;
?>
	<div class="content-head">
		<div class="desc-title">Install Database</div>
		<div class="description">
		<i>Description:</i> This option allows you to load the required database tables for your new Battlefield 2 Private
		Statistics system. You only have to do this once. This process will also, update your configuration file with the
		database server details you enter.<br /><br />
		<i>Note:</i> You <b>MUST</b> create the database, prior to running this script!
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="installdb">
		<table border="0" width="500" style="border: 2px solid #808080;">
			<tbody>
			<tr>
				<td colspan="3" class="form-head">Install BF2142 Private Stats Database</td>
			</tr>
			<tr>
				<td width="80" align="right" valign="middle" class="form-text">Server:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="cfg__db_host" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_host'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">MySQL Database Host. Typically LOCALHOST.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Database:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_name" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_name'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Database Name to store stats.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Username:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_user" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_user'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Username with rights to Database.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Password:</td>
				<td align="left" valign="middle">
				<input type="password" name="cfg__db_pass" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_pass'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Password for Database Username.</td>
			</tr>
			<tr>
				<td colspan="3" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This 
				process will DESTROY existing data within your database!!!<br>
				Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="3" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
			</tbody>
		</table>
		</form>
	</div>
<?php
}

function showUpgradeDBForm() {
	global $cfg;
?>
	<div class="content-head">
		<div class="desc-title">Upgrade Database</div>
		<div class="description">
		<i>Description:</i> This option allows you to upgrade your existing "Gamespy" database to operate with new
		version of the Battlefied 2142 Private Statistics system. This option is generally only available when the
		installed database version differs from the expected version.<br /><br />
		<i>Note:</i> Please ensure you a have a full backup of your existing database before proceeding!
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="upgradedb">
		<table border="0" width="500" style="border: 2px solid #808080;">
			<tbody>
			<tr>
				<td colspan="3" class="form-head">Upgrade Database Form</td>
			</tr>
			<tr>
				<td width="80" align="right" valign="middle" class="form-text">Server:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="cfg__db_host" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_host'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">MySQL Database Host. Typically LOCALHOST.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Database:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_name" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_name'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Database Name to store stats.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Username:</td>
				<td align="left" valign="middle">
				<input type="text" name="cfg__db_user" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_user'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Username with rights to Database.</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Password:</td>
				<td align="left" valign="middle">
				<input type="password" name="cfg__db_pass" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="<?php echo $cfg->get('db_pass'); ?>" /></td>
				<td align="left" valign="top" class="form-desc">Password for Database Username.</td>
			</tr>
			<tr>
				<td colspan="3" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This process may corrupt your database!!!<br>
				Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="3" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
			</tbody>
		</table>
		</form>
	</div>
<?php
}

function showClearDBForm() {
?>
	<div class="content-head">
		<div class="desc-title">Clear Database</div>
		<div class="description">
		<i>Description:</i> This option allows you clear your "Gamespy" Database of ALL collected statistics data.
		Please ensure you have a full backup of your database BEFORE proceeding!!<br><br>
		<span style="color: red;"><b>WARNING:</b></span> This will destroy ALL existing statistics!! 
		Use with EXTREME caution!!!
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="cleardb">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Clear Database confimation</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This process will
				delete ALL data within your database!!!<br>Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showBackupDBForm() {
?>
	<div class="content-head">
		<div class="desc-title">Backup Database</div>
		<div class="description">
		<i>Description:</i> This option allows you backup your "Gamespy" Statistics Database tables. This does not backup
		the database schema, just the data. To restore, simply reload the relevant database schema and import the latest backup
		files.<br><br>
		<span style="color: red;"><b>IMPORTANT:</b></span> This does not replace a proper MySQL Backup Job, but it does
		save your data for later recovery.
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="backupdb">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Backup Database confimation</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> Please ensure no data is
				being written to the database as data corruption may occur!!!<br>Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showRestoreDBForm() {
	global $cfg;
	
	// Get Existing Backup List
	$baklist = array();
	$dir = dir($cfg->get('admin_backup_path'));
	while ($file = $dir->read()) {
		if($file != "." && $file != ".." && is_dir($cfg->get('admin_backup_path').$file)) {
			$baklist[] = $file;
		}
	}
	sort($baklist);
	$dir->close();
?>
	<div class="content-head">
		<div class="desc-title">Restore Database</div>
		<div class="description">
		<i>Description:</i> This option allows you restore your "Gamespy" Statistics Database tables from a previous backup.
		This does not restore the database schema, just the data. Before you restore the data, please ensure you have loaded
		the relevant database schema. As part of this process, ALL extisting data will be lost!<br>
		<br>
		<b>NOTE:</b>The restore will FAIL if you try to restore to an incompatible database schema!<br><br>
		<span style="color: red;"><b>WARNING:</b></span> Running this script will CLEAR ALL data from your existing
		database, please ensure you have a proper backup BEFORE proceeding.
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="restoredb">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Restore Database</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="form-text">Backups:</td>
				<td align="left" valign="middle">
					<select name="backupname" tabindex="1" class="inputbox">
						<option value="<none>" selected>&lt;None&gt;</option><?php
						foreach ($baklist as $item) {
							$dateparts = explode("_", $item);
							$displaytext = "Backup: ".strftime("%Y-%m-%d %H:%M", strtotime($dateparts[1]." ".$dateparts[2]));
							echo "<option value=\"{$item}\">{$displaytext}</option>";
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> Running this script
				will CLEAR ALL data from your existing database!!!<br>Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function showServerInfo() {
	global $cfg, $task;
	
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	$query = "SELECT * FROM servers ORDER BY ip ASC;";
	$result = mysql_query($query) or die(mysql_error());
?>
	<div class="content-head">
		<div class="desc-title">Server Info</div>
		<div class="description">
		<i>Description:</i> This option displays this list of servers that have submitted data to your Private
		Stats Database. Current operational status is also available.
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="<?php echo $task;?>">
				
		<table width="90%" border="0" style="border: 2px solid #808080;">
			<tr>
				<td colspan="10" class="form-head">Server List</td>
			</tr>
			<tr>
				<td width="10" class="header">
					<input type="checkbox" name="toggleAllC" onclick="javascript:ToggleAll(this);">
				</td>
				<td width="100" class="header"><b>IP</b></td>
				<td class="header"><b>Name</b></td>
				<td width="60" class="header"><b>Prefix</b></td>
				<td width="60" align="center" class="header"><b>Port</b></td>
				<td width="100" align="center" class="header"><b>Query Port</b></td>
				<td width="100" align="center" class="header"><b>Online</b></td>
			</tr>
			<?php
			$i = 0;
			if (mysql_num_rows($result)) {
				while ($row = mysql_fetch_array($result)) {
					?>
			<tr onmouseover="style.backgroundColor='#D8ECFF';" onmouseout="style.backgroundColor='#EAECEE';" bgcolor='#EAECEE'>
				<td>
					<input type="checkbox" id="item<?php echo $i++; ?>" name="selitems[]" value="<?php echo $row['id']; ?>" onclick="javascript:Toggle(this);">
				</td>
				<td align="left"><?php echo $row['ip']; ?></td>
				<td align="left"><?php echo $row['hostname']; ?></td>
				<td align="center"><?php echo $row['prefix']; ?></td>
				<td align="center"><?php echo $row['gport']; ?></td>
				<td align="center"><?php echo $row['port']; ?></td>
				<td align="center"><?php if($row['updt'] > (time()-300))  echo '<b><font color="green">online</font></b>'; else  echo '<b><font color="red">offline</font></b>'; ?></td>
			</tr><?php
				} ?>
			<tr>
				<td colspan="10" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr><?php
			} else {?>
			<tr>
				<td colspan="10" align="center">No Data Found!</td>
			</tr>
			<?php
			}
			?>
			
		</table>
		
		</form>
	</div>
	<?php
	// Close database connection
	@mysql_close($connection);
}

function showMapInfo() {
	global $cfg, $task;
	
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	$query = "SELECT * FROM mapinfo ORDER BY id ASC;";
	$result = mysql_query($query) or die(mysql_error());
?>
	<div class="content-head">
		<div class="desc-title">Map Info</div>
		<div class="description">
		<i>Description:</i> This option allows you to view the currenlty know maps within the database. This
		includes custom maps. In future you will be able to change the custom map ID numbers.		
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="<?php echo $task;?>">
				
		<table width="90%" border="0" style="border: 2px solid #808080;">
			<tr>
				<td colspan="10" class="form-head">Map List</td>
			</tr>
			<tr>
				<td width="10" class="header">
					<input type="checkbox" name="toggleAllC" onclick="javascript:ToggleAll(this);">
				</td>
				<td width="20" class="header"><b>MapID</b></td>
				<td class="header"><b>Name</b></td>
				<td width="80" align="center" class="header"><b>Score</b></td>
				<td width="80" align="center" class="header"><b>Time</b></td>
				<td width="80" align="center" class="header"><b>Times</b></td>
				<td width="80" align="center" class="header"><b>Kills</b></td>
				<td width="60" align="center" class="header"><b>Deaths</b></td>
				<td width="60" align="center" class="header"><b>Custom</b></td>
			</tr>
			<?php
			$i = 0;
			if (mysql_num_rows($result)) {
				while ($row = mysql_fetch_array($result)) {
					?>
			<tr onmouseover="style.backgroundColor='#D8ECFF';" onmouseout="style.backgroundColor='#EAECEE';" bgcolor='#EAECEE'>
				<td>
					<input type="checkbox" id="item<?php echo $i++; ?>" name="selitems[]" value="<?php echo $row['id']; ?>" onclick="javascript:Toggle(this);">
				</td>
				<td align="left"><?php echo $row['id']; ?></td>
				<td align="left"><?php echo $row['name']; ?></td>
				<td align="center"><?php echo $row['score']; ?></td>
				<td align="center"><?php echo $row['time']; ?></td>
				<td align="center"><?php echo $row['times']; ?></td>
				<td align="center"><?php echo $row['kills']; ?></td>
				<td align="center"><?php echo $row['deaths']; ?></td>
				<td align="center"><?php echo ($row['custom']?"<font color='red'>YES</font>":"<font color='green'>NO</font>"); ?></td>
			</tr><?php
				} ?>
			<tr>
				<td colspan="10" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit" disabled><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr><?php
			} else {?>
			<tr>
				<td colspan="10" align="center">No Data Found!</td>
			</tr>
			<?php
			}
			?>
			
		</table>
		
		</form>
	</div>
	<?php
	// Close database connection
	@mysql_close($connection);
}

function showValidateRanksForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Validate Ranks</div>
		<div class="description">
		<i>Description:</i> This option allows you validate that the selected players have the correct rank. Rank information
		may get out of sync due to a variety of factors.
		</div>
	</div>
<?php
	buildPlayerList($task,"");
}

function showCheckAwardsForm() {
	global $task;
?>
	<div class="content-head">
		<div class="desc-title">Check Backend Awards</div>
		<div class="description">
		<i>Description:</i> This option allows you validate that your backend awards are functioning as designed. Also, if
		you have recently added/changed the criteria's this script will allow you to remove/add awards to existinf players.
		</div>
	</div>
<?php
	buildPlayerList($task,"");
}

function showImportLogsForm() {
?>
	<div class="content-head">
		<div class="desc-title">Import Logs</div>
		<div class="description">
		<i>Description:</i> This option will allow you to re-import existing SNAPSHOT log files. Typically
		this is used for recovering after a database restore or for importing missed SNAPSHOT's due to
		server communication issues. This process will import ALL log files found in your /ASP/logs 
		directory on this web server. Importing LARGE numbers of log files will seriously impact the
		performance of your web server.<br /><br />
		<i>Note:</i> Please ensure you a have a full backup of your existing database before proceeding!
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="importlogs">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Import Logs Confirmation</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This process may corrupt existing data within
				your datatbase!!!<br>
				Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php
}

function buildPlayerList($task, $where) {
	global $cfg;
	
	$connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
	@mysql_select_db($cfg->get('db_name'), $connection);
	
	// Check if AI Ignored
	$ai_ip = ($cfg->get('admin_ignore_ai'))?'127.0.0.1':'';
	
	// Pagination Code
	$limit = $cfg->get('admin_page_size');		// Sets how many results shown per page
    $query_count	= "SELECT count(*) AS cnt FROM player WHERE ip <> '{$ai_ip}'";
    $result_count	= mysql_query($query_count);
	if (mysql_num_rows($result_count)){
		$rowcount = mysql_fetch_array($result_count);
		$totalrows = $rowcount['cnt'];
	}
	if(!isset($_GET['page']) || (!is_numeric($_GET['page']))){
        $page = 1;
	} else {
		$page = $_GET['page'];
    }
    $limitvalue = $page * $limit - ($limit);	// Ex: (2 * 25) - 25 = 25 <- data starts at 25
    
	// Get Data
	$query = "SELECT * FROM player WHERE ip <> '{$ai_ip}' {$where} ORDER BY name LIMIT $limitvalue, $limit;";
	$result = mysql_query($query) or die("Error: " . mysql_error());

?>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="<?php echo $task;?>">
				
		<table width="90%" border="0" style="border: 2px solid #808080;">
			<tr>
				<td colspan="10" class="form-head">Player List</td>
			</tr>
			<tr>
				<td width="10" class="header">
					<input type="checkbox" name="toggleAllC" onclick="javascript:ToggleAll(this);">
				</td>
				<td width="100" class="header"><b>PID</b></td>
				<td class="header"><b>Name</b></td>
				<td width="80" class="header"><b>Clan</b></td>
				<td width="60" align="center" class="header"><b>Rank</b></td>
				<td width="60" align="center" class="header"><b>Score</b></td>
				<td width="80" align="center" class="header"><b>K/D Ratio</b></td>
				<td width="60" align="center" class="header"><b>Kicked</b></td>
				<td width="60" align="center" class="header"><b>Banned</b></td>
				<td width="80" align="center" class="header"><b>Perm Ban</b></td>
			</tr>
			<?php
			$i = 0;
			if (mysql_num_rows($result)) {
				while ($row = mysql_fetch_array($result)) {
					?>
			<tr onmouseover="style.backgroundColor='#D8ECFF';" onmouseout="style.backgroundColor='#EAECEE';" bgcolor='#EAECEE'>
				<td>
					<input type="checkbox" id="item<?php echo $i++; ?>" name="selitems[]" value="<?php echo $row['id']; ?>" onclick="javascript:Toggle(this);">
				</td>
				<td align="left"><?php echo $row['id']; ?></td>
				<td align="left"><?php echo $row['name']; ?></td>
				<td align="left"><?php echo ($row['clantag'])?$row['clantag']:"--"; ?></td>
				<td align="center"><?php echo $row['rank']; ?></td>
				<td align="center"><?php echo $row['score']; ?></td>
				<td align="center"><?php printf('%01.2f',(($row['deaths']==0)?$row['kills']:$row['kills']/$row['deaths'])); ?></td>
				<td align="center"><?php echo $row['kicked']; ?></td>
				<td align="center"><?php echo $row['banned']; ?></td>
				<td align="center"><?php echo ($row['permban']?"<font color='red'>YES</font>":"<font color='green'>NO</font>"); ?></td>
			</tr>
			<tr>
				<td colspan="10" align="right" class="form-pagenav"><?php
				} 
			
				// Display Page Links
				if($page != 1) { 
			        $pageprev = $page-1;
					echo("<a href=\"index.php?task={$task}&page={$pageprev}\">&lt;&lt; PREV</a> ");  
				} else {
					echo("&lt;&lt;PREV ");
				}
				
				$numofpages = $totalrows / $limit; 
				for($j = 1; $j <= $numofpages; $j++){
					if($j == $page){
						echo($j." ");
					} else {
						echo("<a href=\"index.php?task={$task}&page={$j}\">$j</a> "); 
					}
				}
				
				if(($totalrows % $limit) != 0){
		            if($j == $page){
						echo($j." ");
					} else {
						echo("<a href=\"index.php?task={$task}&page={$j}\">$j</a> ");
					}
				}
				
				if(($totalrows - ($limit * $page)) > 0){
					$pagenext   = $page + 1;
			        echo("<a href=\"index.php?task={$task}&page={$pagenext}\">NEXT &gt;&gt;</a>");
				} else {
					echo("NEXT &gt;&gt;"); 
				} 
				
				?>
				</td>
			</tr>
			<tr>
				<td colspan="10" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr><?php
			} else {?>
			<tr>
				<td colspan="10" align="center">No Data Found!</td>
			</tr>
			<?php
			}
			?>
			
		</table>
		
		</form>
	</div>
	<?php
	// Close database connection
	@mysql_close($connection);
}
function showImportPlayersForm() {
?>
	<div class="content-head">
		<div class="desc-title">Import Players</div>
		<div class="description">
		<i>Description:</i> This area will allow you import players from Offical Gamespy db.<br>
		<!--<b>Note:</b> During this test, sample data will be loaded into your database. This will be removed after the test.-->
		</div>
	</div>
	<div class="content" align="center">
		<form method="POST" action="index.php" onsubmit="return confirmvalidation(this);" name="adminform">
		<input type="hidden" name="action" value="process">
		<input type="hidden" name="task" value="importplayers">
		<table border="0" width="400" style="border: 2px solid #808080;">
			<tr>
				<td colspan="2" class="form-head">Import Players</td>
			</tr>
			<tr>
				<td colspan="2" style="font-size:11px;"><span style="color: red;"><b>WARNING:</b></span> This 
				process will perform some basic tests to validate your configuration. It is recommended you 
				take a backup of your database BEFORE proceeding!<br>
				Are you sure you wish to continue?</td>
			</tr>
			<tr>
				<td width="80" align="right" valign="middle" class="form-text">GS Player ID:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="pid" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="83534936" /></td>
			</tr>
			<tr>
				<td width="80" align="right" valign="middle" class="form-text">New Player AccountID:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="superid" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="80000002" /></td>
			</tr>
			<tr>
				<td width="80" align="right" valign="middle" class="form-text">New Player NickName:</td>
				<td width="100" align="left" valign="middle">
				<input type="text" name="nickname" size="20" tabindex="<?php echo $i++; ?>" class="inputbox" value="ViCh1" /></td>
			</tr>
			<tr>
				<td colspan="2" align="right" class="form-text">
					Confirm Process:&nbsp;<input type="checkbox" tabindex="<?php echo $i++; ?>" name="confirm" />&nbsp;&nbsp;
					<button name="process" class="button" type="submit"><b>Process</b></button>&nbsp;&nbsp;
					<button name="reset" class="button" type="reset">Cancel</button>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
<?php	
}
?>