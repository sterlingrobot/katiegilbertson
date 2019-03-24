<?php
/**

@see @ref getting_started

@page helloworld Creating Your First Report

Once you have successfully @ref installation "installed the HTML Reports Module", you can proceed to start creating report templates.

In this section we will create a simple Hello World report.  All it will do is print out "Hello World" once for each record in the found set.  It won't use any dynamic, we will cover that in the @ref addingfields "next section: Adding Dynamic Fields".

Follow these steps to begin:

-# Log in to your Xataface application as an administrator (i.e. a user with ALL() permissions granted to them.
-# Click the "Control Panel" link in the upper right to access the application control panel. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.16.05_AM.png?max_width=640"/>
-# Click "Manage Reports".  This will take you to the list view for the reports table.  At first this will be empty.
-# Click "New Record".  This will bring up the new record form for a report. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.16.58_AM.png?max_width=640"/>
-# In the @e Tablename dropdown list, select any table that you want to run the report on.
-# In the <em>Template HTML</em> field, enter "Hello World"
-# Click the <em>Preview Report</em> button on the editor toolbar (one of the lower right buttons).  <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.30.17_AM.png?max_width=640"/> You'll see "Hello World" just printed out 10 times, one after another (if the table you selected in the <em>Tablename</em> dropdown has at least 10 records in it).  This is because the preview function just shows you the result of the report when run on the first 10 records in the table. <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-10_at_11.30.30_AM.png?max_width=640"/>.


This section provided a simple example with a very static and boring template.  We haven't even saved our template yet - all we have done is previewed it.  This is because there are a few key items that we need to specify before we save our template (e.g. the report name and label).  We'll go over these options later in the @ref publishing "publishing" section, but first we're going to have some more fun just working with the preview function to see samples of our results.

@ref addingfields "Next: Adding Dynamic Fields to Our Template"

@see @ref getting_started


*/
?>