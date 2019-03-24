<?php

// if(isset($_REQUEST['proxy'])) {
//   $context = array(
//       'http' => array(
//           'proxy' => strlen($_REQUEST['proxy']) ? $_REQUEST['proxy'] : 'www-proxy.us.oracle.com:80',
//           'request_fulluri' => true
//       )
//   );
//   stream_context_set_default($context);
//   ini_set('allow_url_include', 1);

//   $dbexists = 1;
// }

session_start();

include 'config.inc.php';
include 'header.php';

echo "<title>$title</title>\n";
echo "</head>";
echo "<body>";


$current_page = "timeclock.php";

if (!isset($_GET['printer_friendly'])) {

    if (isset($_SESSION['valid_user'])) {
        $set_logout = "1";
    }
	include 'topmain.php';
	echo "<div id='wrapper' class='row'>\n"; // -- MAIN CONTAINER WRAPPER
	echo "<div class='col-md-5'>"; // -- START LEFT COLUMN CONTAINER
	echo "<div id='contentLoading' style='display: block; position: absolute; z-index: 100; width: 100%; height: 100%; color: #FFF; background: rgba(0,0,0,0.5); text-align:center; font: 2em Lora;'><img src='images/loader.gif' width='33' height='33' style='margin-top: 200px;'/>&nbsp;&nbsp;Please wait...</div>";
    include 'leftmain.php';
	echo "</div>"; // -- END LEFT COLUMN CONTAINER
}

// code to allow sorting by Name, In/Out, Date, Notes //

if ($show_display_name == "yes") {
    if (!isset($_GET['sortcolumn'])) {
        $sortcolumn = "displayname";
    } else {
        $sortcolumn = $_GET['sortcolumn'];
    }

} else {

    if (!isset($_GET['sortcolumn'])) {
        $sortcolumn = "fullname";
    } else {
        $sortcolumn = $_GET['sortcolumn'];
    }

}

if (!isset($_GET['sortdirection'])) {
    $sortdirection = "asc";
} else {
    $sortdirection = $_GET['sortdirection'];
}

if ($sortdirection == "asc") {
    $sortnewdirection = "desc";
} else {
    $sortnewdirection = "asc";
}

// determine what users, office, and/or group will be displayed on main page //

if (($display_current_users == "yes") && ($display_office == "all") && ($display_group == "all")) {
    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ((".$db_prefix."info.timestamp < '".$a."') and
              (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."jobs.disabled <> '1' and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "yes") && ($display_office != "all") && ($display_group == "all")) {

    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.office = '".$display_office."'
              and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."'))
              and ".$db_prefix."jobs.disabled <> '1' and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "yes") && ($display_office == "all") && ($display_group != "all")) {

    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.groups = '".$display_group."'
              and ((".$db_prefix."info.timestamp < '".$a."') and (".$db_prefix."info.timestamp >= '".$b."'))
              and ".$db_prefix."jobs.disabled <> '1' and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "yes") && ($display_office != "all") && ($display_group != "all")) {

    $current_users_date = strtotime(date($datefmt));
    $calc = 86400;
    $a = $current_users_date + $calc - @$tzo;
    $b = $current_users_date - @$tzo;

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.office = '".$display_office."'
              and ".$db_prefix."jobs.groups = '".$display_group."' and ((".$db_prefix."info.timestamp < '".$a."')
              and (".$db_prefix."info.timestamp >= '".$b."')) and ".$db_prefix."jobs.disabled <> '1'
              and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "no") && ($display_office == "all") && ($display_group == "all")) {

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.disabled <> '1'
              and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "no") && ($display_office != "all") && ($display_group == "all")) {

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.office = '".$display_office."'
              and ".$db_prefix."jobs.disabled <> '1' and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "no") && ($display_office == "all") && ($display_group != "all")) {

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.groups = '".$display_group."'
              and ".$db_prefix."jobs.disabled <> '1' and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

elseif (($display_current_users == "no") && ($display_office != "all") && ($display_group != "all")) {

    $query = "select ".$db_prefix."info.*, ".$db_prefix."jobs.*, ".$db_prefix."punchlist.*
              from ".$db_prefix."info, ".$db_prefix."jobs, ".$db_prefix."punchlist
              where ".$db_prefix."info.timestamp = ".$db_prefix."jobs.tstamp and ".$db_prefix."info.fullname = ".$db_prefix."jobs.jobname
              and ".$db_prefix."info.`inout` = ".$db_prefix."punchlist.punchitems and ".$db_prefix."jobs.office = '".$display_office."'
              and ".$db_prefix."jobs.groups = '".$display_group."' and ".$db_prefix."jobs.disabled <> '1'
              and ".$db_prefix."jobs.jobname <> 'admin'
              order by `$sortcolumn` $sortdirection";

}

$result = mysqli_query($db, $query) or die("<h3>MYSQL QUERY ERROR</h3>\n<b>Query:</b> ".$query."\n<br/><br/>\n".mysqli_error($db));

$time = time();
$tclock_hour = gmdate('H',$time);
$tclock_min = gmdate('i',$time);
$tclock_sec = gmdate('s',$time);
$tclock_month = gmdate('m',$time);
$tclock_day = gmdate('d',$time);
$tclock_year = gmdate('Y',$time);
$tclock_stamp = mktime ($tclock_hour, $tclock_min, $tclock_sec, $tclock_month, $tclock_day, $tclock_year);

$tclock_stamp = $tclock_stamp + @$tzo;
$tclock_time = date($timefmt, $tclock_stamp);
$tclock_date = date($datefmt, $tclock_stamp);
$report_name="Current Status Report";

echo "<div class='col-md-7'>"; // -- START RIGHT COLUMN CONTAINER
include 'display.php';
echo "</div>"; // -- END RIGHT COLUMN CONTAINER
echo "</div>"; // -- END MAIN CONTAINER WRAPPER
echo "<div class='clearfix'></div>"; // -- Keep the footer at the bottom always
if (!isset($_GET['printer_friendly'])) {
    include 'footer.php';
}

include 'modals.php';
?>
