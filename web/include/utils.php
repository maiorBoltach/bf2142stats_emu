<?php

DEFINE('_CODE_VER', '1.10.1');

/*
  This script checks a remote IP address against a list of authorised hosts/subnets
  Source: http://www.php.net/

  Notes:
  Host address and subnets are supported, use x.x.x.x/y standard notation.
  Addresses without subnet (ie, x.x.x.x) are assumed to be a single HOST
  An address of 0.0.0.0/0 matches ALL HOSTS (ie, disbales check)

  $auth_hosts = array(
  "127.0.0.1",
  "10.0.0.0/8",
  "172.16.0.0/12",
  "192.168.0.0/16"
  );
 */

function isIPInNet($ip, $net, $mask) {
    $lnet = ip2long($net);
    $lip = ip2long($ip);
    $binnet = str_pad(decbin($lnet), 32, 0, STR_PAD_LEFT);
    $firstpart = substr($binnet, 0, $mask);
    $binip = str_pad(decbin($lip), 32, 0, STR_PAD_LEFT);
    $firstip = substr($binip, 0, $mask);

    return(strcmp($firstpart, $firstip) == 0);
}

//This function check if a ip is in an array of nets (ip and mask)
function isIpInNetArray($theip, $thearray) {
    $exit_c = false;

    if (is_array($thearray)) {
        foreach ($thearray as $subnet) {
            if ($subnet == '0.0.0.0' || $subnet == '0.0.0.0/0') { // Match All
                $exit_c = true;
                break;
            }

            if (strpos($subnet, "/") === false) {
                $subnet .= "/32";
            }

            list($net, $mask) = explode("/", $subnet);
            if (isIPInNet($theip, $net, $mask)) {
                $exit_c = true;
                break;
            }
        }
    }
    return($exit_c);
}

//We check each ip in the array and return response
function checkIpAuth($chkhosts) {
    if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != "") {
        $ip_s = $_SERVER['REMOTE_ADDR'];
    }



    if ($ip_s != "" && isIPInNetArray($ip_s, $chkhosts)) {
        return 1; // Authorised HOST IP
    } else {
        return 0; // UnAuthorised HOST IP
    }
}

// Check Private IP
function checkPrivateIp($ip_s) {
    // Define Private IPs
    $privateIPs = array();
    $privateIPs[] = '10.0.0.0/8';
    $privateIPs[] = '127.0.0.0/8';
    $privateIPs[] = '172.16.0.0/12';
    $privateIPs[] = '192.168.0.0/16';

    if ($ip_s != "" AND isIPInNetArray($ip_s, $privateIPs)) {
        return 1; // Private IP
    } else {
        return 0; // Public/Other IP
    }
}

// Quote variable to make safe (SQL Injection protection code)
function quote_smart($value) {
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        $value = mysql_real_escape_string($value);
    }
    return $value;
}

// Get Database Version
function getDbVer() {
    $cfg = new Config();
    $curver = '0.0.0';

    $connection = @mysql_connect($cfg->get('db_host'), $cfg->get('db_user'), $cfg->get('db_pass'));
    if (!$connection) {
        echo 1;
        // DB Server error
    } else {
        $query = "SELECT ver FROM _version WHERE part='database'";
        if (!mysql_select_db($cfg->get('db_name'), $connection)) {
            // DB Error
        } else {
            $result = mysql_query($query);
//			if ($result && mysql_num_rows($result)) {
            $row = mysql_fetch_array($result);
            $curver = $row['ver'];
//			} else {
//				$query = "SHOW TABLES LIKE 'player'";
//				$result = mysql_query($query);
//				if (mysql_num_rows($result)) {
//					$curver = '1.2+';
//				}
//			}
        }
    }
    // Close database connection
    @mysql_close($connection);
    return $curver;
}

function verCmp($ver) {
    $ver_arr = explode(".", $ver);

    $i = 1;
    $result = 0;
    foreach ($ver_arr as $vbit) {
        $result += $vbit * $i;
        $i = $i / 100;
    }
    return $result;
}

// Record Error Log
function ErrorLog($msg, $lvl) {
    $cfg = new Config();

    switch ($lvl) {
        case -1:
            $lvl_txt = 'INFO: ';
            break;
        case 0:
            $lvl_txt = 'SECURITY: ';
            break;
        case 1:
            $lvl_txt = 'ERROR: ';
            break;
        case 2:
            $lvl_txt = 'WARNING: ';
            break;
        default:
            $lvl_txt = 'NOTICE: ';
            break;
    }

    if ($lvl <= $cfg->get('debug_lvl')) {
        $err_msg = date('Y-m-d H:i:s') . " -- " . $lvl_txt . $msg . "\n";
        $file = @fopen($cfg->get('debug_log'), 'a');
        @fwrite($file, $err_msg);
        @fclose($file);
        //echo "<br />----------------------------------------------------------------------------------------------------<br />$err_msg";
    }
}

