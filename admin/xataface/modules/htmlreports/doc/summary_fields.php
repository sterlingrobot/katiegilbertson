<?php
/**

@see @ref template_creation

@page summary_fields Summary Fields

It is often helpful in reports to display totals that summarize entire sets of records with a single value.  For example, when running a report on sales transactions, you are often interested in the total amount of the transactions.  This is where summary fields are useful.

@section html_summary_fields HTML View of Summary Fields

Summary fields are represented in a template in the following format:
@code
{@operation(field)}
@endcode
where @c operation is one of 

- @c count
- @c sum
- @c min
- @c max

and @c field is the name of a field in the table (either related field or primary field).

E.g.

@code
{@sum(amount)}
@endcode


@section summary_field_rules Summary Field Rules

-# Summary fields should only be added to section headers and footers.  They don't make sense in the body of a template.

@see @ref html_section_headers

@section wysiwyg Adding Summary Fields Using the WYSIWYG Editor

An easier way to add summary fields is to just use the field browser (i.e. clicking the <em>Insert Field</em> button). 

@see @ref insertingfield

If you exand any field node, you'll see a number of sub-nodes corresponding to summary operations (e.g. @e sum, @count, etc...).  Click on one of these sub-options to add the corresponding summary field to your template.  That's all that is involved.

@see @ref template_creation

*/
?>