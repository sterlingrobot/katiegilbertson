<?php
session_start();

include '../config.inc.php';
include 'header.php';
include 'topmain.php';
echo "<title>$title - Upgrade Database</title>\n";

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

$count = "0";
$tmp_count = "0";
$emp_tstamp_count = "0";
$info_timestamp_count = "0";
$passed_or_not = "0";
$gmt_offset = date('Z');

echo "<table class='table'>\n";
echo "  <tr valign=top>\n";
echo "    <td>\n";
echo "      <form name='form' action='$self' method='post'>\n";
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
echo "        <tr><td><a class='btn btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png'
                alt='Edit System Settings' />&nbsp;&nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table></td>\n";
echo "    <td align=left class=right_main scope=col>\n";
echo "      <table class='table'>\n";
echo "        <tr class=right_main_text>\n";
echo "          <td valign=top>\n";
echo "            <br />\n";

// determine the privileges of the PHP Timeclock user //

$result = mysqli_query($db, "show grants for current_user()");
while ($row = mysqli_fetch_array($result)) {
  $abc = stripslashes("".$row["0"]."");
  if (((preg_match("/\bgrant\b/i", $abc)) && (preg_match("/\bselect\b/i", $abc)) && 
     (preg_match("/\binsert\b/i", $abc)) && (preg_match("/\bupdate\b/i", $abc)) && 
     (preg_match("/\bdelete\b/i", $abc)) && (preg_match("/\bcreate\b/i", $abc)) && 
     (preg_match("/\balter\b/i", $abc)) && (preg_match("/\bon `$db_name`\.\* to '$db_username'@'$db_hostname|%\b/i", $abc))) ||
     (preg_match("/\bgrant all privileges on `$db_name`\.\* to '$db_username'@'$db_hostname|%' \b/i", $abc)) ||
     (preg_match("/\bgrant all privileges on \*\.\* to '$db_username'@'$db_hostname|%' \b/i", $abc))) { 
  $count++;}}
if (!empty($count)) { 

if ($request == 'GET') {

$query_admin = "select jobname from ".$db_prefix."jobs where jobname = 'admin'";
$result_admin = mysqli_query($db, $query_admin);

while ($row = mysqli_fetch_array($result_admin)) {
    $user_admin = "".$row["jobname"]."";
}

echo "            <form name='form' action='$self' method='post'>\n";
echo "            <table class='table'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/database_go.png' />&nbsp;&nbsp;&nbsp;Upgrade 
                      Database </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td colspan=2>If you are greeted with a
                      message in red stating \"Your database is out of date\", upgrade it by clicking on the \"Next\" button below. If 
                      you do not see this message, then your database is currently up to date and nothing further needs to be done.</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td colspan=2>In the process of  
                      upgrading the database, all necessary modifications and changes of the db will be completed, including any alterations, 
                      conversions, or additions that are needed for this release of PHP Timeclock to function properly.</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td colspan=2>Please click on the 
                      \"Next\" button below and follow the instructions, if any are given.</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table>\n";

if (!isset($user_admin)) {
    echo "            <table class='table'>\n";
    echo "              <tr><td><input type='checkbox' name='recreate_admin' value='1'></td>
                  <td>Re-create the admin user?</td></tr></table>\n";
}

echo "            <table class='table'>\n";

if (isset($user_admin)) {
    
}

echo "              <tr><td width=30><input type='image' name='submit' value='Upgrade DB' align='middle'
                      src='../images/buttons/next_button.png'></td><td><a href='index.php'><img src='../images/buttons/cancel_button.png'
                      border='0'></td></tr></table></form></td></tr>\n"; include '../footer.php'; exit;

} else {

@$recreate_admin = $_POST['recreate_admin'];

if (isset($recreate_admin)) {
    if (($recreate_admin != '1') && (!empty($recreate_admin))) {
        echo "Something is fishy here."; exit;
    }
}

echo "            <table class='table'>\n";
echo "              <tr><th colspan=3 class=table_heading_no_color nowrap align=left style='padding-left:25px;'>Upgrading Database......</th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";


// track the database changes that have been made since version 0.9 //

// jobs table additions //

$field = "employee_passwd";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field VARCHAR(25) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "displayname";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field VARCHAR(50) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "email";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field VARCHAR(75) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "groups";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field VARCHAR(50) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "office";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field VARCHAR(50) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "admin";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field TINYINT(1) NOT NULL default '0';");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "reports";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field TINYINT(1) NOT NULL default '0';");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "time_admin";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field TINYINT(1) NOT NULL default '0';");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

$field = "disabled";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."jobs LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs ADD $field TINYINT(1) NOT NULL default '0';");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