// Check SQL Results
function checkSQLResult($result, $query) {
    if (!$result) {
        $msg = mysql_errno() . ':' . mysql_error() . ' Query String: ' . $query;
        ErrorLog($msg, 1);
        return 1;
    } else {
        return 0;
    }
}

function get_ext_ip() {

    $url = 'http://this-ip.com/';
    $get = implode("\n", getPageContents($url));
    preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $get, $ip);
    $var_ipaddr = $ip[0];
    return $var_ipaddr;
}

function getPageContents($url) {
    // Try file() first
    /*
      if( function_exists('file') && function_exists('fopen') && ini_get('allow_url_fopen') ) {
      //ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
      ini_set("user_agent","GameSpyHTTP/1.0");
      ini_set("auto_detect_line_endings", true);
      $results = @file($url);
      }
     */
    // either there was no function, or it failed -- try curl
    if (!($results) && (function_exists('curl_exec'))) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        $sip = '127.0.0.1'; //change to external ip
        //$sip = '195.140.177.250';
        curl_setopt($curl_handle, CURLOPT_INTERFACE, $sip);
        //curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
        curl_setopt($curl_handle, CURLOPT_USERAGENT, "GameSpyHTTP/1.0");
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
        $results = curl_exec($curl_handle);
        $err = curl_error($curl_handle);
        if ($err != '') {
            print "getData(): CURL failed: ";
            print "$err";
            return false;
        }
        $results = explode("\n", trim($results));
        curl_close($curl_handle);
    }

    if (!$results) // still nothing, forgetd a'bout it
        return false;

    return $results;
}

function chkPath($path) {
    if (($path{strlen($path) - 1} != "/") && ($path{strlen($path) - 1} != "\\")) {
        return $path . "/";
    } else {
        return $path;
    }
}

// Config Handling class
class Config {

    var $data = array();
    var $configFile = 'include/_ccconfig.php'; //Default Config File

    function Config() {
        $this->Load();

        // Verify Code Version
        if ($this->data['db_expected_ver'] != _CODE_VER) {
            $this->data['db_expected_ver'] = _CODE_VER;
        }
    }

    function Save() {
        $cfg = "<?php\n";
        $cfg .= "/***************************************\n";
        $cfg .= "*  Battlefield 2 Private Stats Config  *\n";
        $cfg .= "****************************************\n";
        $cfg .= "* All comments have been removed from  *\n";
        $cfg .= "* this file. Please use the Web Admin  *\n";
        $cfg .= "* to change values.                    *\n";
        $cfg .= "***************************************/\n";
        foreach ($this->data as $key => $val) {
            if (is_numeric($val)) {
                $cfg .= "\$$key = " . $val . ";\n";
            } elseif ($key == 'admin_hosts' || $key == 'game_hosts' || $key == 'stats_local_pids') {
                if (!is_array($val)) {
                    $val_r = explode("\n", $val);
                } else {
                    $val_r = $val;
                }
                $val_s = "";
                foreach ($val_r as $item) {
                    $val_s .= "'" . trim($item) . "',";
                }
                $cfg .= "\$$key = array(" . substr($val_s, 0, -1) . ");\n";
            } else {
                $cfg .= "\$$key = '" . addslashes($val) . "';\n";
            }
        }
        $cfg .= "?>";

        @copy($this->configFile, $this->configFile . '.bak');
        if (phpversion() < 5) {
            $file = @fopen($this->configFile, 'w');
            if ($file === false) {
                return false;
            } else {
                @fwrite($file, $cfg);
                @fclose($file);
                return true;
            }
        } else {
            if (@file_put_contents($this->configFile, $cfg)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function Load() {
        if (file_exists($this->configFile)) {
            include ( $this->configFile );
            $vars = get_defined_vars();
            foreach ($vars as $key => $val) {
                if ($key != 'this' && $key != 'data') {
                    $this->data[$key] = $val;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    function set($key, $val) {
        $this->data[$key] = $val;
    }

    function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

}

/**
 *
 * @param type $arr
 * @param type $dir true, false
 */
function mySort($arr, $dir) {
    arsort($arr, $dir);
    reset($arr);
    $firstKey = key($arr);
    $m = null;
    preg_match("/.+-(.+)/", $firstKey, $m);
    if (isset($m[1])) {
        return $m[1];
    } else {
        return 0;
    }
}

?>