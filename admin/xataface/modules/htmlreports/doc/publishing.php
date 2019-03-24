<?php
/**

@see @ref getting_started

@page publishing Publishing Reports for Other Users

So far we have refrained from saving our report and have instead just been using the report preview function.  This is not because saving a report template is complicated (it's not - you just click save).  It is rather because I want to go over what each of the fields on the template creation form does.

@section overview Overview of How Publishing Works

The HTML Reports module makes use of Xataface's Dataface_ActionTool class to help embed reports into the application's user interface.  Dataface_ActionTool keeps an inventory of all of the actions defined in the application (via actions.ini files) and makes these actions available in different contexts.  Ultimately most of these actions are manifested as buttons or links somewhere in the user interface of the application.

@see http://xataface.com/documentation/tutorial/getting_started/dataface_actions "Xataface Actions: The Basics"
@see http://xataface.com/documentation/tutorial/getting_started/dataface_actions_2 "Xataface Actions 2"
@see http://xataface.com/wiki/actions.ini_file "actions.ini file reference"

@subsection action_categories Action Categories

Actions can be grouped by their @e category directive.  This simple directive allows the Action Tool to determine which actions should show up in which section of the user interface.  For example, actions with the @e table_tabs category, will appear as a tab along with @e details, @e list, and @find, etc... whereas if an action has a category of "result_list_actions", it will be displayed in the upper right of the list view along with the @e "Export CSV" and @e "Export XML" actions.

Some of the more common action categories and their corresponding purposes are listed below:

<table>
<tr><th>Category</th><th>Render Location</th></tr>
<tr><td>table_tabs</td><td>The tabs wrapping around the table options.  E.g. @e find, @e list, @details.</td></tr>
<tr><td>table_actions</td><td>The links/buttons that appear just below the table tabs.  These actions generally operate on the table or the entire found set.  E.g. @e "New Record", @e "Update Set", @e "Delete Set", etc..</td></tr>
<tr><td>result_list_actions</td><td>Displayed before and after the list view.  These are intended to either operate on the result set or present a different view of the data presented in the result set.  E.g. @e "Export CSV", @e "Export XML", @e "RSS Feed", etc..</td></tr>
<tr><td>record_actions</td><td>Displayed just under the record tabs in the @e Details tab.  These are intended to operate on a single record or present a different view of the current record.  E.g. @e "Export Record as XML", @e "RSS of Current Record"</td></tr>
<tr><td>related_list_actions</td><td>Similar to the result_list_actions except these are displayed before and after related record lists.</td></tr>
<tr><td>record_tabs</td><td>The tabs that are displayed in details mode for each record.  E.g. @e View, @e Edit, and the relationship tabs.</td></tr>
<tr><td>personal_tools</td><td>The personal management links accessible to the user.  Usually displayed in the top right corner of the interface.  E.g. @e "My Profile", @e "Control Panel", etc...</td></tr>
<tr><td>management_actions</td><td>Actions that appear in the control panel.</td></tr>
<tr><td>selected_result_actions</td><td>Actions that can act on selected rows in list view.  E.g. @e "Update Selected", @e "Delete Selected", @e "Copy Selected", etc...  @note As of version 0.1 HTML Reports reports cannot be used with this category.  If you set a report to this category, it just won't work correctly.  Future releases will support this category.</td></tr>
<tr><td>selected_related_result_actions</td><td>Similar to @e selected_result_actions except that this operates on related lists.</td></tr>
</table>


@section actionname Setting the Report Name

The @e "Report Name" field is where you specify a unique ID for this report (as far as the Action Tool is concerned.  This should contain only letters, numbers, and underscores (i.e. no spaces or special characters).  If you do not specify a report label, then this value will be used as the label also.

<b>For our continued @e "Hello World" example, we'll just insert "hello_world" here.</b>

@section actionlabel Setting the Report Label

The @e "Report Label" field stores the human readable label that will be associated with your report.  This is what will be displayed in the user interface for your report.  This value is not subject to the same constraints as the @e "Report Name" field.  It can contain any character.

<b>For our example, we'll enter "Hello World" here.</b>


@section actioncategory Setting the Report Category

The @e "Report Category" field is where we specify the action category for our report (i.e. where it will be displayed in the user interface).  <b>For this example, let's select "table_actions".</b>

@section defaultview Setting the Default View

There are 3 possible formats that can be used to render reports using your template:

-# <b>List</b> - Will render the template once for each record in the found set.  Records are displayed one after another as a list.
-# <b>Table</b> - Doesn't use the template per se.  It renders a table using the columns that are involved in the template - one row per record in the found set.  Columns are arranged in the order in which they appear in the template.
-# <b>Details</b> - This is the same as the List format except that it only displays a single record at a time.

<b>For our initial example, we are going to select @e List</b>

@section saving Saving and Testing

Finally we can save our report.  <b>Simply click the @e Save button at the bottom of the form</b>.

Now we can navigate to the table in our application that we chose in the "Tablename" field of our report template.  You should notice a new option amongst the table actions (next to "New Record") called "Hello World".

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.02.38_PM.png?max_width=640"/>

Click on this link, and you should see the current found set rendered using the template we just created.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.02.45_PM.png?max_width=640"/>

@section whatnow What's Next?

@see @ref getting_started
@see @ref template_creation

*/
?>