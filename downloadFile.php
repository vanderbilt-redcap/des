<?php
define('NOAUTH',true);
require_once "base.php";

$code = getCrypt($_REQUEST['code'],"d",$secret_key,$secret_iv);
$exploded = array();
parse_str($code, $exploded);

$filename = $exploded['file'];
$sname = $exploded['sname'];

header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
@readfile(EDOC_PATH.$sname);
?>