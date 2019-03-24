<?php
session_start();

include '../config.inc.php';
include 'header_date.php';
include 'topmain.php';
echo "<title>$title - Delete Time</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (($timefmt == "G:i") || ($timefmt == "H:i")) {
  $timefmt_24hr = '1';
  $timefmt_24hr_text = '24 hr format';
  $timefmt_size = '5';
} else {
  $timefmt_24hr = '0';
  $timefmt_24hr_text = '12 hr format = HH:MM AM/PM';
  $timefmt_size = '8';
}

if ((!isset($_SESSION['valid_user'])) && (!isset($_SESSION['time_admin_valid_user']))) {

echo "<table class='table'>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Administration</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text><td align=center>You are not presently logged in, or do not have permission to view this page.</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Click <a class='btn btn-default' href='../login.php'><u>here</u></a> to login.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

if ($request == 'GET') {

if (!isset($_GET['username'])) {

echo "<table class='table'>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class='btn btn-default' href='timeadmin.php'>Add/Edit/Delete Time</a> page to
                delete a time.
            </td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = stripslashes($_GET['username']);

// disabled_acct($get_user);

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";
include 'userinfo.php';
echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usercreate.php'><img src='../images/icons/user_add.png' alt='Create New Job' />&nbsp;&nbsp;Create New Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usersearch.php'><img src='../images/icons/magnifier.png' alt='Job Search' />&nbsp;&nbsp;Job Search</a></td></tr>\n";

echo "        <tr><td>Depts</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officeadmin.php'><img src='../images/icons/brick.png' alt='Dept Summary' />&nbsp;&nbsp;Dept Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officecreate.php'><img src='../images/icons/brick_add.png' alt='Create New Dept' />&nbsp;&nbsp;Create New Dept</a></td></tr>\n";

echo "        <tr><td>Groups</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupadmin.php'><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;Group Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupcreate.php'><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;Create New Group</a></td></tr>\n";

echo "        <tr><td>In/Out Status</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='statusadmin.php'><img src='../images/icons/application.png' alt='Status Summary' />&nbsp;&nbsp;Status Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='statuscreate.php'><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;Create Status</a></td></tr>\n";

echo "        <tr><td>Miscellaneous</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='timeadmin.php'><img src='../images/icons/clock.png' alt='Add/Edit/Delete Time' />&nbsp;&nbsp;Add/Edit/Delete Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timeadd.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Add Time' />&nbsp;&nbsp;Add Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timeedit.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Edit Time' />&nbsp;&nbsp;Edit Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timedelete.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Delete Time' />&nbsp;&nbsp;Delete Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png'
                alt='Edit System Settings' /> &nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";

$get_user = addslashes($get_user);

$query = "select * from ".$db_prefix."jobs where jobname = '".$get_user."' order by jobname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$username = stripslashes("".$row['jobname']."");
$displayname = stripslashes("".$row['displayname']."");
}
mysqli_free_result($result);

$get_user = stripslashes($get_user);

echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Delete Time
                    </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows width=80%
                      style='padding-left:20px;'>
                      <input type='hidden' name='post_username' value=\"$username\">$username</td></tr>\n";
echo "              <tr><td>Display Name:</td><td align=left class=table_rows
                      width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_displayname' value=\"$displayname\">$displayname</td></tr>\n";
echo "              <tr><td>Date: ($tmp_datefmt)</td><td
                      style='color:red;font-family:Tahoma;;padding-left:20px;' width=80% >
                      <input type='text' size='10' maxlength='10' name='post_date' style='color:#27408b'>&nbsp;*&nbsp;&nbsp;
                      <a href=\"#\" onclick=\"form.post_date.value='';cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt');
                      return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
echo "                <input type='hidden' name='timefmt_24hr' value=\"$timefmt_24hr\">\n";
echo "                <input type='hidden' name='timefmt_24hr_text' value=\"$timefmt_24hr_text\">\n";
echo "                <input type='hidden' name='timefmt_size' value=\"$timefmt_size\">\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\"
                 height=200>&nbsp;</div>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}

elseif ($request == 'POST') {

$get_user = stripslashes($_POST['get_user']);
$post_username = stripslashes($_POST['post_username']);
$post_displayname = stripslashes($_POST['post_displayname']);
$post_date = $_POST['post_date'];
@$final_username = $_POST['final_username'];
@$final_inout = $_POST['final_inout'];
@$final_notes = $_POST['final_notes'];
@$final_mysql_timestamp = $_POST['final_mysql_timestamp'];
@$final_num_rows = $_POST['final_num_rows'];
@$final_time = $_POST['final_time'];
@$delete_time_checkbox = $_POST['delete_time_checkbox'];
@$timestamp = $_POST['timestamp'];
@$calc = $_POST['calc'];
$row_count = '0';

$get_user = addslashes($get_user);
$post_username = addslashes($post_username);
$post_displayname = addslashes($post_displayname);

// begin post validation //

if (!empty($get_user)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$get_user."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_get_user = "".$row['jobname']."";
}
if (!isset($tmp_get_user)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($post_username)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_username = "".$row['jobname']."";
}
if (!isset($tmp_username)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($post_displayname)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."' and displayname = '".$post_displayname."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_post_displayname = "".$row['displayname']."";
}
if (!isset($tmp_post_displayname)) {echo "Something is fishy here.\n"; exit;}
}

// end post validation //

$get_user = stripslashes($get_user);
$post_username = stripslashes($post_username);
$post_displayname = stripslashes($post_displayname);

// begin post validation //

if ($get_user != $post_username) {exit;}

// end post validation //

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";
include 'userinfo.php';
echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usercreate.php'><img src='../images/icons/user_add.png' alt='Create New Job' />&nbsp;&nbsp;Create New Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usersearch.php'><img src='../images/icons/magnifier.png' alt='Job Search' />&nbsp;&nbsp;Job Search</a></td></tr>\n";

echo "        <tr><td>Depts</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officeadmin.php'><img src='../images/icons/brick.png' alt='Dept Summary' />&nbsp;&nbsp;Dept Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officecreate.php'><img src='../images/icons/brick_add.png' alt='Create New Dept' />&nbsp;&nbsp;Create New Dept</a></td></tr>\n";

echo "        <tr><td>Groups</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupadmin.php'><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;Group Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupcreate.php'><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;Create New Group</a></td></tr>\n";

echo "        <tr><td>In/Out Status</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='statusadmin.php'><img src='../images/icons/application.png' alt='Status Summary' />&nbsp;&nbsp;Status Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='statuscreate.php'><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;Create Status</a></td></tr>\n";

echo "        <tr><td>Miscellaneous</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='timeadmin.php'><img src='../images/icons/clock.png' alt='Add/Edit/Delete Time' />&nbsp;&nbsp;Add/Edit/Delete Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timeadd.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Add Time' />&nbsp;&nbsp;Add Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timeedit.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Edit Time' />&nbsp;&nbsp;Edit Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"timedelete.php?username=$get_user\"><img src='../images/icons/arrow_right.png' alt='Delete Time' />&nbsp;&nbsp;Delete Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png'
                alt='Edit System Settings' /> &nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";

// begin post validation //

if (empty($post_date)) {
$evil_post = '1';
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A valid Date is required.</td></tr>\n";
echo "            </table>\n";
}
elseif (preg_match("/^([0-9]{1,2})[-,\/,.]([0-9]{1,2})[-,\/,.](([0-9]{2})|([0-9]{4}))$/i", $post_date, $date_regs)) {
if ($calendar_style == "amer") {
if (isset($date_regs)) {$month = $date_regs[1]; $day = $date_regs[2]; $year = $date_regs[3];}
if ($month > 12 || $day > 31) {
$evil_post = '1';
if (!isset($evil_post)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A valid Date is required.</td></tr>\n";
echo "            </table>\n";
}}}

elseif ($calendar_style == "euro") {
if (isset($date_regs)) {$month = $date_regs[2]; $day = $date_regs[1]; $year = $date_regs[3];}
if ($month > 12 || $day > 31) {
$evil_post = '1';
if (!isset($evil_post)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A valid Date is required.</td></tr>\n";
echo "            </table>\n";
}}}}

if (isset($evil_post)) {
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/clock_add.png' />&nbsp;&nbsp;&nbsp;Add Time
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
echo "              <tr><td>Display Name:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
echo "              <tr><td>Date: ($tmp_datefmt)</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text'
                      size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\"
                      onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt');
                      return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\"
                 height=200>&nbsp;</div>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php';
exit;

// end post validation //

} else {

if (isset($_POST['delete_time_checkbox'])) {

echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "              <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                  &nbsp;Time deleted successfully.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Deleted
                  Time for $post_username on $post_date
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "                <tr><td nowrap width=1% style='padding-right:5px;padding-left:5px;' class=column_headings>Deleted</td>\n";
echo "                  <td nowrap width=7% align=left class=column_headings>In/Out</td>\n";
echo "                  <td nowrap style='padding-left:20px;' width=4% align=right class=column_headings>Time</td>\n";
echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

// begin post validation //

if (!is_numeric($final_num_rows)) {exit;}

// end post validation //

$tmp_tmp_username = array();

// determine who the authenticated user is for audit log

if (isset($_SESSION['valid_user'])) {$user = $_SESSION['valid_user'];}
elseif (isset($_SESSION['time_admin_valid_user'])) {$user = $_SESSION['time_admin_valid_user'];}
else {$user = "";}

// configure current time to insert for audit log

$time = time();
$time_hour = gmdate('H',$time);
$time_min = gmdate('i',$time);
$time_sec = gmdate('s',$time);
$time_month = gmdate('m',$time);
$time_day = gmdate('d',$time);
$time_year = gmdate('Y',$time);
$time_tz_stamp = mktime ($time_hour, $time_min, $time_sec, $time_month, $time_day, $time_year);

// this needs to be changed later
$post_why = "";

for ($x=0;$x<$final_num_rows;$x++) {

// begin post validation //

$final_username[$x] = stripslashes($final_username[$x]);
$tmp_username = stripslashes($tmp_username);

$final_username[$x] = stripslashes($final_username[$x]);
if ($final_username[$x] != $tmp_username) {echo "Something is fishy here.\n"; exit;}
//if ((strlen($final_mysql_timestamp[$x]) != "10") || (!is_integer($final_mysql_timestamp[$x]))) {echo "Something is fishy here.\n"; exit;}

$query_sel = "select * from ".$db_prefix."punchlist where punchitems = '".$final_inout[$x]."'";
$result_sel = mysqli_query($db, $query_sel);

while ($row=mysqli_fetch_array($result_sel)) {
  $punchitems = "".$row['punchitems']."";
}
mysqli_free_result($result_sel);
if (!isset($punchitems)) {echo "Something is fishy here.\n"; exit;}

$final_notes[$x] = preg_replace("/[^[:alnum:] \,\.\?-]/","",$final_notes[$x]);
$final_username[$x] = addslashes($final_username[$x]);

$query5 = "select * from ".$db_prefix."info where (fullname = '".$final_username[$x]."') and (timestamp = '".$final_mysql_timestamp[$x]."') and
           (`inout` = '".$final_inout[$x]."') and (notes = '".$final_notes[$x]."')";
$result5 = mysqli_query($db, $query5);
@$tmp_num_rows = mysqli_num_rows($result5);

if ((isset($tmp_num_rows)) && (@$tmp_num_rows != '1')) {echo "Something is fishy here.\n"; exit;}

// end post validation //

$row_color = ($row_count % 2) ? $color1 : $color2;

if (@$delete_time_checkbox[$x] == '1') {

// begin post validation //

$tmp_time[$x] = date("$timefmt", $final_mysql_timestamp[$x] + $tzo);
if ($tmp_time[$x] != $final_time[$x]) {echo "Something is fishy here.\n"; exit;}

// end post validation //

//if (!get_magic_quotes_gpc()) {$final_username[$x] = addslashes($final_username[$x]);}

$query = "select * from ".$db_prefix."jobs where jobname = '".$final_username[$x]."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {
$tmp_jobname_1 = stripslashes("".$row['jobname']."");
$tmp_tstamp_1 = "".$row['tstamp']."";
}

$tmp_tmp_username[$x] = stripslashes($final_username[$x]);

if (($tmp_jobname_1 == $tmp_tmp_username[$x]) && ($tmp_tstamp_1 == $final_mysql_timestamp[$x])) {

$query2 = "select * from ".$db_prefix."info where fullname = '".$final_username[$x]."' order by timestamp desc limit 1,1";
$result2 = mysqli_query($db, $query2);

while ($row2=mysqli_fetch_array($result2)) {
$tmp_jobname_2 = stripslashes("".$row2['fullname']."");
$tmp_jobname_2 = addslashes($tmp_jobname_2);
$tmp_tstamp_2 = "".$row2['timestamp']."";
}

$query3 = "update ".$db_prefix."jobs set jobname = '".$tmp_jobname_2."', tstamp = '".$tmp_tstamp_2."'
           where jobname = '".$tmp_jobname_2."'";
$result3 = mysqli_query($db, $query3);
}

// delete the time from the info table for $post_username

$query4 = "delete from ".$db_prefix."info where fullname = '".$final_username[$x]."' and timestamp = '".$final_mysql_timestamp[$x]."'";
$result4 = mysqli_query($db, $query4);

// add the results to the audit table

if (strtolower($ip_logging) == "yes") {
$query6 = "insert into ".$db_prefix."audit (modified_by_ip, modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values
           ('".$connecting_ip."', '".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '0', '".$post_why."', '".$final_username[$x]."')";
$result6 = mysqli_query($db, $query6);
} else {
$query6 = "insert into ".$db_prefix."audit (modified_by_user, modified_when, modified_from, modified_to, modified_why, user_modified) values
           ('".$user."', '".$time_tz_stamp."', '".$final_mysql_timestamp[$x]."', '0', '".$post_why."', '".$final_username[$x]."')";
$result6 = mysqli_query($db, $query6);
}

echo "              <tr class=display_row height=20>\n";
echo "                <td nowrap bgcolor='$row_color' width=5% align=center><img src='../images/icons/accept.png' /></td>\n";
echo "                <td nowrap bgcolor='$row_color' align=left width=7% style='padding-left:5px;'>$final_inout[$x]</td>\n";
echo "                <td nowrap align=right style='padding-left:20px;' width=4% bgcolor='$row_color'>$final_time[$x]</td>\n";
echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$final_notes[$x]</td>\n";
echo "              </tr>\n";
$row_count++;
}}

echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='timeadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php';
exit;
}

elseif ((!isset($_POST['delete_time_checkbox'])) && (isset($_POST['tmp_var']))) {

// begin post validation //

if ($_POST['tmp_var'] != '1') {echo "Something is fishy here.\n"; exit;}
$tmp_calc = intval($calc);
$tmp_timestamp = intval($timestamp);
if ((strlen($tmp_calc) != "10") || (!is_integer($tmp_calc))) {echo "Something is fishy here.\n"; exit;}
if ((strlen($tmp_timestamp) != "10") || (!is_integer($tmp_timestamp))) {echo "Something is fishy here.\n"; exit;}

// end post validation //

if (get_magic_quotes_gpc()) {$post_username = stripslashes($post_username);}
$post_username = addslashes($post_username);

$query = "select * from ".$db_prefix."info where (fullname = '".$post_username."') and ((timestamp < '".$calc."') and (timestamp >= '".$timestamp."'))
          order by timestamp asc";
$result = mysqli_query($db, $query);

$username = array();
$inout = array();
$notes = array();
$mysql_timestamp = array();

while ($row=mysqli_fetch_array($result)) {

$time_set = '1';
$username[] = "".$row['fullname']."";
$inout[] = "".$row['inout']."";
$notes[] = "".$row['notes']."";
$mysql_timestamp[] = "".$row['timestamp']."";
}
$num_rows = mysqli_num_rows($result);

$post_username = stripslashes($post_username);

echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Please choose a time or times to delete.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Delete
                  Time for $post_username on $post_date</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "                <tr><td nowrap width=1% style='padding-right:5px;padding-left:5px;' class=column_headings>Delete ?</td>\n";
echo "                  <td nowrap width=7% align=left class=column_headings>In/Out</td>\n";
echo "                  <td nowrap style='padding-left:20px;' width=4% align=right class=column_headings>Time</td>\n";
echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

for ($x=0;$x<$num_rows;$x++) {

$row_color = ($row_count % 2) ? $color1 : $color2;
$time[$x] = date("$timefmt", $mysql_timestamp[$x] + $tzo);
$username[$x] = stripslashes($username[$x]);

echo "              <tr class=display_row>\n";
echo "                <td nowrap width=1% style='padding-right:5px;padding-left:0px;' align=center><input type='checkbox' name='delete_time_checkbox[$x]'
                      value='1'></td>\n";
echo "                <td nowrap align=left style='width:7%;padding-left:5px;background-color:$row_color;color:".$row["color"]."'>$inout[$x]</td>\n";
echo "                <td nowrap align=right style='padding-left:20px;' width=4% bgcolor='$row_color'>$time[$x]</td>\n";
echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$notes[$x]</td>\n";
echo "              </tr>\n";
echo "              <input type='hidden' name='final_username[$x]' value=\"$username[$x]\">\n";
echo "              <input type='hidden' name='final_inout[$x]' value=\"$inout[$x]\">\n";
echo "              <input type='hidden' name='final_notes[$x]' value=\"$notes[$x]\">\n";
echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$mysql_timestamp[$x]\">\n";
echo "              <input type='hidden' name='final_time[$x]' value=\"$time[$x]\">\n";
$row_count++;
}
echo "              <tr><td height=15></td></tr>\n";
$tmp_var = '1';
echo "            <input type='hidden' name='tmp_var' value=\"$tmp_var\">\n";
echo "            <input type='hidden' name='post_username' value=\"$post_username\">\n";
echo "            <input type='hidden' name='post_displayname' value=\"$post_displayname\">\n";
echo "            <input type='hidden' name='post_date' value=\"$post_date\">\n";
echo "            <input type='hidden' name='num_rows' value=\"$num_rows\">\n";
echo "            <input type='hidden' name='calc' value=\"$calc\">\n";
echo "            <input type='hidden' name='timestamp' value=\"$timestamp\">\n";
echo "            <input type='hidden' name='get_user' value=\"$get_user\">\n";
echo "            <input type='hidden' name='tmp_var' value=\"$tmp_var\">\n";
echo "            <input type='hidden' name='final_num_rows' value=\"$num_rows\">\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php';
exit;

} else {

// configure timestamp to insert/update //

if ($calendar_style == "euro") {
//  $post_date = "$day/$month/$year";
  @$post_date = "$month/$day/$year";
}
elseif ($calendar_style == "amer") {
  @$post_date = "$month/$day/$year";
}

$row_count = '0';
$timestamp = strtotime($post_date) - @$tzo;
//$calc = $timestamp + 86400 - @$tzo;
$calc = $timestamp + 86400;
$post_username = stripslashes($post_username);
$post_displayname = stripslashes($post_displayname);
$post_username = addslashes($post_username);
$post_displayname = addslashes($post_displayname);

$query = "select * from ".$db_prefix."info where (fullname = '".$post_username."') and ((timestamp < '".$calc."') and (timestamp >= '".$timestamp."'))
          order by timestamp asc";
$result = mysqli_query($db, $query);

$username = array();
$inout = array();
$notes = array();
$mysql_timestamp = array();

while ($row=mysqli_fetch_array($result)) {

$time_set = '1';
$username[] = "".$row['fullname']."";
$inout[] = "".$row['inout']."";
$notes[] = "".$row['notes']."";
$mysql_timestamp[] = "".$row['timestamp']."";
}
$num_rows = mysqli_num_rows($result);
}

$post_username = stripslashes($post_username);
$post_displayname = stripslashes($post_displayname);

if (!isset($time_set)) {
echo "            <form name='form' action='$self' method='post' onsubmit=\"return isDate()\">\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    No time for was found in the system for $post_username on $post_date.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Delete Time
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "                <input type='hidden' name='date_format' value='$js_datefmt'>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
echo "              <tr><td>Display Name:</td><td align=left class=table_rows
                      colspan=2 width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_displayname' value=\"$post_displayname\">$post_displayname</td></tr>\n";
echo "              <tr><td>Date: ($tmp_datefmt)</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text'
                      size='10' maxlength='10' name='post_date' value='$post_date'>&nbsp;*&nbsp;&nbsp;&nbsp;<a href=\"#\"
                      onclick=\"cal.select(document.forms['form'].post_date,'post_date_anchor','$js_datefmt');
                      return false;\" name=\"post_date_anchor\" id=\"post_date_anchor\" style='font-size:11px;color:#27408b;'>Pick Date</a></td><tr>\n";
echo "                <input type='hidden' name='get_user' value=\"$get_user\">\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <div style=\"position:absolute;visibility:hidden;background-color:#ffffff;layer-background-color:#ffffff;\" id=\"mydiv\"
                 height=200>&nbsp;</div>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php';
exit;
}

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/time.png' /></td><td>
                    Please choose a time or times to delete.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";

// configure date to display correctly //

if ($calendar_style == "euro") {
  $post_date = "$day/$month/$year";
}

echo "                <th class=rightside_heading nowrap halign=left colspan=4><img src='../images/icons/clock_delete.png' />&nbsp;&nbsp;&nbsp;Delete
                  Time for $post_username on $post_date</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";

if (isset($time_set)) {
echo "                <tr><td nowrap width=1% style='padding-right:5px;padding-left:5px;' class=column_headings>Delete ?</td>\n";
echo "                  <td nowrap width=7% align=left class=column_headings>In/Out</td>\n";
echo "                  <td nowrap style='padding-left:20px;' width=4% align=right class=column_headings>Time</td>\n";
echo "                  <td style='padding-left:25px;' class=column_headings><u>Notes</u></td></tr>\n";

for ($x=0;$x<$num_rows;$x++) {

$row_color = ($row_count % 2) ? $color1 : $color2;
$time[$x] = date("$timefmt", $mysql_timestamp[$x] + $tzo);
$username[$x] = stripslashes($username[$x]);

echo "              <tr class=display_row>\n";
echo "                <td nowrap width=1% style='padding-right:5px;padding-left:0px;' align=center><input type='checkbox' name='delete_time_checkbox[$x]'
                      value='1'></td>\n";
echo "                <td nowrap align=left style='width:7%;padding-left:5px;background-color:$row_color;color:".$row["color"]."'>$inout[$x]</td>\n";
echo "                <td nowrap align=right style='padding-left:20px;' width=4% bgcolor='$row_color'>$time[$x]</td>\n";
echo "                <td style='padding-left:25px;' bgcolor='$row_color'>$notes[$x]</td>\n";
echo "              </tr>\n";
echo "              <input type='hidden' name='final_username[$x]' value=\"$username[$x]\">\n";
echo "              <input type='hidden' name='final_inout[$x]' value=\"$inout[$x]\">\n";
echo "              <input type='hidden' name='final_notes[$x]' value=\"$notes[$x]\">\n";
echo "              <input type='hidden' name='final_mysql_timestamp[$x]' value=\"$mysql_timestamp[$x]\">\n";
echo "              <input type='hidden' name='final_time[$x]' value=\"$time[$x]\">\n";
$row_count++;
}
echo "              <tr><td height=15></td></tr>\n";
$tmp_var = '1';
echo "            <input type='hidden' name='tmp_var' value=\"$tmp_var\">\n";
echo "            <input type='hidden' name='post_username' value=\"$post_username\">\n";
echo "            <input type='hidden' name='post_displayname' value=\"$post_displayname\">\n";
echo "            <input type='hidden' name='post_date' value=\"$post_date\">\n";
echo "            <input type='hidden' name='num_rows' value=\"$num_rows\">\n";
echo "            <input type='hidden' name='calc' value=\"$calc\">\n";
echo "            <input type='hidden' name='timestamp' value=\"$timestamp\">\n";
echo "            <input type='hidden' name='get_user' value=\"$get_user\">\n";
echo "            <input type='hidden' name='final_num_rows' value=\"$num_rows\">\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Delete Time' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='timeadmin.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}}}
?>
