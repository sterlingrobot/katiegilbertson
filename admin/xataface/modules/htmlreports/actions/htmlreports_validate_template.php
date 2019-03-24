<?php
/*
 * Xataface HTML Reports Module
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
  * @brief HTTP handler to validate a report template to ensure that it has
  * valid syntax.  This action is accessed via the Template editor toolbar 
  * button to validate the current template.  If the template is validated
  * successfully it will return an JSON object with a response code of 200.
  * If it fails it will return a different "code" in the JSON object.
  *
  *
  * @section permissions Permissions
  *
  * In order to view a row, the user must be granted the <em>validate report template</em>
  * permission for the target table.
  *
  * @see @ref module_permissions
  *
  * @section preview_report_rest_api REST API
  *
  * @subsection post_parameters POST Parameters
  *
  * <table>
  * <tr><th>Parameter Name</th><th>Parameter Description</th><th>Example Input</th></tr>
  * <tr>
  *	<td>@c -table</td>
  *	<td>The name of the table on which the template is designed to run.</td>
  *  <td>transactions</td>
  * </tr>
  * <tr>
  *	<td>@c -action=htmlreports_validate_template</td>
  *	<td>Specifies this action</td>
  *  <td>N/A</td>
  * </tr>
  * <tr>
  *	<td>@c --template</td>
  *	<td>The HTML for the template that is being validated.</td>
  *  <td>@code <h1>My Template</h1> @endcode</td>
  * </tr>
  * </table>
  *
  *
  * @subsection returntype Return Type
  * 
  * This handler will return a application/json response containing a JSON data with the following
  * keys:
  *
  * <table>
  * <tr><th>Key</th><th>Description</th><th>Possible Values</th></tr>
  * <tr><td>code</th><td>The response code</td><td><b>200</b> for success.  Some other value for an error or failure</td></tr>
  * <tr><td>message</th><td>The response message</td><td>"Template validation was successful.  No errors found."</td></tr>
  * </table>
  */
class actions_htmlreports_validate_template {
	function handle($params){
	
		try {
		
			if ( !@$_POST['--template'] ){
				throw new Exception("No template content provided");
			}
			
			if ( !@$_POST['-table'] ){
				throw new Exception("No table specified for template.");
			}
			
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'XfHtmlReportBuilder.class.php');
			
			$table = Dataface_Table::loadTable($_POST['-table']);
			$perms = $table->getPermissions(array());
			if ( !@$perms['validate report template'] ){
				throw new Exception("You don't have permission to validate this template.  Template validation requires the 'validate report template' permission.");
				
			}
			$res = XfHtmlReportBuilder::validateTemplate($table, $_POST['--template']);
			
			
			//$results = XfHtmlReportBuilder::fillReportTable($records, $report->val('template_html'));
		
			$this->out(array(
				'code'=>200,
				'message'=> 'Template validation was successful.  No errors found.'
			));
			exit;
		
		} catch (Exception $ex){
			$this->out(array(
				'code'=> $ex->getCode(),
				'message'=> $ex->getMessage()
			));
			exit;
		
		
		}
	}
	
	function out($params){
		header('Content-type: application/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
	}
}