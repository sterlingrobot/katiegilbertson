Xataface Tagger Module v 0.3
Copyright (C) 2011  Steve Hannah <steve@weblite.ca>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Library General Public
License as published by the Free Software Foundation; either
version 2 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Library General Public License for more details.

You should have received a copy of the GNU Library General Public
License along with this library; if not, write to the
Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
Boston, MA  02110-1301, USA.



Synopsis:
=========

The Tagger module adds:
	1. A tagger widget so that you can add related records to a record via the edit tab using a simple tagging interface.
	2. An optional tag cloud block to be added in the left column of a template.
	
	
Requirements:
	
	Xataface 1.3.1 or higher.

Installation:
=============

1. Download the tagger module and copy the tagger directory into your modules directory.  (It can
   either go in your Xataface modules directory or your application modules directory.
   
Usage:
======

To allow editing related records as tags you would:

1. Add a transient field to the fields.ini file and set the following directives:

	i)   relationship : The name of the relationship that is to be edited.
	ii)  widget:type  : 'tagger'    This must be set to 'tagger'
	iii) transient    : 1   This must be set to 1
	iv)  tagger_label : (Optional) The name of the column in the relationship that should be 
		 treated as the label.
		 
That's it.


To show the frequency of of records containing a particular field value as a tag cloud,
simply add

tag_cloud=1 

To the field's definition in the fields.ini file.  This will cause a tag cloud to show 
up in the left column of the xataface interface.


Advanced Options:
=================

If you require special instructions on how tags should be added to the related table, you
can implement a fieldname__addTag() method to your table's delegate class with the following
signature:

function fieldname__addTag(Dataface_Record $record, string $value){

}

where $record is the source record of the relationship, and $value is the string tag that
is being added.

Here is an example where the tag is meant to use the people's full name, but the record actually
stores the name as first_name, and last_name (separate fields), so when people try to add a 
tag of a full name, we need to tell xataface how to deal with it:

function people__addTag($record, $value){
	list($first,$last) = explode(' ', $value);
	
	$rec = new Dataface_Record('people', array());
	$rec->setValues(array(
		'first_name'=>$first,
		'last_name'=>$last
	));
	return $rec;
}

In this example we split the value (which should be a full name) on a space, then set the
first and last name values in a record.  It returns a Dataface_Record in the 'people' table
which is the targer (domain) table of the relationship.



	
