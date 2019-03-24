Xataface DataGrid Module version 0.1
Created by Steve Hannah <steve@weblite.ca>

Synopsis:
---------

The Xataface DataGrid module uses the Ext DataGrid component (http://extjs.com) to add an editable grid component to your Xataface application.

Requirements:
-------------

PHP 4.3+
MySQL 4.1+
Xataface 0.8+

License:
--------

This module is distributed with ExtJS 2.2, which is distributed under the GPL v 3 (http://extjs.com/products/license.php).

In order to be compatible with the ExtJS license, this module is also distributed under the terms of the GPL v3 (http://www.gnu.org/copyleft/gpl.html).

Installation:
-------------

1. Download and extract the DataGrid directory into your xataface/modules directory.

2. Add the following line to the [_modules] section of your application's conf.ini file:

	modules_DataGrid=modules/DataGrid/DataGrid.php
	
3. Ensure that your permissions are set up appropriately to allow your users to access the grid action (see next section).

Setting Up Permissions:
-----------------------

This module defines the following permissions:

DataGrid:view_grid  	- Permission to view the data grid for a table.
DataGrid:create_grid	- Permission to create a new data grid
DataGrid:edit_grid		- Permission to edit an existing data grid
DataGrid:update			- Permission to update records via the grid
DataGrid:manage_grids	- Permission to access the datagrid control panel

In order for a user to access/use the grid he must be granted at least the
Datagrid:view_grid and DataGrid:update permissions.  Both of these permissions are included in the following system roles by default:

EDIT
EDIT AND DELETE
DELETE
ADMIN
MANAGER

And of course these permissions are included with the call to Dataface_PermissionsTool::ALL() .

If you have assigned your own custom roles and want to enable access to the grid, you can simply add the following to your role definition in your permissions.ini file:

[MY ROLE]
    DataGrid:view_grid=1
    DataGrid:update=1
    
If you want to explicitly disable the grid for a role, you can extend the role and deny those same permissions:

[MY ROLE extends MY ROLE]
	DataGrid:view_grid=0
	DataGrid:update=0
	

	
Usage:
------

Once installed, log in as a user that has permission to access the grid.  You should notice a new tab along with "details", "list", and "find", called "grid".
Click on the "grid" tab to access the grid.

You can double click on any field to edit it.  Modified fields will be marked in red, and automatically saved every 5 seconds - after the changes are saved the field is no longer marked in red.

Limitations:
------------

Currently only fields with the following widget types are available to be edited in the grid:

1. text
2. textarea
3. select
4. date/datetime/time

Other types of fields will simply not be included in the grid.

Support/Questions:
-------------------

Visit the Xataface forum at http://xataface.com/forum
	

