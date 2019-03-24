<?php
/**

@page setuptaggerwidget Setting Up the Tagger Widget

@see Before you can set up the tagger widget for use you need to install it.  See @ref installation for instructions.

Now that you have the tagger widget installed, you can use it just like any other widget in your application.

@section requirements Requirements

-# The tagger widget can only be used on transient fields.
-# Fields that use the tagger widget must also define a valid 'relationship' directive.
-# The relationship edited by the tagger widget *must* be a many-to-many relationship (i.e. you need to be able to add existing records to this relationship).

@see <a href="http://xataface.com/wiki/fields.ini_file">fields.ini file</a> for information on valid directives for fields (e.g. 'transient' and 'relationship').
@see <a href="http://xataface.com/wiki/relationships.ini_file">relationships.ini file</a> for more information about relationships.

@section examples Example Entry in fields.ini file:

@code
[countries]
    widget:type=tagger
    relationship=countries
    transient=1
@endcode

In this example we assume that the relationships.ini file defines a many-to-many relationship named countries.

@section customlabel Custom Label Column

By default Xataface will just try to guess which column of the target table should be used as the label for the tags if they appear in the tagger widget.  Generally it will pick the first varchar field it finds, so you may want to override this with a particular column that would be most appropriate.  

Use the "tagger_label" directive in the fields.ini file to specify this column.  For example, suppose our "countries" table is defined as follows:
@code
CREATE TABLE countries (
    varchar(3) country_code,
    varchar(100) country_name
    primary key (country_code)
)
@endcode

If this table were the target table of a tagger widget, then Xataface would probably try to use the "country_code" field as the source of the tag label.  But the "country_name" field would probably be more appropriate.  We can tell this to Xataface with the "tagger_label" directive:

@code
[countries]
    widget:type=tagger
    relationship=countries
    transient=1
    tagger_label=country_name
@endcode


Note that you can also specify grafted fields here, but you would need to also define an xxx__addTag() method in your source table's delegate class so that Xataface knows how to add tags in that case.  See the next section for more on this scenario.

@see <a href="http://xataface.com/wiki/Delegate_class_methods">Delegate class methods</a> for more information about table delegate classes (where you would implement the fieldname__addTag() method).


@section customadd Customized "Add Tag" Filter

When users enter tags into the system using the tagger widget, Xataface has only a single text string to insert into the related table.  The default behavior is for Xataface to create a new record and set this string as the value in the label column.  But sometimes it isn't that easy.  e.g.:

-# If you are using a grafted field for the label column (you can read but not write these).
-# If there are multiple required fields in the target table, then it won't be sufficient to just set a value in single field.

In these cases you will need to define the fieldname__addTag() method in your table delegate class. 

Suppose we have tables organizations, people, and organization_people (a join table), and the people table is defined as follows:
@code
CREATE TABLE `people` (
    person_id INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100)
)
@endcode

If we wanted to add people to an organization through the tagger widget we might start with a field definition in the organization's fields.ini file as follows:

@code
[people]
    widget:type=tagger
    relationship=people  ;; Assume we defined this in the relationships.ini file
    transient=1
@endcode

But there's a problem.  By default, Xataface will just choose the first_name field as the title field for a people record, and thus you will only be able to enter first names in the tagger widget.  What we probably want is for users to be able to enter the full name of a person in the tagger widget.  For this to happen we need to do two things:

-# Define full_name as a grafted field on the people table.
-# Specify full_name as the tagger_label for the field.

Defining the full_name grafted field (people/fields.ini):
@code
__sql__ = "select p*, concat(first_name,' ',last_name) as full_name from people p"

...
@endcode

Updating the tagger_label in the tagger widget (organizations/fields.ini):
@code
[people]
    widget:type=tagger
    relationship=people  ;; Assume we defined this in the relationships.ini file
    transient=1
    tagger_label=full_name
@endcode

<h3>But we have a problem still...</h3>

Xataface doesn't know how to add a tag to the database because the full_name field is read only.  If a user enters a user's full name, Xataface doesn't know which column it should go in.  Indeed a full name doesn't go in any one column; it goes into two columns.  We can solve this by defining the people__addTag() method in the organizations delegate class.  (Note that we use the name "people__addTag()" because the tagger field is named "people", not because the relationship or target table is named "people".

@code
function people__addTag($record, $value){
	list($first,$last) = explode(' ', $value);
	
	$rec = new Dataface_Record('people', array());
	$rec->setValues(array(
		'first_name'=>$first,
		'last_name'=>$last
	));
	return $rec;
}
@endcode

And we're ready to let it roll on its own.

@see @ref usingtaggerwidget for user instructions for using a tagger widget.
*/