<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Delete Job</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['valid_user'])) {

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
echo "        <tr class=right_main_text><td align=center>Go back to the <a class='btn btn-default' href='useradmin.php'>Job Summary</a> page to delete users.
            </td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = stripslashes($_GET['username']);
@$get_office = $_GET['officename'];

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";
include 'userinfo.php';
echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"useredit.php?username=$get_user&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Job' />&nbsp;&nbsp;Edit Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"chngpasswd.php?username=$get_user&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Change Password' />&nbsp;&nbsp;Change Password</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"userdelete.php?username=$get_user&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Job' />&nbsp;&nbsp;Delete Job</a></td></tr>\n";
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
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";

$get_user = addslashes($get_user);

$row_count = 0;

$query = "select * from ".$db_prefix."jobs where jobname = '".$get_user."' order by jobname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$username = stripslashes("".$row['jobname']."");
$displayname = stripslashes("".$row['displayname']."");
$user_email = "".$row['email']."";
$office = "".$row['office']."";
$groups = "".$row['groups']."";
$admin = "".$row['admin']."";
$reports = "".$row['reports']."";
$time_admin = "".$row['time_admin']."";
}
mysqli_free_result($result);
$get_user = stripslashes($get_user);

// make sure you cannot delete the last admin user in the system!! //

if (!empty($admin)) {
  $admin_count = mysqli_query($db, "select jobname from ".$db_prefix."jobs where admin = '1'");
  @$admin_count_rows = mysqli_num_rows($admin_count);
  if (@$admin_count_rows == "1") {
    $evil = "1";
  }
}
if (isset($evil)) {
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Cannot delete this user. This user is the last Sys Admin Job in the system. Go back and give another user Sys Admin
                    privileges before attempting to delete this user again.</td></tr>\n";
echo "            </table>\n";
}
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/user_delete.png' />&nbsp;&nbsp;&nbsp;Delete 
                    Job</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$username\">$username</td></tr>\n";
echo "              <tr><td>Display Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='display_name' value=\"$displayname\">$displayname</td></tr>\n";
echo "              <tr><td>Email Address:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='email_addy' value=\"$user_email\">$user_email</td></tr>\n";
echo "              <tr><td>Dept:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='office_name' value=\"$office\">$office</td></tr>\n";
echo "              <tr><td>Group:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='group_name' value=\"$groups\">$groups</td></tr>\n";
echo "              <tr><td>Sys Admin:</td>\n";
if ($admin == "1") {$admin_yes_no = "Yes";} else {$admin_yes_no = "No";}
echo "                  <td><input type='hidden' name='admin_perms' 
                      value='$admin'>$admin_yes_no</td></tr>\n";
echo "              <tr><td>Time Admin:</td>\n";
if ($time_admin == "1") {$time_admin_yes_no = "Yes";} else {$time_admin_yes_no = "No";}
echo "                  <td><input type='hidden' name='time_admin_perms' 
                      value='$time_admin'>$time_admin_yes_no</td></tr>\n";
echo "              <tr><td>Reports:</td>\n";
if ($reports == "1") {$reports_yes_no = "Yes";} else {$reports_yes_no = "No";}
echo "                  <td><input type='hidden' name='reports_perms' 
                      value='$reports'>$reports_yes_no</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
if (isset($evil)) {
echo "            <table class='table'>\n";
} else {
echo "            <table class='table'>\n";
}
echo "              <tr><td><input type='checkbox' name='delete_all_user_data' value='1'></td>
                  <td>Delete all punch-in/out history for this user?</td></tr></table>\n";
if (isset($evil)) {
echo "            <table class='table'>\n";
} else {
echo "            <table class='table'>\n";
}
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Job' 
                    src='../images/buttons/next_button.png'></td><td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png' 
                    border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php'; exit;
}

