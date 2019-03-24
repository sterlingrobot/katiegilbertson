<?php
/**

@see @ref template_creation

@page calculated_fields Using Calculated Fields

This section is just inserted as a reminder that you can use Xataface's ability to define calculated fields in a table's delegate class if the fields available to you are insufficient for your report purposes. 

@see http://xataface.com/wiki/field__fieldname For more information about calculated fields.

Calculated fields can be added to templates the same way that regular fields can be added.  There is no difference in the placeholder macro.  E.g. A template containing @c {$year} makes no distinction to whether @c year is a regular field or a calculated field.  It will display it regardless.

@section wysiwyg_calculated_field Adding Calculated Fields with the WYSIWYG Editor

Adding calculated fields through the WYSIWYG editor is almost the same as adding normal fields.  The only difference is that you should expand the <em>Calculated Fields</em> node in the field browser instead of the <em>Fields</em> node.  All of the calculated fields for the table are listed under there.

@see @ref insertingfield

@see @ref template_creation
*/
?>