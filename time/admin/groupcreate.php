<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Create Group</title>\n";

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
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Group Name:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_groupname'>&nbsp;*</td></tr>\n";

// query to populate dropdown with parent offices //

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($db, $query);

echo "              <tr><td>Parent Dept:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <select name='select_office_name'>\n";
echo "                        <option value ='1'>Choose One</option>\n";

while ($row=mysqli_fetch_array($result)) {
  echo "                        <option>".$row['officename']."</option>\n";
}
echo "                      </select>&nbsp;*</td></tr>\n";
mysqli_free_result($result);

echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Group' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}

elseif ($request == 'POST') {

$select_office_name = $_POST['select_office_name'];
$post_groupname = $_POST['post_groupname'];

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
echo "            <br />\n";

$post_groupname = stripslashes($post_groupname);
$select_office_name = stripslashes($select_office_name);
$post_groupname = addslashes($post_groupname);
$select_office_name = addslashes($select_office_name);

// begin post validation //

if (!empty($select_office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$select_office_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
$officeid = "".$row['officeid']."";
}
mysqli_free_result($result);
}
if ((!isset($getoffice)) && ($select_office_name != '1')) {echo "Dept is not defined for this user. Go back and associate this user with an office.\n"; 
exit;}

// check for duplicate groupnames with matching officeids //

$query = "select * from ".$db_prefix."groups where groupname = '".$post_groupname."' and officeid = '".@$officeid."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {
  $tmp_groupname = "".$row['groupname']."";
}

$string = strstr($post_groupname, "\'");
$string2 = strstr($post_groupname, "\"");

if ((!empty($string)) || (empty($post_groupname)) || (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_groupname)) || ($select_office_name == '1') ||
(@$tmp_groupname == $post_groupname) || (!empty($string2))) {

if (!empty($string)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Apostrophes are not allowed when creating a Group Name.</td></tr>\n";
echo "            </table>\n";
}elseif (!empty($string2)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Double Quotes are not allowed when creating a Group Name.</td></tr>\n";
echo "            </table>\n";
}elseif (empty($post_groupname)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A Group Name is required.</td></tr>\n";
echo "            </table>\n";
}elseif (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_groupname)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when creating a Group Name.</td></tr>\n";
echo "            </table>\n";
}elseif ($select_office_name == '1') {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A Parent Dept must be chosen.</td></tr>\n";
echo "            </table>\n";
}elseif (@$tmp_groupname == $post_groupname) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Group already exists. Create another group.</td></tr>\n";
echo "            </table>\n";
}
echo "            <br />\n";

// end post validation //

if (!empty($string)) {$post_groupname = stripslashes($post_groupname);}
if (!empty($string2)) {$post_groupname = stripslashes($post_groupname);}

echo "            <table class='table'>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Group Name:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_groupname' value=\"$post_groupname\">&nbsp;*</td></tr>\n";

if (!empty($string)) {$post_groupname = addslashes($post_groupname);}
if (!empty($string2)) {$post_groupname = addslashes($post_groupname);}

// query to populate dropdown with parent offices //

$query = "select * from ".$db_prefix."offices order by officename asc";
$result = mysqli_query($db, $query);

echo "              <tr><td>Parent Dept:</td><td colspan=2 align=left width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <select name='select_office_name'>\n";
echo "                        <option value ='1'>Choose One</option>\n";

while ($row=mysqli_fetch_array($result)) {
  if ("".$row['officename']."" == $select_office_name) {
  echo "                        <option selected>".$row['officename']."</option>\n";
  } else {
  echo "                        <option>".$row['officename']."</option>\n";
  }
}
echo "                      </select>&nbsp;*</td></tr>\n";
mysqli_free_result($result);

echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Group' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='groupadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;

} else {

$query = "insert into ".$db_prefix."groups (groupname, officeid) values ('".$post_groupname."', '".$officeid."')";
$result = mysqli_query($db, $query);

echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                  &nbsp;Group created successfully.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/group_add.png' />&nbsp;&nbsp;&nbsp;Create Group
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Group Name:</td><td class=table_rows width=80% 
                      style='padding-left:20px;' colspan=2>$post_groupname</td></tr>\n";
echo "              <tr><td>Parent Dept:</td><td class=table_rows width=80% 
                      style='padding-left:20px;' colspan=2>$select_office_name</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='groupcreate.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php'; exit;
}
}
?>
