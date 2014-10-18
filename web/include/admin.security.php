<?php

// No Direct Access
defined( '_BF2142_ADMIN' ) or die( 'Restricted access' );

// Start Session
start_session();

function start_session(){
   static $started = false;
   if(!$started){
       session_start();
       $started = true;
   }
}

function checkSession() {
	global $cfg;
	// Check Session Values
	if (!isset($_SESSION['adminAuth'])) {
		return false;
	} elseif (($_SESSION['adminAuth']) != md5($cfg->get('admin_user').$cfg->get('admin_pass'))) {
		return false;
	} elseif ($_SESSION['adminTime'] < time() - (30*60)) {	// Session Older tha n 30 minutes
		return false;
	} else {
		// Update Session Time
		$_SESSION['adminTime'] = time();
		return true;
	}
}

function processLogin() {
	global $cfg;
	// Initialize or retrieve the current values for the login variables
	$loginAttempts = !isset($_POST['loginAttempts']) ? 1 : $_POST['loginAttempts'];
	$formUser = !isset($_POST['formUser']) ? NULL : $_POST['formUser'];
	$formPassword = !isset($_POST['formPassword']) ? NULL : $_POST['formPassword'];
	
	// Check Values
	if(($formUser != $cfg->get('admin_user') ) || ($formPassword != $cfg->get('admin_pass') )) {
		if ($loginAttempts == 0) { /* 3 strikes and they're out */
			$_POST['loginAttempts'] = 1;
			$auth = false;
			return;
		} else {
			if ( $loginAttempts >= 3 ) {
				echo "<blink><p align='center' style=\"font-weight:bold;font-size:170px;color:red;font-family:sans-serif;\">Log In<br>Failed.</p></blink>";		
				exit;
			} else {
				$_POST['loginAttempts'] += 1;
				return;
			}
		}
	} elseif (($formUser == $cfg->get('admin_user') ) && ($formPassword == $cfg->get('admin_pass') )) {	// test for valid username and password
		// Start Session
		start_session();
		$_SESSION['adminAuth'] = md5($cfg->get('admin_user').$cfg->get('admin_pass'));
		$_SESSION['adminTime'] = time();
		$SID = session_id();
		$_POST['task'] = 'home';
	} else {
		$_POST['loginAttempts'] += 1;
		return;
	}
}

function processLogout() {
	if (!checkSession()) {
		return;
	}
	
	// Reset Session Values
	$_SESSION['adminAuth'] = '';
	$_SESSION['adminTime'] = '';
	
	// If session exists, unregister all variables that exist and destroy session
	$exists = false;
	$session_array = explode(";",session_encode());
	for ($x = 0; $x < count($session_array); $x++) {
		$name  = substr($session_array[$x], 0, strpos($session_array[$x],"|")); 
		if (session_is_registered($name)) {
			session_unregister('$name');
			$exists = true;
		}
	}
	
	if ($exists) {
		session_destroy();
	}
}
?>