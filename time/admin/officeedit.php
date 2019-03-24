<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Edit Dept</title>\n";

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

if ($request == 'GET') {

if (!isset($_GET['officename'])) {

echo "<table class='table'>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class='btn btn-default' href='officeadmin.php'>Dept Summary</a> page to edit 
            offices.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_office = $_GET['officename'];

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
echo "        <tr><td><a class='btn btn-default' href=\"officeedit.php?officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Dept' />&nbsp;&nbsp;Edit Dept</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"officedelete.php?officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Dept' />&nbsp;&nbsp;Delete Dept</a></td></tr>\n";
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

$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$officename = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}

if (!isset($officename)) {echo "Dept name is not defined.\n"; exit;}
if (!isset($officeid)) {echo "Dept name is not defined.\n"; exit;}

$query2 = "select * from ".$db_prefix."jobs where office = '".$get_office."'";
$result2 = mysqli_query($db, $query2);
@$user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$officeid."'";
$result3 = mysqli_query($db, $query3);
@$group_cnt = mysqli_num_rows($result3);

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_edit.png' />&nbsp;&nbsp;&nbsp;Edit Dept
                -&nbsp;$get_office</th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>New Dept Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='25' maxlength='50' name='post_officename' value=\"$officename\">&nbsp;*</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left width=80%
                      class=table_rows style='padding-left:20px;'><input type='hidden' name='group_cnt' 
                      value=\"$group_cnt\">$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left width=80%
                      class=table_rows style='padding-left:20px;'><input type='hidden' name='user_cnt' 
                      value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Edit Dept' src='../images/buttons/next_button.png'></td>
                  <td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table>";

if ($group_cnt == '0') {
  echo "</form></td></tr>\n";
}

if ($group_cnt != '0') {

echo "</form>\n";
echo "            <br /><br /><br /><hr /><br />\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>$get_office Groups</th></tr>\n";
echo "              <tr><td height=40 class=table_rows nowrap halign=left><img src='../images/icons/group.png' />&nbsp;&nbsp;Total
                      Groups: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <img src='../images/icons/user_green.png' />&nbsp;&nbsp;Total Jobs: $user_cnt</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=87% align=left>Group Name</th>\n";
echo "                <th class=table_heading nowrap width=4% align=center>Jobs</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</th></tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."groups where officeid = ('".$officeid."') order by groupname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$tmp_group = "".$row['groupname']."";

$query3 = "select * from ".$db_prefix."jobs where office = '".$officename."' and groups = '".$tmp_group."'";
$result3 = mysqli_query($db, $query3);
@$group_user_cnt = mysqli_num_rows($result3);

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a class=footer_links title=\"Edit Group: ".$row["groupname"]."\"
                    href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$get_office\">$tmp_group</a></td>\n";
echo "                <td><input type='hidden' name='group_user_cnt' 
                    value=\"$group_user_cnt\">$group_user_cnt</td>\n";

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\" >
                    Edit</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\" >
                    Delete</a></td></tr>\n";
} else {
echo "                <td><a title=\"Edit Group: ".$row["groupname"]."\" 
                    href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\">
                    <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
echo "                <td><a title=\"Delete Group: ".$row["groupname"]."\" 
                    href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\">
                    <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
}
}
echo "            </table></td></tr>\n";
}include '../footer.php'; exit;
}

elseif ($request == 'POST') {

$post_officename = $_POST['post_officename'];
$post_officeid = $_POST['post_officeid'];
$get_office = $_POST['get_office'];
$group_cnt = $_POST['group_cnt'];
$user_cnt = $_POST['user_cnt'];
@$group_user_cnt = $_POST['group_user_cnt'];

// begin post validation //

if (!empty($get_office)) {
$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
}
mysqli_free_result($result);
if (!isset($getoffice)) {echo "Dept is not defined.\n"; exit;}
}

