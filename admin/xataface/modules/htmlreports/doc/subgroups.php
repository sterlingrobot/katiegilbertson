<?php
/**

@see @ref template_creation

@page subgroups Multiple Headers / Subgroups

In a @ref grouping "previous section" we demonstrated the use of section headers and footers as a means of partitioning reports into groups.  Here we extend this concept to handle multiple levels of headers and sub groups.

The HTML Reports module allows you to add multiple header and footer sections to a template.  These are treated as pairs when it comes to groupings.  The first header section is associated with the last footer section.  The 2nd header section with the 2nd to last footer section, etc...  This allows you to define multiple levels of subgroupings in your templates.

@section html_subgroups A Look at the HTML for Subgroups

Consider the example where we have a table of transactions and we want to group them by year and month.  Assume that the table has a @c year field and a @c month field (in reality most tables like this would just have a single @c date or @c datetime field for this purpose - so you would probably need to create calculated fields for year and month and use those for grouping instead).

Our template might look something like this:

@code
<div class="xf-htmlreports-section-header">
	<h1>Company Transaction History Report</h1>
	<div style="page-break-after: always;"> 
</div>
<div class="xf-htmlreports-section-header">
	<h2>{$year}</h2>
</div>
<div class="xf-htmlreports-section-header">
	<h3>{$month}</h2>
</div>

Transaction ID: {$transaction_id} <br/>
Description: {$description} <br/>
Amount: {$amount} <br/>

<div class="xf-htmlreports-section-footer">
	&nbsp;  <!-- month footer -->
</div>
<div class="xf-htmlreports-section-footer">
	<div style="page-break-after: always;"> 
	<!-- year footer -->
</div>
<div class="xf-htmlreports-section-footer">
	End of Report
	<!-- report footer -->
</div>
@endcode

@note We made use of the CSS @c page-break-after attribute to try to define how this report will be rendered when it is sent to the printer.

The first header section contains no fields in it so it is printed only once at the beginning of the document.  This is effectively treated as the cover page.

The second header section marks the beginning of a new year.  Note that its corresponding footer (the 2nd last footer) includes a page break so each year will start on a new page when printed on a printer.

The third header section marks the beginning of a new month.

We could have gone further and broken the report down by day, but you get the idea.

@section wysiwyg_subgroups Using the WYSIWYG Editor for Subgroups

Adding subgroups in the WYSIWYG editor follows the same procedure as described on the @ref grouping "Grouping" section.  You just add more sections.  Remember to keep your headers and footers paired.

@see @ref template_creation
@see @ref grouping
@see @ref summary_fields


*/
?>