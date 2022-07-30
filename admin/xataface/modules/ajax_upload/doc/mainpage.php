<?php
/**
@mainpage AJAX Upload Module


<img src="http://media.weblite.ca/files/photos/ajax_uploader.png?max_width=640"/>

<img src="http://media.weblite.ca/files/photos/lightbox.png?max_width=640"/>

@section synopsis Synopsis

This module adds a file upload widget to Xataface that uses AJAX with @e widget:type=ajax_upload.


@section license License

@code
Xataface AJAX Upload Module
Copyright (c) 2012, Steve Hannah <shannah@sfu.ca>, All Rights Reserved

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
@endcode


@section features Features

- Adds the ajax_upload widget type for use in a Xataface application.
- Will work with any container field (i.e. file upload fields where the files
	are stored on the file system).
- AJAX uploading of files.
- Will work inside grid widget (so you can now have unlimited file uploads to a form).
- LightBox preview of images.
- Progress bar to track upload progress.
- Delete or replace files in field.

@section requirements Requirements

Xataface 2.0 or higher

@section download Download

@subsection packages Packages

None yet

@subsection svn SVN

<a href="http://weblite.ca/svn/dataface/modules/ajax_upload/trunk">http://weblite.ca/svn/dataface/modules/ajax_upload/trunk</a>

@section installation Installation

-# Copy the ajax_upload directory into the modules directory of either your application or Xataface.
-# Add the following to the [_modules] section of your conf.ini file: @code
modules_ajax_upload=modules/ajax_upload/ajax_upload.php
@endcode

@see http://xataface.com/wiki/modules For more information about Xataface module development and installation.

@section usage Usage

This module provides the ajax_upload widget type that can be used in place of the file
widget.  E.g. If you have a field that you used to use for file uploads with configuration
like:

@code
[myfield]
	Type=container
@endcode
	
	
You can change it to use the ajax_upload widget by adding:
@code
widget:type=ajax_upload
@endcode

to the fields.ini configuration.  E.g.

@code
[myfield]
	Type=container
	widget:type=ajax_upload
@endcode
		


@section support Support

<a href="http://xataface.com/forum">Xataface Forum</a>




*/
?>