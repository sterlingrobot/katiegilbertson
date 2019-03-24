<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Create Dept</title>\n";

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
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_add.png' />&nbsp;&nbsp;&nbsp;Create Dept
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_officename'>&nbsp;*</td></tr>\n";
echo "              <tr><td>Create Groups Within This Dept?</td>\n";
echo "                  <td><input type='radio' name='create_groups' value='1' 
                      onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Yes
                      <input checked type='radio' name='create_groups' value='0'
                      onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No</td></tr>\n";
echo "              <tr><td>How Many?</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>
                      <input disabled type='text' size='2' maxlength='1' name='how_many' style='background:#eeeeee;'></td></tr>\n";
echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Dept' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}

elseif ($request == 'POST') {

$post_officename = $_POST['post_officename'];
$create_groups = $_POST['create_groups'];
@$how_many = $_POST['how_many'];
@$input_group_name = $_POST['input_group_name'];

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

if (get_magic_quotes_gpc()) {$post_officename = stripslashes($post_officename);}
$post_officename = addslashes($post_officename);

// begin post validation //

// check for duplicate officenames //

$query = "select * from ".$db_prefix."offices where officename = '".$post_officename."'";
$result = mysqli_query($db, $query);

while ($row=mysqli_fetch_array($result)) {
  $tmp_officename = "".$row['officename']."";
}

// error checking: check for duplicate names, disallow certain characters for some fields, etc... //

$string = strstr($post_officename, "\'");
$string2 = strstr($post_officename, "\"");

if ((@$tmp_officename == $post_officename) || (empty($post_officename)) || (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_officename)) || 
((!preg_match("/^([0-9])$/i", @$how_many)) && (isset($how_many))) || (@$how_many == '0') || (($create_groups != '1') && (!empty($create_groups))) ||
(!empty($string)) || (!empty($string2))) {

if (empty($post_officename)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    An Dept Name is required.</td></tr>\n";
echo "            </table>\n";
}
elseif (!empty($string)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Apostrohpes are not allowed when creating an Dept Name.</td></tr>\n";
echo "            </table>\n";
}
elseif (!empty($string2)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Double Quotes are not allowed when creating an Dept Name.</td></tr>\n";
echo "            </table>\n";
}
elseif (@$tmp_officename == $post_officename) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Dept already exists. Create another office.</td></tr>\n";
echo "            </table>\n";
}
elseif (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $post_officename)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when creating an Dept Name.</td></tr>\n";
echo "            </table>\n";
}
elseif (($create_groups == '1') && (empty($how_many))) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Please input the number of groups you wish to create within this new office.</td></tr>\n";
echo "            </table>\n";
}
elseif (($create_groups == '1') && ($how_many == '0')) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    You have chosen to create groups within this new office. Please input a number other than '0' for 'How Many?'.</td></tr>\n";
echo "            </table>\n";
}
elseif (!preg_match("/^([0-9])$/i", $how_many)) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Only numeric characters are allowed for an office count.</td></tr>\n";
echo "            </table>\n";
}elseif (($create_groups != '1') && (!empty($create_groups))) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Choose \"yes\" or \"no\" to the <i>Create Groups Within This Dept</i> question.</td></tr>\n";
echo "            </table>\n";
}
echo "            <br />\n";

if (!empty($string)) {$post_officename = stripslashes($post_officename);}
if (!empty($string2)) {$post_officename = stripslashes($post_officename);}

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_add.png' />&nbsp;&nbsp;&nbsp;Create Dept
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td colspan=2 width=80%
                      style='color:red;font-family:Tahoma;;padding-left:20px;'>
                      <input type='text' size='25' maxlength='50' name='post_officename' value=\"$post_officename\">&nbsp;*</td></tr>\n";

if (!empty($string)) {$post_officename = addslashes($post_officename);}
if (!empty($string2)) {$post_officename = addslashes($post_officename);}

