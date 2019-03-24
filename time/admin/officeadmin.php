<?php

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Dept Summary</title>\n";

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

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
echo "              <tr><th class=table_heading_no_color nowrap width=100% valign=top halign=left>Dept Summary</th></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading nowrap width=7% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=79% align=left>Dept Name</th>\n";
echo "                <th class=table_heading nowrap width=4% align=center>Groups</th>\n";
echo "                <th class=table_heading nowrap width=4% align=center>Jobs</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</th></tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."offices order by officename";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$query2 = "select office from ".$db_prefix."jobs where office = '".$row['officename']."'";
$result2 = mysqli_query($db, $query2);
@$user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$row['officeid']."'";
$result3 = mysqli_query($db, $query3);
@$group_cnt = mysqli_num_rows($result3);

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td nowrap class=table_rows width=7%>&nbsp;$row_count</td>\n";
echo "                <td nowrap class=table_rows width=79%>&nbsp;<a class=footer_links title='Edit Dept: ".$row["officename"]."'
                    href=\"officeedit.php?officename=".$row["officename"]."\">".$row["officename"]."</a></td>\n";
echo "                <td>$group_cnt</td>\n";
echo "                <td>$user_cnt</td>\n";

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a style='color:#27408b;text-decoration:underline;' 
                    href=\"officeedit.php?officename=".$row["officename"]."\" title=\"Edit Dept: ".$row["officename"]."\">
                    Edit</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;' 
                    href=\"officedelete.php?officename=".$row["officename"]."\" title=\"Delete Dept: ".$row["officename"]."\">
                    Delete</a></td></tr>\n";
} else {
echo "                <td><a href=\"officeedit.php?officename=".$row["officename"]."\" 
                    title=\"Edit Dept: ".$row["officename"]."\">
                    <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
echo "                <td><a href=\"officedelete.php?officename=".$row["officename"]."\" 
                    title=\"Delete Dept: ".$row["officename"]."\">
                    <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
}
}
echo "            </table></td></tr>\n";
include '../footer.php'; exit;
?>
