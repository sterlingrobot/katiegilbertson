<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Add/Edit/Delete Time</title>\n";

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
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% align=left>Add/Edit/Delete Time</th></tr>\n";
echo "            </table>\n";
echo "            <table class='table table-bordered'>\n";
echo "              <tr>\n";
echo "                <th class=table_heading nowrap width=7% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=17% align=left>Jobname</th>\n";
echo "                <th class=table_heading nowrap width=17% align=left>Display Name</th>\n";
echo "                <th class=table_heading nowrap width=17% align=left>Dept</th>\n";
echo "                <th class=table_heading width=33% align=left>Group</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Disabled</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Add</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</td>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select jobname, displayname, email, groups, office, admin, reports, disabled from ".$db_prefix."jobs
          order by jobname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$jobname = stripslashes("".$row['jobname']."");
$displayname = stripslashes("".$row['displayname']."");

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td nowrap class=table_rows width=7%>&nbsp;$row_count</td>\n";
echo "                <td nowrap class=table_rows width=17%>&nbsp;<a title=\"Edit Time For: $jobname\" class=footer_links
                    href=\"timeedit.php?username=$jobname\">$jobname</a></td>\n";
echo "                <td nowrap class=table_rows width=17%>&nbsp;$displayname</td>\n";
echo "                <td nowrap class=table_rows width=17%>&nbsp;".$row['office']."</td>\n";
echo "                <td>&nbsp;".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td class='text-center'><img src='../images/icons/cross.png' /></td>\n";
} else {
  $disabled = "";
  echo "                <td class='text-center'>".$disabled."</td>\n";
}

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td class='text-center'><a style='color:#27408b;text-decoration:underline;'
                    title=\"Add Time For: $jobname\" href=\"timeadd.php?username=$jobname\">Add</a></td>\n";
echo "                <td class='text-center'><a style='color:#27408b;text-decoration:underline;'
                    title=\"Edit Time For: $jobname\" href=\"timeedit.php?username=$jobname\">Edit</a></td>\n";
echo "                <td class='text-center'><a style='color:#27408b;text-decoration:underline;'
                    title=\"Delete Time For: $jobname\" href=\"timedelete.php?username=$jobname\">
                    Delete</a></td></tr>\n";

} else {

echo "                <td class='text-center'><a title=\"Add Time For: $jobname\"
                    href=\"timeadd.php?username=$jobname\">
                    <img border=0 src='../images/icons/clock_add.png' /></a></td>\n";
echo "                <td class='text-center'><a title=\"Edit Time For: $jobname\"
                    href=\"timeedit.php?username=$jobname\">
                    <img border=0 src='../images/icons/clock_edit.png' /></a></td>\n";
echo "                <td class='text-center'><a title=\"Delete Time For: $jobname\"
                    href=\"timedelete.php?username=$jobname\">
                    <img border=0 src='../images/icons/clock_delete.png' /></a></td></tr>\n";
}
}
echo "          </table></td></tr>\n";
include '../footer.php'; exit;
?>