if (!empty($post_officeid)) {
$query = "select * from ".$db_prefix."offices where officeid = '".$post_officeid."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$post_officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
if (!isset($post_officeid)) {echo "Dept id is not defined.\n"; exit;}
}

$query2 = "select office from ".$db_prefix."jobs where office = '".$get_office."'";
$result2 = mysqli_query($db, $query2);
@$tmp_user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$post_officeid."'";
$result3 = mysqli_query($db, $query3);
@$tmp_group_cnt = mysqli_num_rows($result3);

if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this office.\n"; exit;}
if ($group_cnt != $tmp_group_cnt) {echo "Posted group count does not equal actual group count for this office.\n"; exit;}

if ((empty($post_officename)) || (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_officename))) {

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
echo "        <tr><td><a class='btn btn-default' href=\"officeedit.php?officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Dept' />&nbsp;&nbsp;Edit Dept</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"officedelete.php?officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Dept' />&nbsp;&nbsp;Delete Dept</a></td></tr>\n";
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

if (empty($post_officename)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    An Dept Name is required.</td></tr>\n";
echo "            </table>\n";
}
elseif (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_officename)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when creating an Dept Name.</td></tr>\n";
echo "            </table>\n";
}
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_edit.png' />&nbsp;&nbsp;&nbsp;Edit Dept
                -&nbsp;$get_office</th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>New Dept Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px'><input type='text' 
                      size='25' maxlength='50' name='post_officename' value=\"$post_officename\">&nbsp;*</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left width=80% 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='group_cnt' 
                      value=\"$group_cnt\">$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='user_cnt' 
                      value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Edit Dept' src='../images/buttons/next_button.png'></td>
                  <td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table>";

if ($group_cnt == '0') {
  echo "</form></td></tr>\n";
}