elseif ($request == 'POST') {

$post_username = stripslashes($_POST['post_username']);
$display_name = stripslashes($_POST['display_name']);
$email_addy = $_POST['email_addy'];
$office_name = $_POST['office_name'];
$group_name = $_POST['group_name'];
$admin_perms = $_POST['admin_perms'];
$reports_perms = $_POST['reports_perms'];
$time_admin_perms = $_POST['time_admin_perms'];
@$delete_data = $_POST['delete_all_user_data'];

$post_username = addslashes($post_username);
$display_name = addslashes($display_name);

// begin post validation //

if (!empty($post_username)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_username = "".$row['jobname']."";
}
if (!isset($tmp_username)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($display_name)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."' and displayname = '".$display_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_display_name = "".$row['displayname']."";
}
if (!isset($tmp_display_name)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($email_addy)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."' and email = '".$email_addy."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_email_addy = "".$row['email']."";
}
if (!isset($tmp_email_addy)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($office_name)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."' and office = '".$office_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_office_name = "".$row['office']."";
}
if (!isset($tmp_office_name)) {echo "Something is fishy here.\n"; exit;}
}

if (!empty($group_name)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."' and groups = '".$group_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_group_name = "".$row['groups']."";
}
if (!isset($tmp_group_name)) {echo "Something is fishy here.\n"; exit;}
}

if (($admin_perms != '0') && ($admin_perms != '1')) {echo "Something is fishy here.\n"; exit;}
if (($reports_perms != '0') && ($reports_perms != '1')) {echo "Something is fishy here.\n"; exit;}
if (($time_admin_perms != '0') && ($time_admin_perms != '1')) {echo "Something is fishy here.\n"; exit;}
if ((isset($delete_data)) && ($delete_data != '1')) {echo "Something is fishy here.\n"; exit;}

// end post validation //

$query2 = "delete from ".$db_prefix."jobs where jobname = ('".$post_username."')";
$result2 = mysqli_query($db, $query2);

if ($delete_data == "1") {
$query3 = "delete from ".$db_prefix."info where fullname = ('".$post_username."')";
$result3 = mysqli_query($db, $query3);
}

$post_username = stripslashes($post_username);
$display_name = stripslashes($display_name);

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";
include 'userinfo.php';
echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Edit Job' />
                &nbsp;&nbsp;Edit Job</td></tr>\n";
echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Change Password' />
                &nbsp;&nbsp;Change Password</td></tr>\n";
echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Delete Job' />
                &nbsp;&nbsp;Delete Job</td></tr>\n";
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
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>&nbsp;Job 
                    deleted successfully.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/user_delete.png' />&nbsp;&nbsp;&nbsp;Delete 
                    Job</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
echo "              <tr><td>Display Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='display_name' value=\"$display_name\">$display_name</td></tr>\n";
echo "              <tr><td>Email Address:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='email_addy' value=\"$email_addy\">$email_addy</td></tr>\n";
echo "              <tr><td>Dept:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='office_name' value=\"$office_name\">$office_name</td></tr>\n";
echo "              <tr><td>Group:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='group_name' value=\"$group_name\">$group_name</td></tr>\n";
echo "              <tr><td>Sys Admin:</td>\n";
if ($admin_perms == "1") {$admin_yes_no = "Yes";} else {$admin_yes_no = "No";}
echo "                  <td><input type='hidden' name='admin_perms' 
                      value='$admin_perms'>$admin_yes_no</td></tr>\n";
echo "              <tr><td>Reports:</td>\n";
if ($time_admin_perms == "1") {$time_admin_yes_no = "Yes";} else {$time_admin_yes_no = "No";}
echo "                  <td><input type='hidden' name='time_admin_perms' 
                      value='$time_admin_perms'>$time_admin_yes_no</td></tr>\n";
echo "              <tr><td>Reports:</td>\n";
if ($reports_perms == "1") {$reports_yes_no = "Yes";} else {$reports_yes_no = "No";}
echo "                  <td><input type='hidden' name='reports_perms' 
                      value='$reports_perms'>$reports_yes_no</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='useradmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php'; exit;
}
?>
