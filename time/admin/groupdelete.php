<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php';}
echo "<title>$title - Delete Group</title>\n";

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

if ((!isset($_GET['groupname'])) && (!isset($_GET['officename']))) {

echo "<table class='table'>\n";
echo "  <tr class=right_main_text><td height=10 align=center valign=top scope=row class=title_underline>PHP Timeclock Error!</td></tr>\n";
echo "  <tr class=right_main_text>\n";
echo "    <td align=center valign=top scope=row>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text><td align=center>How did you get here?</td></tr>\n";
echo "        <tr class=right_main_text><td align=center>Go back to the <a class='btn btn-default' href='groupadmin.php'>Group Summary</a> page to edit groups.
            </td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_group = $_GET['groupname'];
$get_office = $_GET['officename'];

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";

// display links in top left of each page //


echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usercreate.php'><img src='../images/icons/user_add.png' alt='Create New Job' />&nbsp;&nbsp;Create New Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usersearch.php'><img src='../images/icons/magnifier.png' alt='Job Search' />&nbsp;&nbsp;Job Search</a></td></tr>\n";

echo "        <tr><td>Depts</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officeadmin.php'><img src='../images/icons/brick.png' alt='Dept Summary' />&nbsp;&nbsp;Dept Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officecreate.php'><img src='../images/icons/brick_add.png' alt='Create New Dept' />&nbsp;&nbsp;Create New Dept</a></td></tr>\n";

echo "        <tr><td>Groups</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupadmin.php'><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;Group Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"groupedit.php?groupname=$get_group&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Group' />&nbsp;&nbsp;Edit Group</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"groupdelete.php?groupname=$get_group&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Group' />&nbsp;&nbsp;Delete Group</a></td></tr>\n";
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

$query = "select * from ".$db_prefix."groups, ".$db_prefix."offices where officename = '".$get_office."' and groupname = '".$get_group."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {

$officename = "".$row['officename']."";
$officeid = "".$row['officeid']."";
$groupname = "".$row['groupname']."";
$groupid = "".$row['groupid']."";
}

if (!isset($officename)) {echo "Dept name is not defined for this group.\n"; exit;}
if (!isset($groupname)) {echo "Group name is not defined for this group.\n"; exit;}

$query2 = "select * from ".$db_prefix."jobs where office = '".$get_office."' and groups = '".$get_group."'";
$result2 = mysqli_query($db, $query2);
@$user_cnt = mysqli_num_rows($result2);

if ($user_cnt > 0) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td>";
  if ($user_cnt == 1) {
echo "<td></tr>\n";
  } else {
echo "<td></tr>\n";
  }
echo "            </table>\n";
echo "            <br />\n";
}
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_delete.png' />&nbsp;&nbsp;&nbsp;Delete Group
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Group Name:</td><td align=left width=80% 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='post_groupname' 
                      value=\"$groupname\">$get_group</td></tr>\n";
echo "              <tr><td>Parent Dept:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows width=66%><input type='hidden' name='post_officename' 
                      value=\"$officename\">$get_office</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='user_cnt' 
                      value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
if ($user_cnt == 0) {
    echo "              <tr><td height=40></td></tr></table>\n";
    echo "              <input type='hidden' name='group_name_no_users'>\n";
    echo "              <input type='hidden' name='office_name_no_users'>\n";
} elseif ($user_cnt == 1) {
echo "              <tr><td>Move this user to which office?&nbsp;&nbsp;&nbsp;\n";
} else {
echo "              <tr><td>Move these users to which office?&nbsp;&nbsp;&nbsp;\n";
}

if ($user_cnt > '0') {
echo "                <select name='office_name' onchange='group_names();'>\n";
echo "                </select>&nbsp;&nbsp;&nbsp;Which Group?\n";
echo "                <select name='group_name' onfocus='group_names();'>
                  <option selected></option>\n";
echo "                </select></td></tr></table>\n";
}

echo "            <table class='table'>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "              <input type='hidden' name='post_groupid' value=\"$groupid\">\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Group' src='../images/buttons/next_button.png'></td>
                  <td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n"; 
                  include '../footer.php'; exit;
}

elseif ($request == 'POST') {

include 'header_post.php';include 'topmain.php';

$post_officename = $_POST['post_officename'];
$post_officeid = $_POST['post_officeid'];
@$group_name = $_POST['group_name'];
@$office_name = $_POST['office_name'];
@$group_name_no_users = $_POST['group_name_no_users'];
@$office_name_no_users = $_POST['office_name_no_users'];
$post_groupname = $_POST['post_groupname'];
$post_groupid = $_POST['post_groupid'];
$user_cnt = $_POST['user_cnt'];

// begin post validation //

if ((!empty($post_officename)) || (!empty($post_officeid)) || ($office_name != 'no_office_users')) {
$query = "select * from ".$db_prefix."offices where officename = '".$post_officename."' and officeid = '".$post_officeid."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$officename = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
}
if ((!isset($officename)) || (!isset($officeid))) {echo "Dept name is not defined for this group.\n"; exit;}

if ((!empty($post_groupname)) || (!empty($post_groupid)) || ($group_name != 'no_group_users')) {
$query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and groupid = '".$post_groupid."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$groupname = "".$row['groupname']."";
$groupid = "".$row['groupid']."";
}
mysqli_free_result($result);
}
if ((!isset($groupname)) || (!isset($groupid))) {echo "Group name is not defined for this group.\n"; exit;}