echo "              <tr><td>Create Groups Within This Dept?</td>\n";
if ($create_groups == '1') {

echo "                  <td><input type='radio' name='create_groups' value='1' checked
                      onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Yes
                      <input type='radio' name='create_groups' value='0'
                      onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No</td></tr>\n";
} else {

echo "                  <td><input type='radio' name='create_groups' value='1' 
                      onFocus=\"javascript:form.how_many.disabled=false;form.how_many.style.background='#ffffff';\">Yes
                      <input checked type='radio' name='create_groups' value='0'
                      onFocus=\"javascript:form.how_many.disabled=true;form.how_many.style.background='#eeeeee';\">No</td></tr>\n";
}

echo "              <tr><td>How Many?</td><td colspan=2 width=80% 
                      style='padding-left:20px;'>\n";

if ($create_groups == '1') {
echo "                      <input type='text' size='2' maxlength='1' name='how_many' value='$how_many'></td></tr>\n";
} else {
echo "                      <input disabled type='text' size='2' maxlength='1' name='how_many' style='background:#eeeeee;' value='$how_many'></td></tr>\n";
}

echo "              <tr><td colspan=2 class='text-right text-danger'>*&nbsp;required&nbsp;</td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

echo "              <tr><td width=30><input type='image' name='submit' value='Create Dept' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";include '../footer.php'; exit;
}

// end post validation //

