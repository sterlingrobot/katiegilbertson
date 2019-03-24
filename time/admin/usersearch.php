<?php
session_start();

$self = $_SERVER['PHP_SELF'];
$request = $_SERVER['REQUEST_METHOD'];

include '../config.inc.php';
if ($request !== 'POST') {include 'header_get.php';include 'topmain.php';}
echo "<title>$title - Job Search</title>\n";

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

if ($request !== 'POST') {

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
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/magnifier.png' />&nbsp;&nbsp;&nbsp;Search for Job
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='25' maxlength='50' name='post_username' 
                      onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
                      form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\" ></td></tr>\n";
echo "              <tr><td>Display Name:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='25' maxlength='50' name='display_name' 
                      onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
                      form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Email Address:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' 
                      size='25' maxlength='75' name='email_addy' 
                      onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
                      form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Dept:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td>Group:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='group_name'>\n";
echo "                      </select></td></tr>\n";

echo "              <tr><td><a class=footer_links 
                      href=\"usersearch.php\" style='text-decoration:underline;'>reset form</a>&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Job' align='middle' 
                      src='../images/buttons/search_button.png'></td><td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}

elseif ($request == 'POST') {

include 'header_post.php';include 'topmain.php';

@$post_username = stripslashes($_POST['post_username']);
@$display_name = stripslashes($_POST['display_name']);
@$email_addy = $_POST['email_addy'];
@$office_name = $_POST['office_name'];
@$group_name = $_POST['group_name'];

//$post_username = addslashes($post_username);
//$display_name = addslashes($display_name);
//$office_name = addslashes($office_name);
//$group_name = addslashes($group_name);

// begin post validation //

if ((!preg_match("/^([[:alnum:]]| |-|'|,)+$/i", $post_username)) || (!preg_match("/^([[:alnum:]]| |-|'|,)+$/i", $display_name)) ||
(!preg_match("/^([[:alnum:]]|_|\.|-|@)+$/i", $email_addy))) {

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
if (!preg_match("/^([[:alnum:]]| |-|'|,)+$/i", $post_username)) {
if ($post_username == "") {} else {
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td>
                    &nbsp;Alphanumeric characters, hyphens, apostrophes, commas, and spaces are allowed when searching for a Jobname.</td></tr>\n";
echo "            </table>\n";
$evil_input = "1";
}}
if (!preg_match("/^([[:alnum:]]| |-|'|,)+$/i", $display_name)) {
if ($display_name == "") {} else {
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td>
                    &nbsp;Alphanumeric characters, hyphens, apostrophes, commas, and spaces are allowed when searching for a Display Name.</td></tr>\n";
echo "            </table>\n";
$evil_input = "1";
}}
if (!preg_match("/^([[:alnum:]]|_|\.|-|@)+$/i", $email_addy)) {
if ($email_addy == "") {} else {
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td>
                    &nbsp;Alphanumeric characters, underscores, periods, and hyphens are allowed when searching for an Email Address.</td></tr>\n";
echo "            </table>\n";
$evil_input = "1";
}}
if (($post_username == "") && ($display_name == "") && ($email_addy == "")) {
echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td>
                    &nbsp;A Jobname, Display Name, or Email Address is required.</td></tr>\n";
echo "            </table>\n";
$evil_input = "1";
}

if (!empty($office_name)) {
$query = "select * from ".$db_prefix."offices where officename = '".$office_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_officename = "".$row['officename']."";
}
mysqli_free_result($result);
if (!isset($tmp_officename)) {echo "Dept is not defined.\n"; exit;}
}

if (!empty($group_name)) {
$query = "select * from ".$db_prefix."groups where groupname = '".$group_name."'";
$result = mysqli_query($db, $query);
while ($row=mysqli_fetch_array($result)) {
$tmp_groupname = "".$row['groupname']."";
}
mysqli_free_result($result);
if (!isset($tmp_officename)) {echo "Group is not defined.\n"; exit;}
}

// end post validation //

if (isset($evil_input)) {

echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/magnifier.png' />&nbsp;&nbsp;&nbsp;Search for Job
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' style='color:red;' size='25' maxlength='50' 
                      name='post_username' value='$post_username' 
                      onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
                      form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Display Name:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' style='color:red;' size='25' maxlength='50' 
                      name='display_name' value='$display_name' 
                      onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
                      form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Email Address:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text style='color:red;' size='25' maxlength='75' 
                      name='email_addy' value='$email_addy' 
                      onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
                      form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Dept:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td>Group:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='group_name' onfocus='group_names();'>
                        <option selected>$group_name</option>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td><a class=footer_links 
                      href=\"usersearch.php\" style='text-decoration:underline;'>reset form</a></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Job' align='middle' 
                      src='../images/buttons/search_button.png'></td><td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php';
exit;

} else {

$post_username = addslashes($post_username);
$display_name = addslashes($display_name);
$office_name = addslashes($office_name);
$group_name = addslashes($group_name);

if (!empty($post_username)) {
$tmp_var = $post_username;
$tmp_var2 = "Jobname";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where jobname LIKE '%".$post_username."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (!empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where jobname LIKE '%".$post_username."%' and office = '".$office_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where jobname LIKE '%".$post_username."%' 
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
}

elseif (!empty($display_name)) {
$tmp_var = $display_name;
$tmp_var2 = "Display Name";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where displayname LIKE '%".$display_name."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (!empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where displayname LIKE '%".$display_name."%' and office = '".$office_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where displayname LIKE '%".$display_name."%' 
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
}

elseif (!empty($email_addy)) {
$tmp_var = $email_addy;
$tmp_var2 = "Email Address";

  if ((!empty($office_name)) && (!empty($group_name))) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where email LIKE '%".$email_addy."%' and office = '".$office_name."' and groups = '".$group_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (!empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where email LIKE '%".$email_addy."%' and office = '".$office_name."'
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
  elseif (empty($office_name)) {
  $query4 = "select jobname, displayname, email, groups, office, admin, reports, time_admin, disabled from ".$db_prefix."jobs
            where email LIKE '%".$email_addy."%' 
            order by jobname";
  $result4 = mysqli_query($db, $query4);
  } 
}

$tmp_var = stripslashes($tmp_var);
$tmp_var2 = stripslashes($tmp_var2);
$row_count = "0";

while ($row=mysqli_fetch_array($result4)) {

@$user_count_rows = mysqli_num_rows($user_count);
@$admin_count_rows = mysqli_num_rows($admin_count);
@$reports_count_rows = mysqli_num_rows($reports_count);

$row_count++;

if ($row_count == "1") {

echo "            <table class='table'>\n";
echo "              <tr><th class=table_heading_no_color nowrap width=100% halign=left>Job Search Summary</th></tr>\n";
echo "              <tr><td height=40 class=table_rows nowrap halign=left>Search Results for \"$tmp_var\" in $tmp_var2</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
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
}

$row_color = ($row_count % 2) ? $color2 : $color1;
$jobname = stripslashes("".$row['jobname']."");
$displayname = stripslashes("".$row['displayname']."");

echo "              <tr class=table_border bgcolor='$row_color'><td>&nbsp;$row_count</td>\n";
echo "                <td>&nbsp;<a class=footer_links title=\"Edit Job: $jobname\"
                    href=\"useredit.php?username=$jobname&officename=".$row["office"]."\">$jobname</a></td>\n";
echo "                <td>$displayname</td>\n";
//echo "                <td>".$row["email"]."</td>\n";
echo "                <td>".$row['office']."</td>\n";
echo "                <td>".$row['groups']."</td>\n";

if ("".$row["disabled"]."" == 1) {
  echo "                <td><img src='../images/icons/cross.png' /></td>\n";
} else {
  $disabled = "";
  echo "                <td>".$disabled."</td>\n";
}
if ("".$row["admin"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $admin = "";
  echo "                <td>".$admin."</td>\n";
}
if ("".$row["time_admin"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $time_admin = "";
  echo "                <td>".$time_admin."</td>\n";
}
if ("".$row["reports"]."" == 1) {
  echo "                <td><img src='../images/icons/accept.png' /></td>\n";
} else {
  $reports = "";
  echo "                <td>".$reports."</td>\n";
}


echo "                <td>
                    <a title=\"Edit Job: $jobname\" href=\"useredit.php?username=$jobname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/application_edit.png' /></td>\n";
echo "                <td>
                    <a title=\"Change Password: $jobname\" 
                    href=\"chngpasswd.php?username=$jobname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/lock_edit.png' /></td>\n";
echo "                <td>
                    <a title=\"Delete Job: $jobname\" href=\"userdelete.php?username=$jobname&officename=".$row["office"]."\">
                    <img border=0 src='../images/icons/delete.png' /></td>\n";
echo "              </tr>\n";
}
mysqli_free_result($result4);

if ($row_count == "0") {

$post_username = stripslashes($post_username);

echo "            <br />\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td>
                    &nbsp;A user was not found matching your criteria. Please try again.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/magnifier.png' />&nbsp;&nbsp;&nbsp;Search for Job
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Jobname:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' style='color:red;' size='25' maxlength='50' 
                      name='post_username' value=\"$post_username\" 
                      onFocus=\"javascript:form.display_name.disabled=true;form.email_addy.disabled=true;
                      form.display_name.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Display Name:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text' style='color:red;' size='25' maxlength='50' 
                      name='display_name' value=\"$display_name\" 
                      onFocus=\"javascript:form.post_username.disabled=true;form.email_addy.disabled=true;
                      form.post_username.style.background='#eeeeee';form.email_addy.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Email Address:</td><td colspan=2 width=80% 
                      style='color:red;font-family:Tahoma;;padding-left:20px;'><input type='text style='color:red;' size='25' maxlength='75' 
                      name='email_addy' value=\"$email_addy\" 
                      onFocus=\"javascript:form.post_username.disabled=true;form.display_name.disabled=true;
                      form.post_username.style.background='#eeeeee';form.display_name.style.background='#eeeeee';\"></td></tr>\n";
echo "              <tr><td>Dept:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='office_name' onchange='group_names();'>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td>Group:</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <select name='group_name' onfocus='group_names();'>
                        <option selected>$group_name</option>\n";
echo "                      </select></td></tr>\n";
echo "              <tr><td><a class=footer_links 
                      href=\"usersearch.php\" style='text-decoration:underline;'>reset form</a></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Job' align='middle' 
                      src='../images/buttons/search_button.png'></td><td><a href='useradmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
} else {

echo "            </table></td></tr>\n";
include '../footer.php'; exit;
}}}}
?>
