<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Change Password</title>\n";

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
echo "        <tr class=right_main_text><td align=center>Go back to the <a class='btn btn-default' href='useradmin.php'>Job Summary</a> 
            page to change passwords.</td></tr>\n";
echo "      </table><br /></td></tr></table>\n"; exit;
}

$get_user = $_GET['username'];
@$get_office = $_GET['officename'];

if (get_magic_quotes_gpc()) {$get_user = stripslashes($get_user);}

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";

// display links in top left of each page //


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
echo "            <br />\n";

$get_user = addslashes($get_user);

$query = "select jobname from ".$db_prefix."jobs where jobname = '".$get_user."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$username = stripslashes("".$row['jobname']."");
}
mysqli_free_result($result);
if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}

if (!empty($get_office)) {
$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
}
mysqli_free_result($result);
}
if (!isset($getoffice)) {echo "Dept is not defined for this user. Go back and associate this user with an office.\n"; exit;}

echo "            <table class='table'>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/lock_edit.png' />&nbsp;&nbsp;&nbsp;Change 
                      Password</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td style='padding-left:20px;' 
                      align=left class=table_rows width=80%><input type='hidden' name='post_username' value=\"$username\">$username</td></tr>\n";
echo "              <tr><td>New Password</td><td colspan=2 
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25' name='new_password'></td></tr>\n";
echo "              <tr><td>Confirm Password:</td><td colspan=2 
                      style='padding-left:20px;'><input type='password' size='25' maxlength='25'name='confirm_password'>
                      </td></tr>\n"; 
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <input type='hidden' name='get_office' value='$get_office'>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Change Password' 
                  src='../images/buttons/next_button.png'></td>
                  <td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php';
exit;
}

elseif ($request == 'POST') {

$post_username = stripslashes($_POST['post_username']);
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$get_office = $_POST['get_office'];

// begin post validation //

if (!empty($get_office)) {
$query = "select * from ".$db_prefix."offices where officename = '".$get_office."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$getoffice = "".$row['officename']."";
}
mysqli_free_result($result);
}
if (!isset($getoffice)) {echo "Dept is not defined for this user. Go back and associate this user with an office.\n"; exit;}

// end post validation //

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <table class='table'>\n";
include 'userinfo.php';
echo "        <tr><td>Jobs</td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"useredit.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Job' />&nbsp;&nbsp;Edit Job</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"chngpasswd.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Change Password' />&nbsp;&nbsp;Change Password</a></td>
                </tr>\n";
echo "        <tr><td><a class='btn btn-default' href=\"userdelete.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Job' />&nbsp;&nbsp;Delete Job</a></td></tr>\n";
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

$post_username = addslashes($post_username);

// begin post validation //

if (!empty($post_username)) {
$query = "select * from ".$db_prefix."jobs where jobname = '".$post_username."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$username = "".$row['jobname']."";
}
mysqli_free_result($result);
if (!isset($username)) {echo "username is not defined for this user.\n"; exit;}
}

$post_username = stripslashes($post_username);

//if (!preg_match("/^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|\{|\}|\[|\]|\||\:|\<|\>|\.|,|\?)+$/i", $new_password)) {   
if (!preg_match("/^([[:alnum:]]|~|\!|@|#|\$|%|\^|&|\*|\(|\)|-|\+|`|_|\=|[{]|[}]|\[|\]|\||\:|\<|\>|\.|,|\?)+$/i", $new_password)) {   
$evil_password = '1';
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Single and double quotes, backward and forward slashes, semicolons, and spaces are not allowed when creating a Password.</td></tr>\n";
echo "            </table>\n";
}
elseif ($new_password !== $confirm_password) {
$evil_password = '1';
echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Passwords do not match.</td></tr>\n";
echo "            </table>\n";
}

// end post validation //

if (isset($evil_password)) {

echo "            <br />\n";
echo "            <table class='table'>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/lock_edit.png' />&nbsp;&nbsp;&nbsp;Change 
                    Password</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows width=80% 
                      style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
echo "              <tr><td>New Password:</td><td colspan=2 
                      style='padding-left:20px;' width=80%><input type='password' size='25' maxlength='25' name='new_password'></td></tr>\n";
echo "              <tr><td>Confirm Password:</td><td colspan=2 
                      style='padding-left:20px;' width=80%><input type='password' size='25' maxlength='25'name='confirm_password'>
                      </td></tr>\n"; 
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <input type='hidden' name='get_office' value=\"$get_office\">\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Change Password' 
                      src='../images/buttons/next_button.png'></td><td><a href='useradmin.php'>
                      <img src='../images/buttons/cancel_button.png' border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php';
exit;

} else {

$new_password = crypt($new_password, 'xy');
$confirm_password = crypt($confirm_password, 'xy');

$post_username = addslashes($post_username);

$query = "update ".$db_prefix."jobs set employee_passwd = ('".$new_password."') where jobname = ('".$post_username."')";
$result = mysqli_query($db, $query);

$post_username = stripslashes($post_username);

echo "            <table class='table'>\n";
echo "              <tr><td><img src='../images/icons/accept.png' /></td>
                <td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/lock_edit.png' />&nbsp;&nbsp;&nbsp;Change 
                      Password</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td align=left class=table_rows width=80% 
                      style='padding-left:20px;'><input type='hidden' name='post_username' value=\"$post_username\">$post_username</td></tr>\n";
echo "              <tr><td>New Password</td><td align=left class=table_rows 
                      colspan=2 style='padding-left:20px;' width=80%>***hidden***</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td><a href='useradmin.php'><img src='../images/buttons/done_button.png' border='0'></td></tr>
            </table></td></tr>\n"; 
include '../footer.php';
exit;
}
}
?>
