<?php
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
/**
 * @brief The datepicker module core class.
 *
 * This class is loaded whenever the module is active.  It loads the appropriate
 * dependencies and registers the datepicker widget.
 *
 * @author Steve Hannah <steve@weblite.ca>
 * @created June 6, 2011
 */
class modules_datepicker {

	/**
	 * @brief The base URL to the datepicker module.  This will be correct whether it is in the 
	 * application modules directory or the xataface modules directory.
	 *
	 * @see getBaseURL()
	 */
	private $baseURL = null;
	
	
	
	/**
	 * @brief Initializes the datepicker module and registers all of the event listener.
	 *
	 */
	function __construct(){
		$app = Dataface_Application::getInstance();
		
		
		// Now work on our dependencies
		$mt = Dataface_ModuleTool::getInstance();
		
		// We require the XataJax module
		// The XataJax module activates and embeds the Javascript and CSS tools
		$mt->loadModule('modules_XataJax', 'modules/XataJax/XataJax.php');
		
		
		// Register the tagger widget with the form tool so that it responds
		// to widget:type=tagger
		import('Dataface/FormTool.php');
		$ft = Dataface_FormTool::getInstance();
		$ft->registerWidgetHandler('datepicker', dirname(__FILE__).DIRECTORY_SEPARATOR.'widget.php', 'Dataface_FormTool_datepicker');
		$ft->registerWidgetHandler('datetimepicker', dirname(__FILE__).DIRECTORY_SEPARATOR.'datetimepickerwidget.php', 'Dataface_FormTool_datetimepicker');
		
		
	}
	
	
	/**
	 * @brief Returns the base URL to this module's directory.  Useful for including
	 * Javascripts and CSS.
	 *
	 */
	public function getBaseURL(){
		if ( !isset($this->baseURL) ){
			$this->baseURL = Dataface_ModuleTool::getInstance()->getModuleURL(__FILE__);
		}
		return $this->baseURL;
	}
	
	
}