<?php
$timestamp = time();
$r = $_REQUEST;
$str = "";
foreach ($r as $id => $val) {
    $str.=$id . "=" . $val . "&";
}
file_put_contents('GetRankInfo.txt', $str);