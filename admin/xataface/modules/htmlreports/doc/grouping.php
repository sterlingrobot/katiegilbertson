<?php
/**
@see @ref template_creation

@page grouping Grouping Reports

Reports can also be grouped based on the value of specific columns.  By creating a header or footer section in your template with a field macro will effectively cause the report, when run, to be grouped based on the fields in the headers and footers.  Summary calculation fields can also be placed in header and footer sections to display summary results for particular columns (e.g. sum, count, avg, etc..).

Multiple levels of groups are supported also.  E.g. By having 2 header sections, a report would be grouped on the fields in the first heading - then within each group, they would be separated into subgroups based on the fields in the second heading.  This could be used, for example, to group transactions by Year, and also by category within each year.

@section html_grouping A Look At the HTML Source

Before getting into the WYSIWYG features or grouping, let's take a look at the HTML that the report generator looks for when determining groups.

@section html_section_headers Section Headers

Any @c div tag in the template with a @c xf-htmlreports-section-header class will be treated as a section header. E.g.:

@code
<div class="xf-htmlreports-section-header">
	My Report Title
</div>
@endcode

A section header that contains no field placeholder macros is effectively a report header.  I.e. the report generator will display this section once only at the beginning of the report.

@subsection html_group_headers Grouping Records

If the section header contains a field placeholder macro, then this field will be used to group the records in the report, and this header will be displayed before each group.  For example, if our table had a field @c year and we wanted out report to separate the records into years, and place a heading to mark the beginning of a year you could have a section header as follows:

@code
<div class="xf-htmlreports-section-header">
    Year: {$year}
</div>
@endcode

@subsection html_sub_groups Sub Groups

It is also possible to have subgroups by defining multiple section headers. 

@see @ref subgroups

@subsection html_summary_fields Summary Fields

The module also supports summary fields to display totals in section headers and footers.

@see @ref summary_fields

@section html_section_footers The Section Footers

Any @c div tag in the template with a @c xf-htmlreports-section-footer class will be treated as a section footer. 

e.g.
@code
<div class="xf-htmlreports-section-footer">
	My Report Footer
</div>
@endcode

Headers and footers are grouped into matching pairs.  If you are using multiple subgroups, you should make sure that there are an equal number of header sections as there are footer sections.  Everything you can do inside a header section, you can also do inside a footer, and vice versa.


@section wysiwyg_grouping Adding Headers and Footers with the WYSIWYG Editor

Adding section headers and footers using the WYSIWYG editor can be accomplished with the <em>Add Section Header</em> <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.00.54_AM.png?max_width=640"/> and <em>Add Section Footer</em> <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.01.04_AM.png?max_width=640"/> buttons.

Clicking the <em>Add Section Header</em> button will add a section to the top of your template.  In the editor it will be marked clearly.  The resulting reports won't include the dotted border or the icon in the top left of the section. 

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.01.15_AM.png?max_width=640"/>

Similarly, clicking the <em>Add Section Footer</em> button will add a section to the bottom of your template.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.01.30_AM.png?max_width=640"/>

@subsection headerfooter_example An Example with Section Headers

For this example we'll expand our @ref helloworld "Hello World" example from earlier by adding a section header to group our tools list by tool type.

We begin by opening our <em>Hello World</em> template for editing.  Then we click the <em>Add Section Header</em> toolbar button <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.13.01_AM.png?max_width=640"/> to add a section header.



Let's add the @c tool_type field to the header section.  First we click the cursor inside the section header that we added, and click the <em>Insert Field</em> button on the toolbar to browse through the fields in our table.

Expand the @e Fields node and select the <em>Tool Type</em> field.  This will add the @c {$tool_type_id} placeholder inside our section header.  We'll expand this by prefixing the text "Tools of Type " so that the report makes sense.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.11.51_AM.png?max_width=640"/>

We'll click on the @e Preview button to see what this report will look like with the first 10 records of our set.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.12.12_AM.png?max_width=640"/>

It looks OK, but it is difficult to differentiate the section headers from the section bodies since they are both using the same size font.

Next we'll try to make the font of the section header into bold font.  We'll use the <em>Paragraph Format</em> drop-down for this.  Select the text in the section header, and then select "Heading 1" from the <em>Paragraph Format</em> drop down.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.12.49_AM.png?max_width=640"/>

@note There is currently a bug/annoyance in the editor that causes the section header section to be removed when the font is changed in the heading text.  Notice that the section heading dotted border and icon disappeared when the font was changed.  We need to add it back by clicking the <em>Add Section Header</em> button, then selecting and dragging our text back into the new, empty section header.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.13.31_AM.png?max_width=640"/>

Now that we have our template looking the way we want, we'll try to preview it again. 

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.13.48_AM.png?max_width=640"/>


Now the section headers are differentiated from the section bodies nicely.

@section table_preview_grouping Grouping with the Table View

So far we have been using the <em>list view</em> for all of our examples.  The <em>Table View</em> can also be used with grouping.  In this case it will still group the records based on the fields in the section headers and footers, but the record lists will be tabular with rows being records in the found set, and columns corresponding to the fields listed in the template body, appearing in the order in which they are declared.

Clicking on the <em>Preview Report as Table</em> button in the toolbar <img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.51.58_AM.png?max_width=640"/> will show us a sample of what the table view will look like.  In our case (from the example above) our table will only have one column because the template body only contained a single column.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-08-11_at_11.51.42_AM.png?max_width=640"/>


@section grouping_summary Summary

The HTML Reports module includes some powerful header and footer capabilities that can be used to group records in a report together.  This section demonstrated how to create section headers and footers and how adding fields to those sections resulted in grouping reports into separate groups and subgroups.

Subsequent sections expand on this technique by adding @ref summary_fields "summary fields" and multiple levels of headers and footers to produce @ref subgroups "sub-sections" also.


@see @ref template_creation
@see @ref subgroups
@see @ref summary_fields




*/
?>