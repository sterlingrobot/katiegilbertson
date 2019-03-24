<?php
/**

@see @ref htmlreports_administration

@page module_permissions Permissions for the HTML Reports Module

The HTML Reports Module defines a number of permissions that control access to the management of reports.  These permissions are as follows:

<table>
<tr><th>Permission Name</th><th>Description</th></tr>
<tr><td>view private</td><td>Permission to view reports that are private and are not yours.</td></tr>
<tr><td>manage reports</td><td>Permission to manage reports (i.e. create new, edit, delete reports).</td></tr>
<tr><td>view schema</td><td>Report necessary to view a table's schema.  This is needed to use the field browser.  Generally this would always be granted along with the <em>manage reports</em> permission.</td></tr>
<tr><td>validate report</td><td>Permission to run validation on a report template to ensure that it doesn't contain syntax errors.  This is generally granted along with the manage reports permission.</td></tr>
<tr><td>preview report</td><td>Permission to use the Preview function for a report.</td></tr>
</table>

By default these permissions are not added to any roles.  Only users with @c Dataface_PermissionsTool::ALL() assigned to them will have permission to create reports.  You may grant these permissions to other users as well if you want to specifically give other users the ability to create reports. 

@see http://xataface.com/documentation/tutorial/getting_started/permissions
@see http://www.xataface.com/wiki/permissions.ini_file


@note The permissions to create and edit reports are separate from the permissions to view reports.  Permission to view a report is defined on the report edit form.

@see @ref setting_report_permissions
@see @ref htmlreports_administration




*/
?>