<?php
/**
@see @ref getting_started

@page installation Installation


@section license License

@code
Xataface HTML Reports Module

Copyright (C) 2011  Steve Hannah <shannah@sfu.ca>

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


@section requirements Requirements

-# Xataface 2.0 or higher (or SVN rev 2500 or higher)
-# The Xataface <a href="http://xataface.com/dox/ckeditor/latest">ckeditor module</a>.

@section download Download Instructions



@todo Upload the package to SourceForge

@warning No release is available for download yet.  Please download from the SVN repository at http://weblite.ca/svn/dataface/modules/htmlreports


@section install Install Instructions



-# Download and install the <a href="http://xataface.com/dox/ckeditor/latest">ckeditor module</a>.
-# Copy the <em>htmlreports</em> directory into your either your application's <em>modules</em> directory or your Xataface <em>modules</em> directory.
-# Add the following to the <em>[_modules]</em> section of your application's conf.ini file: @code
modules_htmlreports=modules/htmlreports/htmlreports.php
@endcode

@note Ensure that this line comes @e after the <a href="http://xataface.com/dox/ckeditor/latest">ckeditor module</a> line item.

@ref helloworld "Next: Creating Your First Report"

@see @ref getting_started

*/
?>