// jobs table changes //

$result = mysqli_query($db, "SHOW FIELDS FROM ".$db_prefix."jobs");
while ($row = mysqli_fetch_array($result)) {
  $name = "".$row["Field"]."";
  $type = "".$row["Type"]."";
  $tmp_type = strtoupper($type);

  if (($name == 'jobname') && ($type != 'varchar(50)')) {
    $alter_result = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs CHANGE jobname jobname VARCHAR(50) NOT NULL");
    echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                      align=left>:&nbsp;<b>$name</b> field in <u>jobs</u> table has been changed from type $tmp_type to type VARCHAR(50).</td></tr>\n";
    $passed_or_not = "1";
  }
  if (($name == 'tstamp') && ($type != 'bigint(14)')) {
    $alter_result = mysqli_query($db, "ALTER TABLE ".$db_prefix."jobs CHANGE tstamp tstamp BIGINT(14) DEFAULT NULL");
    echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                      align=left>:&nbsp;<b>$name</b> field in <u>jobs</u> table has been changed from type $tmp_type to type BIGINT(14).</td></tr>\n";
    $emp_tstamp_count++;
    $passed_or_not = "1";
  }
}
mysqli_free_result($result);

// info table additions //

$field = "ipaddress";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."info LIKE '".$field."'");
@$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."info ADD $field VARCHAR(39) NOT NULL;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>jobs</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

// info table changes //

$result = mysqli_query($db, "SHOW FIELDS FROM ".$db_prefix."info");
while ($row = mysqli_fetch_array($result)) {
  $name = "".$row["Field"]."";
  $type = "".$row["Type"]."";
  $tmp_type = strtoupper($type);

  if (($name == 'inout') && ($type != 'varchar(50)')) {
    $alter_result = mysqli_query($db, "ALTER TABLE ".$db_prefix."info CHANGE `inout` `inout` VARCHAR(50) NOT NULL");
    echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                      align=left>:&nbsp;<b>$name</b> field in <u>info</u> table has been changed from type $tmp_type to type VARCHAR(50).</td></tr>\n";
    $passed_or_not = "1";
  }
  if (($name == 'timestamp') && ($type != 'bigint(14)')) {
    $alter_result = mysqli_query($db, "ALTER TABLE ".$db_prefix."info CHANGE timestamp timestamp BIGINT(14) DEFAULT NULL");
    echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                      align=left>:&nbsp;<b>$name</b> field in <u>info</u> table has been changed from type $tmp_type to type BIGINT(14).</td></tr>\n";
    $info_timestamp_count++;
    $passed_or_not = "1";
  }
}
mysqli_free_result($result);

// punchlist table additions //

$field = "in_or_out";
$result = mysqli_query($db, "SHOW fields from ".$db_prefix."punchlist LIKE '".$field."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$passwd_query = mysqli_query($db, "ALTER TABLE ".$db_prefix."punchlist ADD $field TINYINT(1) NOT NULL default '0';");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$field</b> field has been added to the <u>punchlist</u> table.</td></tr>\n";
$passed_or_not = "1";
} 

// punchlist table changes //

