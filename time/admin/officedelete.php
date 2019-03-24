<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php';}
echo "<title>$title - Delete Dept</title>\n";

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

if (!isset($officename)) {echo "Dept name is not defined for this group.\n"; exit;}

$query2 = "select office from ".$db_prefix."jobs where office = '".$get_office."'";
$result2 = mysqli_query($db, $query2);
@$user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$officeid."'";
$result3 = mysqli_query($db, $query3);
@$group_cnt = mysqli_num_rows($result3);

if ($user_cnt > 0) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td>";
  if ($user_cnt == 1) { 
echo "<td class=table_rows_red>This office 
                    contains $user_cnt user. This user must be moved to another office and group before it can be deleted.</td></tr>\n";
  } else {
echo "<td class=table_rows_red>This office 
                    contains $user_cnt users. These users must be moved to another office and group before it can be deleted.</td></tr>\n";
  }
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_delete.png' />&nbsp;&nbsp;&nbsp;Delete Dept
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='post_officename' value=\"$officename\">$get_office</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left width=80% 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left 
                      class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

  if ($user_cnt == 1) { 
  echo "              <tr><td>Move this user to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  } else {
  echo "              <tr><td>Move these users to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  }

echo "                <select name='office_name' onchange='group_names();'>
                  <option selected>Choose One</option>\n";
echo "                </select>&nbsp;&nbsp;&nbsp;Which Group?\n";
echo "                <select name='group_name'>\n";
echo "                </select></td></tr></table>\n";

echo "            <table class='table'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Dept' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'>
                      <img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n"; include '../footer.php'; exit;

} elseif ($user_cnt == '0') {

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_delete.png' />&nbsp;&nbsp;&nbsp;Delete Dept
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='post_officename' value=\"$officename\">$get_office</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left width=80% 
                      style='padding-left:20px;'class=table_rows><input type='hidden' name='group_cnt' value=\"$group_cnt\">$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left width=80% 
                      style='padding-left:20px;' class=table_rows><input type='hidden' name='user_cnt' value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$officeid\">\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <input type='hidden' name='group_name' value='no_group_users'>\n";
echo "              <input type='hidden' name='office_name' value='no_office_users'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Dept' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'>
                      <img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n"; include '../footer.php'; exit;
} exit;
}

elseif ($request == 'POST') {

include 'header_post.php';include 'topmain.php';

$post_officename = $_POST['post_officename'];
@$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];
$post_officeid = $_POST['post_officeid'];
$group_cnt = $_POST['group_cnt'];
$user_cnt = $_POST['user_cnt'];

// begin post validation //

if ((!empty($post_officename)) || (!empty($post_officeid))) {
$query = "select * from ".$db_prefix."offices where officename = '".$post_officename."' and officeid = '".$post_officeid."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$officename = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
}

if ((!isset($officename)) || (!isset($officeid))) {echo "Dept name is not defined.\n"; exit;}

if ((!empty($office_name)) && ($office_name != 'no_office_users')) {
$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_officename = "".$row['officename']."";
$tmp_officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
if ((!isset($tmp_officename)) || (!isset($tmp_officeid))) {echo "Dept name is not defined for this group.\n"; exit;}
}

if ((!empty($group_name)) && ($group_name != 'no_group_users')) {
$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_groupname = "".$row['groupname']."";
$tmp_groupid = "".$row['groupid']."";
}
mysqli_free_result($result);
if ((!isset($tmp_groupname)) || (!isset($tmp_groupid))) {echo "Dept name is not defined for this group.\n"; exit;}
}

$query2 = "select office from ".$db_prefix."jobs where office = '".$post_officename."'";
$result2 = mysqli_query($db, $query2);
@$tmp_user_cnt = mysqli_num_rows($result2);

$query3 = "select * from ".$db_prefix."groups where officeid = '".$post_officeid."'";
$result3 = mysqli_query($db, $query3);
@$tmp_group_cnt = mysqli_num_rows($result3);

if ($user_cnt != $tmp_user_cnt) {echo "Posted user count does not equal actual user count for this office.\n"; exit;}
if ($group_cnt != $tmp_group_cnt) {echo "Posted group count does not equal actual group count for this office.\n"; exit;}

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

if ((empty($office_name)) || (empty($group_name)) || ($office_name == $post_officename)) {

echo "        <tr><td><a class='btn btn-default' href=\"officeedit.php?officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Edit Dept' />&nbsp;&nbsp;Edit Dept</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"officedelete.php?officename=$post_officename\"><img src='../images/icons/arrow_right.png' alt='Delete Dept' />&nbsp;&nbsp;Delete Dept</a></td></tr>\n";

} else { 

echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Edit Dept' />&nbsp;&nbsp;
                Edit Dept</td></tr>\n";
echo "        <tr><td><img src='../images/icons/arrow_right.png' alt='Delete Dept' />
                &nbsp;&nbsp;Delete Dept</td></tr>\n";
}

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

if ((empty($office_name)) || (empty($group_name))) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td>\n";
echo "                <td>To delete this office, you must choose to move its' current users to another 
                      office <b>AND</b> group.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
} elseif ($office_name == $post_officename) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td>\n";
echo "                <td>To delete this office, you must choose to move its' current users to a <b>DIFFERENT</b> 
                      office and group.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
} else {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/accept.png' /></td>
                <td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
}
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_delete.png' />&nbsp;&nbsp;&nbsp;Delete Dept
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";

if ((empty($office_name)) || (empty($group_name)) || ($office_name == $post_officename)) {

echo "              <tr><td>Dept Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'><input type='hidden' name='post_officename'
                      value=\"$post_officename\">$post_officename</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left 
                      class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='group_cnt'
                      value=\"$group_cnt\">$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left 
                      class=table_rows width=80% style='padding-left:20px;'><input type='hidden' name='user_cnt'
                      value=\"$user_cnt\">$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <input type='hidden' name='post_officeid' value=\"$post_officeid\">\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

  if ($user_cnt == 1) { 
  echo "            <tr><td>Move this user to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  } else {
  echo "            <tr><td>Move these users to which office?&nbsp;&nbsp;&nbsp;&nbsp;\n";
  }

echo "                <select name='office_name' onchange='group_names();'>
                  <option selected>Choose One</option>\n";
echo "                </select>&nbsp;&nbsp;&nbsp;Which Group?\n";
echo "                <select name='group_name' onfocus='group_names();'>\n";
echo "                </select></td></tr></table>\n";

echo "            <table class='table'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Delete Dept' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'>
                      <img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n"; include '../footer.php'; exit;

} else {

if ($user_cnt > 0) {
$query4 = "update ".$db_prefix."jobs set office = ('".$office_name."'), groups = ('".$group_name."') where office = ('".$post_officename."')";
$result4 = mysqli_query($db, $query4);
}

$query5 = "delete from ".$db_prefix."offices where officeid = '".$post_officeid."'";
$result5 = mysqli_query($db, $query5);

$query6 = "delete from ".$db_prefix."groups where officeid = '".$post_officeid."'";
$result6 = mysqli_query($db, $query6);

echo "              <tr><td>Dept Name:</td><td align=left class=table_rows 
                      width=80% style='padding-left:20px;'>$post_officename</td></tr>\n";
echo "              <tr><td>Group Count:</td><td align=left 
                      class=table_rows width=80% style='padding-left:20px;'>$group_cnt</td></tr>\n";
echo "              <tr><td>Job Count:</td><td align=left 
                      class=table_rows width=80% style='padding-left:20px;'>$user_cnt</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='officeadmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td>
              </tr>\n";
include '../footer.php'; exit;
}}
?>
