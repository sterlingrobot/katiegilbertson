<?php
session_start();

include '../config.inc.php';
include 'header_colorpick.php';
include 'topmain.php';
echo "<title>$title - Create Status</title>\n";

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
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3>
                    <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Status</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Status Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='20' maxlength='50' name='post_statusname'>&nbsp;*</td></tr>\n";
echo "              <tr><td>Color:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text'
                      size='20' maxlength='7' name='post_color'>&nbsp;*&nbsp;&nbsp;<a href=\"#\"
                      onclick=\"cp.select(document.forms['form'].post_color,'pick');return false;\" name=\"pick\" id=\"pick\"
                      style='font-size:11px;color:#27408b;'>Pick Color</a></td></tr>\n";
echo "              <tr><td>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>\n";
echo "                  <td><input type='radio' name='create_status' value='1'>In
                      <input checked type='radio' name='create_status' value='0'>Out</td></tr>\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Status' src='../images/buttons/next_button.png'></td>
                  <td><a href='statusadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php';
exit;
}

elseif ($request == 'POST') {

$post_statusname = $_POST['post_statusname'];
$post_color = $_POST['post_color'];
$create_status = $_POST['create_status'];

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

// begin post validation //

if (($create_status !== '0') && ($create_status !== '1')) {exit;}

$post_statusname = stripslashes($post_statusname);
$post_statusname = addslashes($post_statusname);

$string = strstr($post_statusname, "\'");
$string2 = strstr($post_statusname, "\"");

if (empty($string)) {
$query = "select punchitems from ".$db_prefix."punchlist where punchitems = '".$post_statusname."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {
$dupe = '1';
}
}

if ((empty($post_statusname)) || (empty($post_color)) || (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_statusname)) || (isset($dupe)) || 
((!preg_match("/^(#[a-fA-F0-9]{6})+$/i", $post_color)) && (!preg_match("/^([a-fA-F0-9]{6})+$/i", $post_color))) || (!empty($string)) || (!empty($string2))) {

if (empty($post_statusname)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A Status Name is required.</td></tr>\n";
echo "            </table>\n";
}
elseif (empty($post_color)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A Color is required.</td></tr>\n";
echo "            </table>\n";
}elseif (!empty($string)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Apostrophes are not allowed.</td></tr>\n";
echo "            </table>\n";
}elseif (!empty($string2)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Double Quotes are not allowed.</td></tr>\n";
echo "            </table>\n";
}elseif (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_statusname)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when editing a Status Name.</td></tr>\n";
echo "            </table>\n";
}
elseif ((!preg_match("/^(#[a-fA-F0-9]{6})+$/i", $post_color)) && (!preg_match("/^([a-fA-F0-9]{6})+$/i", $post_color))) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    The '#' symbol followed by letters A-F, or numbers 0-9 are allowed when editing a Color.</td></tr>\n";
echo "            </table>\n";
}elseif (isset($dupe)) {
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Status already exists. Create another status.</td></tr>\n";
echo "            </table>\n";
}

if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3>
                      <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Status</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Status Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='20' maxlength='50' name='post_statusname' value=\"$post_statusname\">&nbsp;*</td></tr>\n";
echo "              <tr><td>Color:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text'
                      size='20' maxlength='7' name='post_color' value=\"$post_color\">&nbsp;*&nbsp;&nbsp;<a href=\"#\" 
                      onclick=\"cp.select(document.forms['form'].post_color,'pick');return false;\" name=\"pick\" id=\"pick\" 
                      style='font-size:11px;color:#27408b;'>Pick Color</a></td></tr>\n";
echo "              <tr><td>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>\n";

if ($create_status == '1') {
echo "                  <td><input checked type='radio' name='create_status' value='1'>In
                      <input type='radio' name='create_status' value='0'>Out</td></tr>\n";
} elseif ($create_status == '0') {
echo "                  <td><input type='radio' name='create_status' value='1'>In
                      <input checked type='radio' name='create_status' value='0'>Out</td></tr>\n";
}

if (!empty($string)) {$post_statusname = stripslashes($post_statusname);}
if (!empty($string2)) {$post_statusname = stripslashes($post_statusname);}

echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <script language=\"javascript\">cp.writeDiv()</script>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=40></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Status' src='../images/buttons/next_button.png'></td>
                  <td><a href='statusadmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php';
exit;

} else {

$query = "insert into ".$db_prefix."punchlist (punchitems, color, in_or_out) values ('".$post_statusname."', '".$post_color."', '".$create_status."')";
$result = mysqli_query($db, $query);

echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/accept.png' /></td>
                <td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3>
                      <img src='../images/icons/application_add.png' />&nbsp;&nbsp;&nbsp;Create Status</th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Status Name:</td><td align=left class=table_rows 
                      colspan=2 width=80% style='padding-left:20px;'>$post_statusname</td></tr>\n";
echo "              <tr><td>Color:</td><td align=left class=table_rows 
                      colspan=2 width=80% style='padding-left:20px;'>$post_color</td></tr>\n";

if ($create_status == '1') {
  $create_status_tmp = 'In';
  } else {
  $create_status_tmp = 'Out';
}

echo "              <tr><td>Is Status considered '<b>In</b>' or '<b>Out</b>'?</td>
                      <td align=left class=table_rows colspan=2 width=80% style='padding-left:20px;'>$create_status_tmp</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='statusadmin.php'><img src='../images/buttons/done_button.png' 
                      border='0'></a></td></tr></table>\n";
}
include '../footer.php'; exit;
}
?>