if (!empty($office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_officename = "".$row['officename']."";
$tmp_officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
if ((!isset($tmp_officename)) || (!isset($tmp_officeid))) {echo "Dept name is not defined for this group.\n"; exit;}
}

if (!empty($group_name)) {
$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_groupname = "".$row['groupname']."";
$tmp_groupid = "".$row['groupid']."";
}
mysqli_free_result($result);
if ((!isset($tmp_groupname)) || (!isset($tmp_groupid))) {echo "Group name is not defined for this group.\n"; exit;}
}

if (isset($office_name_no_users)) {
  if (!empty($office_name_no_users)) {echo "Something is fishy here.\n"; exit;}
}
if (isset($group_name_no_users)) {
  if (!empty($group_name_no_users)) {echo "Something is fishy here.\n"; exit;}
}

$query = "select * from ".$db_prefix."jobs where office = '".$post_officename."' and groups = '".$post_groupname."'";
$result = mysqli_query($db, $query);
@$tmp_user_cnt = mysqli_num_rows($result);

if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this group.\n"; exit;}

// end post validation //

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";

// display links in top left of each page //


echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usercreate.php'><img src='../images/icons/user_add.png' alt='Create New Job' />&nbsp;&nbsp;Create New Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='usersearch.php'><img src='../images/icons/magnifier.png' alt='Job Search' />&nbsp;&nbsp;Job Search</a></td></tr>\n";

echo "        <tr><td>Depts</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officeadmin.php'><img src='../images/icons/brick.png' alt='Dept Summary' />&nbsp;&nbsp;Dept Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='officecreate.php'><img src='../images/icons/brick_add.png' alt='Create New Dept' />&nbsp;&nbsp;Create New Dept</a></td></tr>\n";

echo "        <tr><td>Groups</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='groupadmin.php'><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;Group Summary</a></td></tr>\n";

if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name))) || 
(($group_name == $post_groupname) && ($office_name == $post_officename))) {

echo "        <tr><td><a class='btn btn-default' href=\"groupedit.php?groupname=$post_groupname&officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Edit Group' />&nbsp;&nbsp;Edit Group</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"groupdelete.php?groupname=$post_groupname&officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Delete Group' />&nbsp;&nbsp;Delete Group</a></td>
                </tr>\n";

} else {

echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Edit Group' />&nbsp;&nbsp;
                Edit Group</td></tr>\n";
echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Delete Group' />
                &nbsp;&nbsp;Delete Group</td></tr>\n";
}

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

if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name))) || 
(($group_name == $post_groupname) && ($office_name == $post_officename))) {
echo "                <td><img src='../images/icons/cancel.png' /></td>\n";
} else {
echo "                <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>Group 
                    deleted successfully.</td></tr></table>\n";
}

if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name)))) {
echo "                <td class=table_rows_red>To delete this group, you must choose to move its' current users to another
                      office <b>AND/OR</b> group.</td></tr></table>\n";
} elseif (($group_name == $post_groupname) && ($office_name == $post_officename)) {
echo "                <td>ANOTHER</b>
                      group.</td></tr></table>\n";
}

echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_delete.png' />&nbsp;&nbsp;&nbsp;Delete Group
              </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";

if (((isset($office_name)) && (empty($office_name))) || ((isset($group_name)) && (empty($group_name))) || 
(($group_name == $post_groupname) && ($office_name == $post_officename))) {

echo "              <tr><td>Group Name:</td><td align=left width=80% 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='post_groupname' 
                      value=\"$post_groupname\">$post_groupname</td></tr>\n";
echo "              <tr><td>Parent Dept:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='post_officename' 
                      value=\"$post_officename\">$post_officename</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='user_cnt' 
                      value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

if ($user_cnt == 0) {
    echo "              <tr><td height=40></td>\n";
  } elseif ($user_cnt == 1) {
  echo "              <tr><td>Move this user to which office?&nbsp;&nbsp;&nbsp;\n";
  } else {
  echo "              <tr><td>Move these users to which office?&nbsp;&nbsp;&nbsp;\n";
}

if ($user_cnt > '0') {
echo "                <select name='office_name' onchange='group_names();'>\n";
echo "                </select>&nbsp;&nbsp;&nbsp;Which Group?\n";
echo "                <select name='group_name' onfocus='group_names();'>
                  <option selected></option>\n";
echo "                </select></td></tr></table>\n";
}

echo "            <table class='table'>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
echo "              <input type='hidden' name='post_groupid' value=\"$post_groupid\">\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Group' src='../images/buttons/next_button.png'></td>
                  <td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
                  include '../footer.php'; exit;
} else {

if ($user_cnt > '0') {
$query4 = "update ".$db_prefix."jobs set office = ('".$office_name."'), groups = ('".$group_name."') where office = ('".$post_officename."') 
           and groups = ('".$post_groupname."')";
$result4 = mysqli_query($db, $query4);
}

$query5 = "delete from ".$db_prefix."groups where groupid = '".$post_groupid."'";
$result5 = mysqli_query($db, $query5);

echo "              <tr><td>Group Name:</td><td align=left width=80%  
                      style='padding-left:20px;' class=table_rows>$post_groupname</td></tr>\n";
echo "              <tr><td>Parent Dept:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows>$post_officename</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left width=80%
                      style='padding-left:20px;' class=table_rows>$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='groupadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php'; exit;
}}
?>
