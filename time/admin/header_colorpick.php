<html>
<?php

include_once '../functions.php';

// grab the connecting ip address. //

$connecting_ip = get_ipaddress();
if (empty($connecting_ip)) {
    return FALSE;
}

// determine if connecting ip address is allowed to connect to PHP Timeclock //

if ($restrict_ips == "yes") {
  for ($x=0; $x<count($allowed_networks); $x++) {
    $is_allowed = ip_range($allowed_networks[$x], $connecting_ip);
    if (!empty($is_allowed)) {
      $allowed = TRUE;
    }
  }
  if (!isset($allowed)) {
    echo "You are not authorized to view this page."; exit;
  }
}

// check for correct db version //

@ $db = mysqli_connect('p:' . $db_hostname, $db_username, $db_password, $db_name);
if (!$db) {echo "Error: Could not connect to the database. Please try again later."; exit;}

$table = "dbversion";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
@$rows = mysqli_num_rows($result);
if ($rows == "1") {
$dbexists = "1";
} else {
$dbexists = "0";
}

$db_version_result = mysqli_query($db, "select * from ".$db_prefix."dbversion");
while (@$row = mysqli_fetch_array($db_version_result)) {
  @$my_dbversion = "".$row["dbversion"]."";
}

// include css and timezone offset//

if (($use_client_tz == "yes") && ($use_server_tz == "yes")) {

$use_client_tz = '$use_client_tz';
$use_server_tz = '$use_server_tz';
echo "Please reconfigure your config.inc.php file, you cannot have both $use_client_tz AND $use_server_tz set to 'yes'"; exit;}

echo "<head>\n";
if ($use_client_tz == "yes") {
if (!isset($_COOKIE['tzoffset'])) {
include '../tzoffset.php';
echo "<meta http-equiv='refresh' content='0;URL=index.php'>\n";}}
?>

<!--<link rel='stylesheet' type='text/css' media='print' href='../css/print.css' />-->
<link rel='stylesheet' type='text/css' media='screen' href='../css/bootstrap-readable.min.css' />
<script language="javascript" src="../scripts/ColorPicker2.js"></script>
<script language="javascript">var cp = new ColorPicker();</script>
<script language="javascript" src="../scripts/pnguin.js"></script>
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>

</head>
<?php

if ($use_client_tz == "yes") {
if (isset($_COOKIE['tzoffset'])) {
$tzo = $_COOKIE['tzoffset'];
settype($tzo, "integer");
$tzo = $tzo * 60;}
} elseif ($use_server_tz == "yes") {
  $tzo = date('Z');
} else {
  $tzo = "1";}
echo "<body>\n";
?>