$result = mysqli_query($db, "SHOW FIELDS FROM ".$db_prefix."punchlist");
while ($row = mysqli_fetch_array($result)) {
  $name = "".$row["Field"]."";
  $type = "".$row["Type"]."";
  $tmp_type = strtoupper($type);

  if (($name == 'punchitems') && ($type != 'varchar(50)')) {
    $alter_result = mysqli_query($db, "ALTER TABLE ".$db_prefix."punchlist CHANGE punchitems punchitems VARCHAR(50) NOT NULL");
    echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                      align=left>:&nbsp;<b>$name</b> field in <u>punchlist</u> table has been changed from type $tmp_type to type VARCHAR(50).</td></tr>\n";
    $passed_or_not = "1";
  }
}
mysqli_free_result($result);

// add metars table //

$table = "metars";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$metars_query = mysqli_query($db, "CREATE TABLE ".$db_prefix."metars (metar varchar(255) NOT NULL default '',
                             timestamp timestamp(14) NOT NULL, station varchar(4) NOT NULL default '',
                             PRIMARY KEY  (station), UNIQUE KEY station (station)) TYPE=MyISAM;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$table</b> table has been added to the <u>$db_name</u> database.</td></tr>\n";
$passed_or_not = "1";
} 

// add dbversion table //

$table = "dbversion";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$dbversion_query = mysqli_query($db, "CREATE TABLE ".$db_prefix."dbversion (dbversion decimal(5,1) NOT NULL default '0.0',
                             PRIMARY KEY  (dbversion)) TYPE=MyISAM;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$table</b> table has been added to the <u>$db_name</u> database.</td></tr>\n";
$passed_or_not = "1";
}

// dbversion table changes //

$table = "dbversion";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (!empty($rows)) {
  $dbversion_result = mysqli_query($db, "select * from ".$db_prefix."dbversion");
  while ($row = mysqli_fetch_array($dbversion_result)) {
    $tmp_dbversion = "".$row["dbversion"]."";
  }
  if (!isset($tmp_dbversion)) {
    $compare_result = mysqli_query($db, "INSERT INTO ".$db_prefix."dbversion (dbversion) VALUES ('".$dbversion."');");
    echo "                  <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                          align=left>:&nbsp;the version of the database is $dbversion.</td></tr>\n";
    $passed_or_not = "1";
  } elseif (@$tmp_dbversion != $dbversion) {
      $update_query = "update dbversion set ".$db_prefix."dbversion = '".$dbversion."'";
      $update_result = mysqli_query($db, $update_query);
    echo "                  <tr><td width=10 class=table_rows style='padding-left:25px;color:#0000FF;font-weight:bold;'>Changed</td><td class=table_rows 
                          align=left>:&nbsp;the version of the database has been changed from <b>$tmp_dbversion</b> to <b>$dbversion</b>.</td></tr>\n";
    $passed_or_not = "1";
  } 
}

// add offices table //

$table = "offices";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$metars_query = mysqli_query($db, "CREATE TABLE ".$db_prefix."offices (officename varchar(50) NOT NULL default '',
                             officeid int(10) NOT NULL auto_increment,
                             PRIMARY KEY  (officeid), UNIQUE KEY officeid (officeid)) TYPE=MyISAM;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$table</b> table has been added to the <u>$db_name</u> database.</td></tr>\n";
$passed_or_not = "1";
} 

// add groups table //

$table = "groups";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$metars_query = mysqli_query($db, "CREATE TABLE ".$db_prefix."groups (groupname varchar(50) NOT NULL default '',
                             groupid int(10) NOT NULL auto_increment,
                             officeid int(10) NOT NULL default '0',
                             PRIMARY KEY  (groupid), UNIQUE KEY groupid (groupid)) TYPE=MyISAM;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$table</b> table has been added to the <u>$db_name</u> database.</td></tr>\n";
$passed_or_not = "1";
} 

// add audit table //

$table = "audit";
$result = mysqli_query($db, "SHOW TABLES LIKE '".$db_prefix.$table."'");
$rows = mysqli_num_rows($result);

