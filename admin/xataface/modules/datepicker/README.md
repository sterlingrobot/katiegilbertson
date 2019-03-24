#Xataface Datepicker Module

<center><img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-06-07%20at%2010.39.53%20AM.png?max_width=640"/></center>

##Synopsis

This module adds two widgets for use in Xataface applications:

1. datepicker - For picking dates.  (Uses jQuery UI's datepicker control)
2. datetimepicker - For picking dates and times (Uses a variation on jQuery UI's datepicker control)


##License

~~~
Xataface Datepicker Module
Copyright (c) 2011-2014, Steve Hannah <shannah@sfu.ca>, All Rights Reserved

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
~~~


##Requirements

* Xataface 1.4 or higher

##Installation

1. Copy the datepicker directory into your modules directory. i.e.: `modules/datepicker`
2. Add the following to the `[_modules]` section of your app's conf.ini

~~~
modules_datepicker=modules/datepicker/datepicker.php
~~~

###Installing using git

~~~
$ cd /path/to/myapp
$ mkdir modules
$ cd modules
$ git clone https://github.com/shannah/xataface-module-datepicker.git datepicker
~~~

Then add 

~~~
modules_datepicker=modules/datepicker/datepicker.php
~~~

to the `[_modules]` section of your `conf.ini` file.

See the [Xataface modules wiki page](http://xataface.com/wiki/modules) For more information about Xataface module development and installation.


##Usage

### The datepicker Widget

Set any date field to use the  datepicker widget for editing.  E.g. field
definition in the fields.ini file:

~~~
[mydatefield]
    widget:type=datepicker
~~~

Then your field will use the datepicker widget:

<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-06-07%20at%2010.39.53%20AM.png?max_width=640"/>

See [the jQuery UI datepicker control docs](http://jqueryui.com/demos/datepicker/) For more information about the datepicker widget.

### The datetimepicker widget

Set any datetime field to use the @c datetimepicker widget for editing.  E.g. field definition in the fields.ini file:

~~~
[mydatetimefield]
	widget:type=datetimepicker

~~~

Then your field will use the @c datetimepicker widget.




##fields.ini options:
* widget:format - The date format.  E.g. "yy/mm/dd".  [Format options](http://api.jqueryui.com/datepicker/#utility-formatDate)
* widget:showOn  - Indicates when to show.  Default is 'focus',  Setting to 'button' will show when a trigger button is clicked.  
* widget:showAnim - Name of the jQuery animation to show for popup.  Default is 'show'. Some options include "fade", "slide", "slideDown", "slideUp", "show", "fadeIn", "fadeOut".
* widget:showOptions - Options for enhanced animations.  This should be a javascript expression resolving to an object that can be passed into the appropriate animation function.  For an example of options, see the appropriate animation page on the jQuery website.  E.g. the [fadeIn docs](http://api.jquery.com/fadeIn/).
* widget:defaultDate - Set the date to highlight on first opening if the field is blank. Specify either an actual date in the current dateFormat, or a number of days from today (e.g. +7) or a string of values and periods ('y' for years, 'm' for months, 'w' for weeks, 'd' for days, e.g. '+1m +7d'). Default is today.
* widget:appendText - Display text following the input box (e.g. showing the format)
* widget:buttonText - Text for the trigger button.
* widget:buttonImage - URL for trigger button image.
* widget:buttonImageOnly - "true" if the image appears alone.  "false" if it appears on a button.
* widget:hideIfNoPrevNext - "true" to hide next/previous month links.
* widget:navigationAsDateFormat - "true" if date formatting applied to prev/today/next links.
* widget:gotoCurrent - "true" if today link goes back to current selection instead.
* widget:changeMonth - "true" if month can be selected directly.  "false" if only prev/next.
* widget:yearRange - The range of years displayed in the year drop-down: either relative to today's year ("-nn:+nn"), relative to the currently selected year ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these formats ("nnnn:-nn"). Note that this option only affects what appears in the drop-down, to restrict which dates may be selected use the minDate and/or maxDate options.
* widget:showOtherMonths - "true" to show dates in other months.  "false" to leave blank.
* widget:selectOtherMonths - "true" to allow selection of dates in other months.  "false" for unselectable.
* widget:showWeek - "true" to show week of the year.  "false" to not show it.
* widget:calculateWeek - Javascript expression resolving to a function that calculates the week of the year.  Function should take a Date and return the number of the week for it.  See [jQuery UI docs](http://api.jqueryui.com/datepicker/#option-calculateWeek) for details on how function should be implemented.
* widget:shortYearCuroff - The cutoff year for determining the century for a date (used in conjunction with dateFormat 'y'). Any dates entered with a year value less than or equal to the cutoff year are considered to be in the current century, while those greater than it are deemed to be in the previous century. A relative number of years from the current year, e.g., "+3" or "-5".
* widget:minDate  - The minimum selectable date. A string in the format defined by the [dateFormat](http://api.jqueryui.com/datepicker/#option-dateFormat) option, or a relative date. Relative dates must contain value and period pairs; valid periods are "y" for years, "m" for months, "w" for weeks, and "d" for days. For example, "+1m +7d" represents one month and seven days from today.
* widget:maxDate  - The maximum selectable date.  A string in the format defined by the [dateFormat](http://api.jqueryui.com/datepicker/#option-dateFormat) option, or a relative date. Relative dates must contain value and period pairs; valid periods are "y" for years, "m" for months, "w" for weeks, and "d" for days. For example, "+1m +7d" represents one month and seven days from today.
* widget:duration - The duration of the display/closure.
* widget:beforeShowDay - A javascript expression resolving to a function that takes a date and returns an array with [0] = true if selectable, false if not, [1] = customCSS class name(s) or '', [2]= cell title (optional).
* widget:onSelect - A javascript expression resolving to a function which is a callback used when a date is selected.
* widget:onChangeMonthYear - A javascript expression resolving to a function which is a callback used when the month or year is changed.
* widget:onClose - A javascript expression resolving to a function which is a callback used when the datepicker is closed.
* widget:numberOfMonths - The number of months to show at a time.  e.g. "1"
* widget:showCurrentAtPos - The position in multiple months at which to show the current month (starting at 0). E.g. "0"
* widget:stepMonths - The number of months to step back/forward.  E.g. "1"
* widget:stepBigMonths - The number of months to step back/forward for the big links.
* widget:altField - Selector for an alternate field to store selected dates into.
* widget:altFormat - The date format to use for the alternate field.
* widget:constrainInput - "true" if the input is constrained by the current date format.
* widget:showButtonPanel - "true" to show button panel.  "false" to not show it.
* widget:autoSize - "true" to size the input for the date format.

See the [fields.ini wiki page](http://xataface.com/wiki/fields.ini_file) For more information about fields.ini file directives.
See the [widget:type wiki page](http://xataface.com/wiki/widget%3Atype) For more information about the widget:type directive.


##Support

* <a href="http://xataface.com/forum">Xataface Forum</a>

##Credits

* This module is a wrapper around the wonderful [jQuery UI datepicker widget](http://jqueryui.com/demos/datepicker/).
* Module developed and maintained by [Steve Hannah](http://sjhannah.com)
