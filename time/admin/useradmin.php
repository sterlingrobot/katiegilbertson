<?php

session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Job Summary</title>\n";

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
include 'admin_menu.php';
$user_count = mysqli_query($db, "select jobname from ".$db_prefix."jobs
                           order by jobname");
@$user_count_rows = mysqli_num_rows($user_count);

$admin_count = mysqli_query($db, "select jobname from ".$db_prefix."jobs where admin = '1'");
@$admin_count_rows = mysqli_num_rows($admin_count);

$time_admin_count = mysqli_query($db, "select jobname from ".$db_prefix."jobs where time_admin = '1'");
@$time_admin_count_rows = mysqli_num_rows($time_admin_count);

$reports_count = mysqli_query($db, "select jobname from ".$db_prefix."jobs where reports = '1'");
@$reports_count_rows = mysqli_num_rows($reports_count);

echo "    <td align=left class=right_main scope=col>\n";
echo "      <div class='col-md-10'>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <table class='table table-bordered'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>Job Summary</th></tr>\n";
echo "              <tr><td height=40 class=table_rows nowrap halign=left><img src='../images/icons/user_green.png' />&nbsp;&nbsp;Total
                      Jobs: $user_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_orange.png' />&nbsp;&nbsp;
                      Sys Admin Jobs: $admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_red.png' />&nbsp;&nbsp;
                      Time Admin Jobs: $time_admin_count_rows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../images/icons/user_suit.png' />&nbsp;
                      &nbsp;Reports Jobs: $reports_count_rows</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table table-bordered'>\n";
echo "              <tr>\n";
echo "                <th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=13% align=left>Jobname</th>\n";
echo "                <th class=table_heading nowrap width=18% align=left>Display Name</th>\n";
//echo "                <th class=table_heading nowrap width=23% align=left>Email Address</th>\n";
echo "                <th class=table_heading nowrap width=10% align=left>Dept</th>\n";
echo "                <th class=table_heading nowrap width=10% align=left>Group</th>\n";
echo "                <th class=table_heading width=3% align=center>Disabled</th>\n";
echo "                <th class=table_heading width=3% align=center>Sys Admin</th>\n";
echo "                <th class=table_heading width=3% align=center>Time Admin</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Reports</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading width=3% align=center>Chg Pwd</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
          order by jobname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$jobname = stripslashes("".$row['jobname']."");
$displayname = stripslashes("".$row['displayname']."");

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td nowrap class=table_rows width=3%>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a title=\"Edit Job: $jobname\" class=footer_links
                    href=\"useredit.php?username=$jobname&officename=".$row["office"]."\">$jobname</a></td>\n";
echo "                <td>&nbsp;$displayname</td>\n";
//echo "                <td>&nbsp;".$row["email"]."</td>\n";
echo "                <td>&nbsp;".$row['office']."</td>\n";
echo "                <td nowrap>&nbsp;".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td class='text-center'><img src='../images/icons/cross.png' /></td>\n";
} else {
  $disabled = "";
  echo "                <td class='text-center'>".$disabled."</td>\n";
}
if ("".$row["admin"]."" == 1) {
  echo "                <td class='text-center'><img src='../images/icons/accept.png' /></td>\n";
} else {
  $admin = "";
  echo "                <td class='text-center'>".$admin."</td>\n";
}
if ("".$row["time_admin"]."" == 1) {
  echo "                <td class='text-center'><img src='../images/icons/accept.png' /></td>\n";
} else {
  $time_admin = "";
  echo "                <td class='text-center'>".$time_admin."</td>\n";
}
if ("".$row["reports"]."" == 1) {
  echo "                <td class='text-center'><img src='../images/icons/accept.png' /></td>\n";
} else {
  $reports = "";
  echo "                <td class='text-center'>".$reports."</td>\n";
}

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Edit Job: $jobname\"
                    href=\"useredit.php?username=$jobname&officename=".$row["office"]."\">Edit</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Change Password: $jobname\"
                    href=\"chngpasswd.php?username=$jobname&officename=".$row["office"]."\">Chg Pwd</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Delete Job: $jobname\"
                    href=\"userdelete.php?username=$jobname&officename=".$row["office"]."\">Delete</a></td></tr>\n";

} else {

echo "                <td class='text-center'><a title=\"Edit Job: $jobname\"
                    href=\"useredit.php?username=$jobname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
echo "                <td class='text-center'><a title=\"Change Password: $jobname\"
                    href=\"chngpasswd.php?username=$jobname&officename=".$row["office"]."\"><img border=0
                    src='../images/icons/lock_edit.png' /></a></td>\n";
echo "                <td class='text-center'><a title=\"Delete Job: $jobname\"
                    href=\"userdelete.php?username=$jobname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
}
}
echo "          </table></div></td></tr>\n";
include '../footer.php';
exit;
?>
