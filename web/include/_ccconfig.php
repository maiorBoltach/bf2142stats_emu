<?php
/***************************************
*  Battlefield 2 Private Stats Config  *
****************************************
* All comments have been removed from  *
* this file. Please use the Web Admin  *
* to change values.                    *
***************************************/
$db_expected_ver = '1.10.1';
$db_host = 'localhost';
$db_name = 'имя базы';
$db_user = 'root';
$db_pass = 'ваш пароль';


#$admin_user = '';
#$admin_pass = '';
#$admin_hosts = array('62.1.195.3','127.0.0.1','localhost');
$admin_log = '../logs/_admin_event.log';
$admin_backup_path = './_backups/';
$admin_backup_ext = '.php';
$admin_page_size = 25;
$admin_ignore_ai = 1;

$stats_ext = '.txt';
$stats_logs = 'logs/';
$stats_logs_store = 'logs/_processed/';
$stats_move_logs = 1;
$stats_min_game_time = 0;
$stats_min_player_game_time = 0;
$stats_players_min = 1;
$stats_players_max = 256;

$debug_lvl = 4;
$debug_log = '../logs/_stats_errors.log';

/*
$game_hosts = array(
'127.0.0.1',
'localhost'
);
*/
$game_custom_mapid = 700;
$game_unlocks = 0;
?>