if (isset($input_group_name)) {

for ($x=0;$x<$how_many;$x++) {

$z = $x+1;

// begin post validation // 

if (empty($input_group_name[$z])) {$empty_groupname = '1';}
if (!preg_match("/^([[:alnum:]]| |-|_|\.)+$/i", $input_group_name[$z])) {$evil_groupname = '1';}

}

@$groupname_array_cnt = count($input_group_name);
@$unique_groupname_array = array_unique($input_group_name);
@$unique_groupname_array_cnt = count($unique_groupname_array);

if ((@$empty_groupname != '1') && (@$evil_groupname != '1') && (@$groupname_array_cnt == @$unique_groupname_array_cnt)) {

$query = "insert into ".$db_prefix."offices (officename) values ('".$post_officename."')";
$result = mysqli_query($db, $query);

$query2 = "select * from ".$db_prefix."offices where officename = '".$post_officename."'";
$result2 = mysqli_query($db, $query2);

while ($row=mysqli_fetch_array($result2)) {
  $tmp_officeid = "".$row['officeid']."";
}
mysqli_free_result($result2);

for ($x=0;$x<$how_many;$x++) {
$y = $x+1;
$query3 = "insert into ".$db_prefix."groups (groupname, officeid) values ('".$input_group_name[$y]."', '".$tmp_officeid."')";
$result3 = mysqli_query($db, $query3);
}

echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "              <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                  &nbsp;Dept created successfully.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
}

echo "            <table class='table'>\n";
echo "            <form name='form' action='$self' method='post'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_add.png' />&nbsp;&nbsp;&nbsp;Create Dept
                </th>\n";
echo "              </tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td class=table_rows colspan=2 
                      width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_officename' value='$post_officename'>$post_officename</td></tr>\n";
echo "              <tr><td>Create Groups Within This Dept?</td><td 
                      class=table_rows colspan=2 width=80% style='padding-left:20px;'>
                      <input type='hidden' name='create_groups' value='$create_groups'>$create_groups</td></tr>\n";
echo "              <tr><td>How Many?</td><td class=table_rows colspan=2 
                      width=80% style='padding-left:20px;'>
                      <input type='hidden' name='how_many' value='$how_many'>$how_many</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <br /><br />\n";

if (@$empty_groupname == '1')  {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    A Group Name is required.</td></tr>\n";
echo "            </table>\n";
} elseif (@$evil_groupname == '1') {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Alphanumeric characters, hyphens, underscores, spaces, and periods are allowed when creating a Group Name.</td></tr>\n";
echo "            </table>\n";
} elseif (@$groupname_array_cnt != @$unique_groupname_array_cnt) {
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/cancel.png' /></td><td class=table_rows_red>
                    Duplicate Group Name exists.</td></tr>\n";
echo "            </table>\n";
}

if ((@$empty_groupname != '1') && (@$evil_groupname != '1') && (@$groupname_array_cnt == @$unique_groupname_array_cnt)) {

echo "            <table class='table'>\n";
echo "              <tr>\n";
if ($how_many == '1') {
echo "              <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                  &nbsp;$how_many group was created within the <b>$post_officename</b> office successfully.</td></tr>\n";
} elseif ($how_many > '1') {
echo "              <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                  &nbsp;$how_many groups were created within the <b>$post_officename</b> office successfully.</td></tr>\n";
}
echo "            </table>\n";
}

echo "            <table class='table'>\n";
echo "              <tr><td height=15></td></tr>\n";

for ($x=0;$x<$how_many;$x++) {
$y = $x+1;

if ((@$empty_groupname == '1') || (@$evil_groupname == '1') || (@$groupname_array_cnt != @$unique_groupname_array_cnt)) {
echo "              <tr><td>$y.&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type='text' size='25' maxlength='50' name='input_group_name[$y]' value=\"$input_group_name[$y]\"></td></tr>\n";
} else {
echo "              <tr><td>$y.&nbsp;&nbsp;&nbsp;&nbsp;$input_group_name[$y]</td></tr>\n";
}
} // end for loop

echo "            </table>\n";
echo "            <table class='table'>\n";

if ((@$empty_groupname == '1') || (@$evil_groupname == '1') || (@$groupname_array_cnt != @$unique_groupname_array_cnt)) {
echo "              <tr><td height=20>&nbsp;</td></tr>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Dept' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php'; exit;

} else {

echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='officecreate.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php'; exit;
}

} else {

if (!isset($how_many)) {

$query = "insert into ".$db_prefix."offices (officename) values ('".$post_officename."')";
$result = mysqli_query($db, $query);

echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <td><img src='../images/icons/accept.png' /></td><td class=table_rows_green>
                &nbsp;Dept created successfully.</td></tr>\n";
echo "            </table>\n";
echo "            <br />\n";
}

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr>\n";
echo "                <th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/brick_add.png' />&nbsp;&nbsp;&nbsp;Create Dept
                </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td>Dept Name:</td><td class=table_rows colspan=2 
                      width=80% style='padding-left:20px;'>
                      <input type='hidden' name='post_officename' value='$post_officename'>$post_officename</td></tr>\n";
echo "              <tr><td>Create Groups Within This Dept?</td><td 
                      class=table_rows colspan=2 width=80% style='padding-left:20px;'>\n";

if ($create_groups == "1") {$tmp_create_groups = "Yes";}
else {$tmp_create_groups = "No";}
echo "                      <input type='hidden' name='create_groups' value='$create_groups'>$tmp_create_groups</td></tr>\n";

if (!isset($how_many)) {

echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";
echo "              <tr><td height=20 align=left>&nbsp;</td></tr>\n";
echo "              <tr><td><a href='officecreate.php'><img src='../images/buttons/done_button.png' border='0'></td></tr></table></td></tr>\n";
include '../footer.php'; exit;
}

if (isset($how_many)) {

echo "              <tr><td>How Many?</td><td class=table_rows colspan=2 
                      width=80% style='padding-left:20px;'>
                      <input type='hidden' name='how_many' value='$how_many'>$how_many</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";
echo "            <table class='table'>\n";

if ($how_many == '1') {

echo "              <tr><td height=40 class=table_rows colspan=2>You have chosen to create <b>$how_many</b> group within the 
                      <b>$post_officename</b> office. Please input the group name below.</td></tr>\n";
} elseif ($how_many > '1') {

echo "              <tr><td height=40 class=table_rows colspan=2>You have chosen to create <b>$how_many</b> groups within the 
                      <b>$post_officename</b> office. Please input the group names below.</td></tr>\n";
}

for ($x=0;$x<$how_many;$x++) {
$y = $x+1;
echo "              <tr><td>$y.&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type='text' size='25' maxlength='50' name='input_group_name[$y]'></td></tr>\n";
}
}
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td width=30><input type='image' name='submit' value='Create Dept' align='middle' 
                      src='../images/buttons/next_button.png'></td><td><a href='officeadmin.php'><img src='../images/buttons/cancel_button.png' 
                      border='0'></td></tr></table></form></td></tr>\n";
include '../footer.php'; exit;
}
}
?>
