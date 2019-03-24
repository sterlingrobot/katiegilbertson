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
 * @brief HTTP handler to preview a report given a template.
 *
 * @section preview_report_rest_api REST API
 *
 * @subsection post_parameters POST Parameters
 *
 * <table>
 * <tr><th>Parameter Name</th><th>Parameter Description</th><th>Example Input</th></tr>
 * <tr>
 *	<td>@c --template</td>
 *	<td>The HTML template content to be used to generate the report.</td>
 *	<td>@code <h1>My Report Title</h1>
 *	My field {$my_field}
 *  @endcode
 *	</td>
 * </tr>
 * <tr>
 *	<td>@c --css</td>
 *	<td>The CSS to use to style the content.</td>
 *	<td>@code body { font-family: sans-serif;} @endcode</td>
 * </tr>
 * <tr>
 *	<td>@c -table</td>
 *	<td>The name of the table on which to run the sample report.</td>
 *  <td>transactions</td>
 * </tr>
 * </table>
 *
 * @subsection returntype Return Type
 * 
 * This handler will return a text/html response
 */
 class actions_htmlreports_preview_report {

	function handle($params){
		try {
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			$mod = Dataface_ModuleTool::getInstance()->loadModule('modules_htmlreports');
			
			$templateContent = @$query['--template'];
			if ( !$templateContent ){
				throw new Exception("No template provided");
			}
			
			$css = '';
			if ( @$query['--css'] ){
				$css = $query['--css'];
			}
			
			
			$table = Dataface_Table::loadTable($query['-table']);
			$perms =& $table->getPermissions();
			if ( !@$perms['preview report'] ){
				throw new Exception("You don't have permission to preview reports on this table.  Previewing reports requires the 'preview report' permission.");
				
			}
			
			
			$records = df_get_selected_records($query);
			if ( !$records ){
				$records = df_get_records_array($query['-table'], $query, 0, 10, false);
			}
			
			
			
			
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'XfHtmlReportBuilder.class.php');
			if ( @$query['--view'] == 'table' ){
				$results = XfHtmlReportBuilder::fillReportTable($records, $templateContent);
			} else {
				$results = XfHtmlReportBuilder::fillReportMultiple($records, $templateContent);
			}
			header('Content-type: text/html; charset="'.$app->_conf['oe'].'"');
			df_register_skin('htmlreports', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates');
			
			$context = array(
				'html'=>$results,
				'css' => $css
			);
			
			df_display($context, 'xataface/modules/htmlreports/preview_report.html');
		} catch (Exception $ex){
			//if ( $ex->getCode() == 2000 ){
			//	print($ex->getTraceAsString());
			//	exit;
			//}
			return Dataface_Error::permissionDenied($ex->getMessage());
		}
	}
}