<?php
/**
@see @ref template_publishing

@page setting_report_permissions Setting Report Permissions

You may have noticed that there is a @e Permission drop-down box on the <em>Edit Report</em> form.  This can be used to specify a permission that is required for users to access the report.  This is useful if you have a report that should only be available to specific users.  You can use Xataface's built-in permissions handling to decide which users can access your report.

The drop-down list includes all permissions that are currently loaded in to Xataface.  This is an aggregation of all of the loaded permissions.ini file permissions from Xataface, the loaded modules, and your application. 

@section leaving_permissions_blank Leaving Permission Blank

You don't need to specify any permission for your report.  If you leave the permission blank, then the report will be accessible to any user of the system.

@note The permission for a report is different that the permissions for the records that are displayed in the report.  The report permission merely dictates whether the user sees the button in the User Interface that they can click to run the report.  If they don't have access to view the information that is displayed in the report (as defined by Xataface table, record, and field-level permissions), then the report will simply contain a whole lot of blank fields or the phrase "NO ACCESS".

@section using_existing_permission Using An Existing Permission

Some common permissions you could set your report to would include:

-# @e view
-# @e list
-# @e edit

But you could define your own permission in your permissions.ini file and attach that permission as well.  It is up to you.

@see http://www.xataface.com/wiki/permissions.ini_file For information about the Xataface permissions.ini file
@see http://www.xataface.com/wiki/fieldname__permissions For information about defining field level permissions.
@see http://www.xataface.com/wiki/Relationship_Permissions For information about relationship permissions.

@see @ref template_publishing

*/
?>