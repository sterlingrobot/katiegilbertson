<?php
/**

@see @ref getting_started

@page addingfields Adding Dynamic Fields to a Template

In the @ref helloworld "previous example" we created a rather useless report template.  It does nothing except print "Hello World" once for each record in a found set.  In this section we'll expand on this example to turn out report into something a little bit more useful.

@section macro_insert_field Placeholder Macros

Before getting into the features of the WYSIWYG editor, let's take a quick look at how the report generator parses fields embedded in templates.  

Suppose you have a field named @c first_name in the table that is the subject of a report.  Then you can embed the value of the @c first_name field into a report by adding @c {$first_name} to the template.  E.g.
@code
<p>First Name: {$first_name}</p>
@endcode
would be rendered as 
@code
<p>First Name: Steve</p>
@endcode

when run on a record whose @c first_name field contained the value "Steve".

In order to assist you in creating your templates, the HTML Reports module includes a field browser that allows you to browse through the fields in your table and add them to your template by selecting them with your mouse.  Read on for details on how to add fields using this method.

@section wysiwyg_insert_field Using the WYSIWYG Editor To Insert A Field

@subsection selectingtable Selecting A Table

The @e Tablename dropdown list on the report form allows us to specify the table on which this report is meant to run.  In my particular case, I'm going to create a report to run on my @e Tools table.  You can select any table you like for your example.

@subsection insertingfield Inserting a Field

We left off the last example with a nearly blank template with only the text "Hello World".  Now we're going to change it slightly.  I Want the template to say "Hello {$tool_name}" where {$tool_name} is the name of a tool.  When the report is run on a set of tool records, I want to to have one line per tool in the found set.

Steps:

-# Click the cursor inside the HTML editor, and delete the text "World", so that it only says "Hello" now.  Make sure that text carat is placed just after "World ".
-# Click the <em>Insert Field</em> button in the editor toolbar.  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.32.20_AM.png?max_width=640"/>  This will open up a field browser dialog box as a tree widget.  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.33.39_AM.png?max_width=640"/> Expand the <em>Fields</em> node to reveal the fields that are available to add to your template.  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.33.47_AM.png?max_width=640"/>  Click on a field to add it to your template.  You should see a text placeholder for that field show up in your template. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_4.01.51_PM.png?max_width=640"/>
-# Click the <em>Preview Report</em> button in the editor toolbar again so see what our report looks like now.  Now you should see the value of the field that you added written for each of the first 10 records in the table.  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.35.49_AM-1.png?max_width=640"/>

@subsection exercise Exercise

Try adding some more fields to your template and then preview it.  See how "useful" you can make it look

@subsection next What's Next

We still haven't saved our template.  We'll finally get a chance to do that in the next section: @ref publishing "Publishing Reports for Other Users"


@see @ref getting_started

*/
?>