Xataface Datepicker Module
Copyright (c) 2011, Steve Hannah <shannah@sfu.ca>, All Rights Reserved

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

The Datepicker module adds a "datepicker" widget for use in your
applications.  This widget is appropriate for only date fields (not
datetime or time).  It essentially applies the jQuery-UI datepicker
widget to a regular text field.

Requirements:
=============

Xataface 1.4 or higher

Installation:
=============

1. Copy the datepicker directory into your modules directory. i.e.:

modules/datepicker.

2. Add the following to the [_modules] section of your app's conf.ini
file:

modules_datepicker=modules/datepicker/datepicker.php

Usage:
======

Set any date field to use the datepicker widget for editing.  E.g. field
definition in the conf.ini file:

[mydatefield]
    widget:type=datepicker
    
   
More Reading:
=============

TBA


Support:
========

http://xataface.com/forum

