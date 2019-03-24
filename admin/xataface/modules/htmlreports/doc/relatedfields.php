<?php
/**

@see @ref template_creation

@page relatedfields Adding Related Records to Reports

Adding related fields to a template is the same as adding native fields.   You just select the @e "Insert Field" button on the editor toolbar, expand the @e Relationships node, then expand the relationship whose field you want to add.  Then just click any field and it will be added to your template.

You'll notice that the placeholder for the field is in the format 
@code
{$relationship.field}
@endcode

If you click the @e Preview button, you should see your report now includes data from your related record.

In this simple case only a value from the @e first related record is displayed.  All subsequent records are omitted.  Hence this simple method works great if either the relationship only has one record in it - or if you are only interested in showing the first record.  But most of the time when you are designing templates that display related field information, you actually want all of the related records' values to be shown.   And usually in some sort of list or table.

@section relatedtable Creating a "Related" Table

In order to build an understanding of how the system works, we will begin by looking at the HTML source code for a template directly.  You can view the HTML source in the template editor by clicking the @e Source button in the upper left.  Click it again to toggle back to WYSIWYG mode.  Later we'll see how to define these same constructs using the WYSIWYG editor without resorting to HTML directly.

Displaying a list of related values is actually quite easy.  The report generator will look for a @e relationship attribute in @c table, @ol, and @ul tags.  If it finds such an attribute it will render each row of the table body, or list once for each related record.  Generally, then you would just create a table with a header and a single row in the body, and the report generator would know to re-render the single body row once for each related record.

<b>Example</b>:

@code
<table relationship="Ingredientes">
<thead>
	<tr>
		<th>Ingredient Description</th>
		<th>Cost Per kg</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>{$Ingredientes.ing_descr}</td>
		<td>{$Ingredientes.cost1}</td>
	</tr>
</tbody>
</table>
@endcode


In this example we have an HTML table that specifies that is should be used to render records from the @e Ingredientes relationship.  The @c thead tag defines the heading.  The @c tbody tag defines only a single row, but notice that this row contains placeholders for fields of the @c Ingredientes relationship.  The report generator will loop through each record in the relationship when this report is run and render this row definition once for each record.

@section relatedlist Creating a "Related" List

Creating a related list is almost the same as a related table.  The only difference is that you use @c ol and @c ul tags and these have no headers as a @c table tag does.  

E.g.


@code
<ul relationship="Ingredientes">
    <li> {$Ingredientes.ing_descr} : Cost Per kg {$Ingredientes.cost1}</li>
</ul>
@endcode

This produces the ingredients as an unordered list.  Using an @c ol tag would have produced an ordered list.

@section wysiwygtable Using the WYSIWYG Editor

Now that we can see the underpinnings of how related lists and tables work, we will learn how to add them using the WYSIWYG editor - skipping the HTML entirely.

Open the edit form for your report template and ensure that the HTML widget is left in WYSIWYG mode (i.e. not source mode).  

-# Click in the editor to set the caret where you would like the table to be inserted.  Then click on the @e Table button in the editor toolbar.  This shoudl open a dialog as shown. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.24.17_PM.png?max_width=640"/>
-# We are going to create a table with 2 rows and 2 columns, so enter "2" in the @e Rows field and "2" in the @e Columns field.  <b>Don't click OK yet</b>.
-# We want the top row to actually be a header, so in the @e Header drop-down list, select "First Row".
-# Click the @e OK button.  This should insert an empty 2x2 table into the editor.
-# Enter values for the header cells. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.26.09_PM.png?max_width=640"/>
-# With the caret somewhere in the table (either the header or the body cells), click the <em>Set Relationship</em> button in the toolbar. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.26.24_PM.png?max_width=640"/> This will pop up a small dialog box with a drop-down whose options are the relationships in the template's table (as specified by the @Tablename field).  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.26.37_PM.png?max_width=640"/>
-# Select the relationship that you want this table to display.  Then close the dialog box.
-# Finally, we populate the body of the table.  Click in the first cell of the body, then click the <em>Insert Field</em> toolbar button.  This will open the field browser. 
-# Expand the @e Relationships node, then expand the node of the relationship that this table is to display.  Finally select a field.   This will insert a placeholder macro for that field in the table cell.  Do the same for the 2nd column. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.27.23_PM.png?max_width=640"/>

Let's check the Preview (i.e. click the <em>Preview List</em> button).

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.27.55_PM.png?max_width=640"/>

Presently since the report consists solely of a table of related records, the preview just shows a bunch of tables stacked on top of each other.  In our case it is just a list of ingredients - but it doesn't say anything about which record those ingredients are related to (i.e. the source record).  Let's add a heading to our template to show the name of the recipe. 

-# Click in the editor before the related table that we inserted earlier, and press @c Enter to free up some space. 
-# Now, with the caret on a line of its own at the top of the editor, click on the <em>Insert Field</em> toolbar button.  This will open the field browser dialog.
-# Expand the @e Fields node and select a field that would work well as a title.  You should see this field added to the editor as a placeholder macro at the top of the page.
-# Let's make the font on this title a little bigger so that it stands out.  Select "Heading 1" from the @e Format menu (it likely just says "Normal" by default).  This should make the type bold and big.  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.28.30_PM.png?max_width=640"/>

Now let's check the preview.  It should look something like the following, with headings followed by a list of related records.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.28.43_PM.png?max_width=640"/>


@section troubleshooting Troubleshooting


The following are some of the common problems that may be encountered when setting up related tables and lists in a template.

@subsection onerowonly My Report Only Shows One Row For My Related Table

If your related tables are only being rendered as a single row when the report is run, it usually means that the @c relationship attribute has not been correctly applied to the @c table (or @c ul or @ol) tag.  You can double check this by clicking the @e Source button on the editor toolbar to view your template HTML directly.  The @c table tag should have a @c relationship attribute set to your relationship name.  If it does not, that means that you need to either add it manually, or return to WYSIWYG mode, click the mouse in the table and click the <em>Set Relationship</em> button on the editor toolbar - then select the appropriate relationship.

@subsection allrowssame  My Related Tables Have Multiple Rows All With The Same Content

This can happen if the related fields in your table's body cells come from a different relationship than the relationship that is set for the table.  Ensure that the relationship set for the table is the same as the relationship from which the fields in your body row originate from.

@section forfieldgroupings Field Grouping

The related lists and tables feature of templates is very useful for being able to display related details of the subject records for your reports.  It may be tempting to use this feature as a means of grouping records together for reports.  Indeed the example in this section almost looked like a report on the Ingredients table grouped by recipe.  However it is better to use the @ref grouping "grouping feature of the HTML Reports Module" for grouping reports as it provides many more features in this area.  


@section whatsnext What's Next?

@ref grouping "Grouping Reports"

@see @ref template_creation



*/
?>