<?php

echo "<div class='row'>\n";
echo "		<div class='col-md-2'>\n";
echo "      <table cellpadding=5>\n";
include 'userinfo.php';
echo "        <tr><th>Jobs</th></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='useradmin.php'><img src='../images/icons/user.png' alt='Job Summary' />&nbsp;&nbsp;Job Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='usercreate.php'><img src='../images/icons/user_add.png' alt='Create New Job' />&nbsp;&nbsp;Create New Job</a></td></tr>\n";
if(in_array($self, $user_functions )) :
  echo "        <tr><td><a class='btn btn-default' href=\"useredit.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Edit Job' />&nbsp;&nbsp;Edit Job</a></td></tr>\n";
  echo "        <tr><td><a class='btn btn-default' href=\"chngpasswd.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Change Password' />&nbsp;&nbsp;Change Password</a></td>
				  </tr>\n";
  echo "        <tr><td><a class='btn btn-default' href=\"userdelete.php?username=$post_username&officename=$get_office\"><img src='../images/icons/arrow_right.png' alt='Delete Job' />&nbsp;&nbsp;Delete Job</a></td></tr>\n";
endif;
echo "        <tr><td><a class='btn btn-block btn-default' href='usersearch.php'><img src='../images/icons/magnifier.png' alt='Job Search' />&nbsp;&nbsp;Job Search</a></td></tr>\n";

echo "        <tr><th>Depts</th></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='officeadmin.php'><img src='../images/icons/brick.png' alt='Dept Summary' />&nbsp;&nbsp;Dept Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='officecreate.php'><img src='../images/icons/brick_add.png' alt='Create New Dept' />&nbsp;&nbsp;Create New Dept</a></td></tr>\n";

echo "        <tr><th>Groups</th></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='groupadmin.php'><img src='../images/icons/group.png' alt='Group Summary' />&nbsp;&nbsp;Group Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='groupcreate.php'><img src='../images/icons/group_add.png' alt='Create New Group' />&nbsp;&nbsp;Create New Group</a></td></tr>\n";

echo "        <tr><th>In/Out Status</th></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='statusadmin.php'><img src='../images/icons/application.png' alt='Status Summary' />&nbsp;&nbsp;Status Summary</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='statuscreate.php'><img src='../images/icons/application_add.png' alt='Create Status' />&nbsp;&nbsp;Create Status</a></td></tr>\n";

echo "        <tr><th>Miscellaneous</th></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='timeadmin.php'><img src='../images/icons/clock.png' alt='Add/Edit/Delete Time' />&nbsp;&nbsp;Add/Edit/Delete Time</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='sysedit.php'><img src='../images/icons/application_edit.png' alt='Edit System Settings' />&nbsp;&nbsp;Edit System Settings</a></td></tr>\n";
echo "        <tr><td><a class='btn btn-block btn-default' href='dbupgrade.php'><img src='../images/icons/database_go.png' alt='Upgrade Database' />&nbsp;&nbsp;&nbsp;Upgrade Database</a></td></tr>\n";
echo "      </table>\n";
echo "     </div>\n";
?>