if ($group_cnt != '0') {

echo "</form>\n";
echo "            <br /><br /><br /><hr /><br />\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>$get_office Groups</th></tr>\n";
echo "              <tr><td height=40 class=table_rows nowrap halign=left><img src='../images/icons/group.png' />&nbsp;&nbsp;Total
                      Groups: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <img src='../images/icons/user_green.png' />&nbsp;&nbsp;Total Jobs: $user_cnt</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";

echo "                <th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=87% align=left>Group Name</th>\n";
echo "                <th class=table_heading nowrap width=4% align=center>Jobs</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."groups where officeid = ('".$post_officeid."') order by groupname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$tmp_group = "".$row['groupname']."";

$query3 = "select * from ".$db_prefix."jobs where office = '".$get_office."' and groups = '".$tmp_group."'";
$result3 = mysqli_query($db, $query3);
@$group_user_cnt = mysqli_num_rows($result3);

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a class=footer_links
                    href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$get_office\">$tmp_group</a></td>\n";
echo "                <td>$group_user_cnt</td>\n";

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\" >
                    Edit</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\" >
                    Delete</a></td></tr>\n";
} else {
echo "                <td><a href=\"groupedit.php?groupname=$tmp_group&officename=$get_office\">
                    <img border=0 src='../images/icons/application_edit.png' title=\"Edit Group: ".$row["groupname"]."\" /></a></td>\n";
echo "                <td><a href=\"groupdelete.php?groupname=$tmp_group&officename=$get_office\">
                    <img border=0 src='../images/icons/delete.png' title=\"Delete Group: ".$row["groupname"]."\" /></a></td></tr>\n";
}
}
echo "            </table></td></tr>\n";
}
include '../footer.php'; exit;

} else {

///////////////////////////////////////////////////////////////////////////////////////////////

$officeid_query = "select * from ".$db_prefix."offices where officename = ('".$post_officename."')";
$officeid_result = mysqli_query($db, $officeid_query);
while ($row=mysqli_fetch_array($officeid_result)) {
  $post_officeid = "".$row['officeid']."";
}

$query4 = "update ".$db_prefix."jobs set office = ('".$post_officename."') where office = ('".$get_office."')";
$result4 = mysqli_query($db, $query4);

$query5 = "update ".$db_prefix."offices set officename = ('".$post_officename."') where officename = ('".$get_office."')";
$result5 = mysqli_query($db, $query5);

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
echo "        <tr><td><a class='btn btn-default' href=\"officeedit.php?officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Edit Dept' />&nbsp;&nbsp;Edit Dept</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"officedelete.php?officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Delete Dept' />&nbsp;&nbsp;Delete Dept</a></td></tr>\n";
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
echo "                <td><img src='../images/icons/accept.png' /></td>
                <td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_edit.png' />&nbsp;&nbsp;&nbsp;Edit Dept
                -&nbsp;$get_office</th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>New Dept Name:</td><td align=left class=table_rows 
                      colspan=2 width=80% style='padding-left:20px;'>$post_officename</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left class=table_rows 
                      colspan=2 width=80% style='padding-left:20px;'>$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left class=table_rows 
                      colspan=2 width=80% style='padding-left:20px;'>$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='officeadmin.php'><img src='../images/buttons/done_button.png' 
                      border='0'></a></td></tr></table>";

if ($group_cnt == '0') {
  echo "</td></tr>\n";
}

if ($group_cnt != '0') {

echo "\n";
echo "            <br /><br /><br /><hr /><br />\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>$post_officename Groups</th></tr>\n";
echo "              <tr><td height=40 class=table_rows nowrap halign=left><img src='../images/icons/group.png' />&nbsp;&nbsp;Total
                      Groups: $group_cnt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <img src='../images/icons/user_green.png' />&nbsp;&nbsp;Total Jobs: $user_cnt</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";

echo "                <th class=table_heading nowrap width=3% align=left>&nbsp;</th>\n";
echo "                <th class=table_heading nowrap width=87% align=left>Group Name</th>\n";
echo "                <th class=table_heading nowrap width=4% align=center>Jobs</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Edit</th>\n";
echo "                <th class=table_heading nowrap width=3% align=center>Delete</th>\n";
echo "              </tr>\n";

$row_count = 0;

$query = "select * from ".$db_prefix."groups where officeid = ('".$post_officeid."') order by groupname";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$tmp_group = "".$row['groupname']."";

$query3 = "select * from ".$db_prefix."jobs where office = '".$post_officename."' and groups = '".$tmp_group."'";
$result3 = mysqli_query($db, $query3);
@$group_user_cnt = mysqli_num_rows($result3);

$row_count++;
$row_color = ($row_count % 2) ? $color2 : $color1;

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a class=footer_links
                    href=\"groupedit.php?groupname=".$row["groupname"]."&officename=$post_officename\">$tmp_group</a></td>\n";
echo "                <td>$group_user_cnt</td>\n";

if ((strpos($user_agent, "MSIE 6")) || (strpos($user_agent, "MSIE 5")) || (strpos($user_agent, "MSIE 4")) || (strpos($user_agent, "MSIE 3"))) {

echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Edit Group: ".$row["groupname"]."\" href=\"groupedit.php?groupname=$tmp_group&officename=$post_officename\" >
                    Edit</a></td>\n";
echo "                <td><a style='color:#27408b;text-decoration:underline;'
                    title=\"Delete Group: ".$row["groupname"]."\" href=\"groupdelete.php?groupname=$tmp_group&officename=$post_officename\" >
                    Delete</a></td></tr>\n";
} else {
echo "                <td><a href=\"groupedit.php?groupname=$tmp_group&officename=$post_officename\">
                    <img border=0 src='../images/icons/application_edit.png' /></a></td>\n";
echo "                <td><a href=\"groupdelete.php?groupname=$tmp_group&officename=$post_officename\">
                    <img border=0 src='../images/icons/delete.png' /></a></td></tr>\n";
}
}
echo "            </table></td></tr>\n";
}
}
include '../footer.php'; exit;
}
?>