if (empty($rows)) {
$audit_query = mysqli_query($db, "CREATE TABLE ".$db_prefix."audit (modified_by_ip varchar(39) NOT NULL default '', 
                             modified_by_user varchar(50) NOT NULL default '',
                             modified_when bigint(14) NOT NULL, modified_from bigint(14) NOT NULL, 
                             modified_to bigint(14) NOT NULL, modified_why varchar(250) NOT NULL default '',
                             user_modified varchar(50) NOT NULL,
                             PRIMARY KEY  (modified_when), UNIQUE KEY modified_when (modified_when)) TYPE=MyISAM;");
echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                      align=left>:&nbsp;<b>$table</b> table has been added to the <u>$db_name</u> database.</td></tr>\n";
$passed_or_not = "1";
} 

if (isset($recreate_admin)) {

    if ($recreate_admin == '1') {

        // add admin user //

        $admin = "admin";

        $query_admin = "select jobname from ".$db_prefix."jobs where jobname = '".$admin."'";
        $result_admin = mysqli_query($db, $query_admin);

        while ($row_admin = mysqli_fetch_array($result_admin)) {
            $admin_user = stripslashes("".$row_admin['jobname']."");
        }

        if (!isset($admin_user)) {
            $add_admin_query = mysqli_query($db, "INSERT INTO ".$db_prefix."jobs 
                                            VALUES ('admin', NULL, 'xy.RY2HT1QTc2', 'administrator', '', '', '', 1, 1, 1, '');");

            echo "              <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Added</td><td class=table_rows 
                                  align=left>:&nbsp;<b>$admin</b> user has been added to the <u>$db_name</u> database.</td></tr>\n";
            $passed_or_not = "1";
        }
    }
}
 
// convert mysql timestamps to unix timestamps //

if (!empty($emp_tstamp_count)) {
$emp_tstamp_result = mysqli_query($db, "update ".$db_prefix."jobs set tstamp = (unix_timestamp(tstamp) - '".$gmt_offset."')");
$employee_rows= mysqli_affected_rows();

  if (!empty($employee_rows)) {
  echo "                <tr><td width=10 class=table_rows style='padding-left:25px;color:#FF9900;font-weight:bold;'>Converted</td><td class=table_rows 
                   align=left>:&nbsp;<b>$employee_rows rows</b> in the jobs table were converted from a mysql timestamp to a unix 
                   timestamp.</td></tr>\n";
  }
}
unset($emp_tstamp_count);

if (!empty($info_timestamp_count)) {
$info_timestamp_result = mysqli_query($db, "update ".$db_prefix."info set timestamp = (unix_timestamp(timestamp) - '".$gmt_offset."')");
$info_rows= mysqli_affected_rows();

  if (!empty($info_rows)) {
  echo "                <tr><td width=10 class=table_rows style='padding-left:25px;color:purple;font-weight:bold;'>Converted</td><td class=table_rows 
                   align=left>:<b>$info_rows rows</b> in the info table were converted from a mysql timestamp to a unix timestamp.</b></td></tr>\n";
  }
}
unset($info_timestamp_count);

if (empty($passed_or_not)) {
echo "              <tr><td><b>No changes were made to the 
                      database.</b></td></tr>\n";
} else {
echo "              <tr><td><b>Your database is now up to date.</b>
                      </td></tr>\n";
}
echo "            </table>\n";
echo "          </td>\n";
echo "        </tr>\n";
include '../footer.php'; exit;
}
} else {

echo "            <table class='table'>\n";
echo "              <tr><th class=rightside_heading nowrap halign=left colspan=3><img src='../images/icons/database_go.png' />&nbsp;&nbsp;&nbsp;Upgrade 
                      Database </th></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td colspan=2>Your mysql 
                      user, $db_username@$db_hostname, does not have the required SELECT, INSERT, UPDATE, DELETE, CREATE, and ALTER 
                      privileges for the $db_name database.</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "              <tr><td colspan=2>Return to this page after 
                      $db_username@$db_hostname has been granted these privileges on the $db_name database.</td></tr>\n";
echo "              <tr><td height=15></td></tr>\n";
echo "            </table></td></tr>\n";
include '../footer.php'; 
exit;
}
?>
