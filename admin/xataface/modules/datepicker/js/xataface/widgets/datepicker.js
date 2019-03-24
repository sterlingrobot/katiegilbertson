/*
 * Xataface Datepicker Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 * 
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */

//require <jquery.packed.js>
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>

(function(){
	var $ = jQuery;
	
	var pickerAtts = {
		
		    'showOn': 'string', //'focus', // 'focus' for popup on focus,
            // 'button' for trigger button, or 'both' for either
            'showAnim': 'string', //'show', // Name of jQuery animation for popup
            'showOptions' :  'object', //{}, // Options for enhanced animations
            'defaultDate' : 'string', //null, // Used when field is blank: actual date,
            // +/-number for offset from today, null for today
            'appendText': 'string', //'', // Display text following the input box, e.g. showing the format
            'buttonText' : 'string', //'...', // Text for trigger button
            'buttonImage' : 'string', //'', // URL for trigger button image
            'buttonImageOnly' :  'bool', //false, // True if the image appears alone, false if it appears on a button
            'hideIfNoPrevNext' : 'bool', //false, // True to hide next/previous month links
            // if not applicable, false to just disable them
            'navigationAsDateFormat' : 'bool', // false, // True if date formatting applied to prev/today/next links
            'gotoCurrent' : 'bool', //false, // True if today link goes back to current selection instead
            'changeMonth' : 'bool', //false, // True if month can be selected directly, false if only prev/next
            'changeYear' : 'bool', //false, // True if year can be selected directly, false if only prev/next
            'yearRange' : 'string', //'c-10:c+10', // Range of years to display in drop-down,
            // either relative to today's year (-nn:+nn), relative to currently displayed year
            // (c-nn:c+nn), absolute (nnnn:nnnn), or a combination of the above (nnnn:-n)
            'showOtherMonths' : 'bool', //false, // True to show dates in other months, false to leave blank
            'selectOtherMonths' : 'bool', //false, // True to allow selection of dates in other months, false for unselectable
            'showWeek' : 'bool', //false, // True to show week of the year, false to not show it
            'calculateWeek' : 'object', //this.iso8601Week, // How to calculate the week of the year,
            // takes a Date and returns the number of the week for it
            'shortYearCutoff' : 'string', //'+10', // Short year values < this are in the current century,
            // > this are in the previous century,
            // string value starting with '+' for current year + value
            'minDate' : 'string', // null, // The earliest selectable date, or null for no limit
            'maxDate' : 'string', //null, // The latest selectable date, or null for no limit
            'duration' : 'string', //'_default', // Duration of display/closure
            'beforeShowDay' : 'object', //null, // Function that takes a date and returns an array with
            // [0] = true if selectable, false if not, [1] = custom CSS class name(s) or '',
            // [2] = cell title (optional), e.g. $.datetimepicker.noWeekends
            'beforeShow' : 'object', //null, // Function that takes an input field and
            // returns a set of custom settings for the date picker
            'onSelect' : 'object', //null, // Define a callback function when a date is selected
            'onChangeMonthYear' : 'object', //null, // Define a callback function when the month or year is changed
            'onClose' : 'object', //null, // Define a callback function when the datepicker is closed
            'numberOfMonths' : 'int', //1, // Number of months to show at a time
            'showCurrentAtPos' : 'int', //0, // The position in multipe months at which to show the current month (starting at 0)
            'stepMonths' : 'int', //1, // Number of months to step back/forward
            'stepBigMonths' : 'int',// 12, // Number of months to step back/forward for the big links
            'altField' : 'string', //'', // Selector for an alternate field to store selected dates into
            'altFormat' : 'string', //'', // The date format to use for the alternate field
            'constrainInput' : 'bool', //false, // The input is constrained by the current date format
            'showButtonPanel' : 'bool', //false, // True to show button panel, false to not show it
            'autoSize' : 'bool', //false, // True to size the input for the date format, false to leave as is
			'firstDay' : 'int'
		
		};
	
	/**
	 * When defining the javascript for a widget, we always wrap it in
	 * registerXatafaceDecorator so that it will be run whenever any new content is
	 * loaded ino the page.  This makes it compatible with the grid widget.
	 *
	 * If you don't do this, the widget will only be installed on widgets at page load time
	 * so when new rows are added via the grid widget, the necessary javascript won't be installed
	 * on those widgets.
	 */
	registerXatafaceDecorator(function(node){
		// node is the root node that is being decorated.  All
		// queries should be done relative to this node.
		
		
		
		$('input.xf-datepicker', node).each(function(){
		    var self = this;
		    var p = {
				dateFormat: ''+$(this).attr('data-xf-date-format'),
				altFormat: ''+$(this).attr('data-xf-date-format')
			};
		
		
		    $.each(pickerAtts, function(k,v){
			    var attKey = 'data-xf-datepicker-'+k;
			    if ( $(self).attr(attKey) !== undefined ){
			        var val = $(self).attr(attKey);
			        switch ( v ){
			            case 'string': p[k] = val; break;
			            case 'bool' : p[k] = val === 'true'; break;
			            case 'int' : p[k] = parseInt(val); break;
			            case 'object' : p[k] = eval(val); break;
			            
			        }
			    }
			});
			
			$(this).datepicker(p);
			
			
			
		});
		
		
	
	});

})();