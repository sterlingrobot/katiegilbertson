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
  * @brief HTTP handler to view a specified report.
  *
  *
  * @section permissions Permissions
  *
  * Access to this action is dictated by the report permission.  The report permission 
  * can be set in the edit form for the report.
  *
  * @see @ref setting_report_permissions
  *
  * @section preview_report_rest_api REST API
  *
  * @subsection post_parameters POST Parameters
  *
  * <table>
  * <tr><th>Parameter Name</th><th>Parameter Description</th><th>Example Input</th><th>Required</th></tr>
  * <tr>
  *	<td>@c -table</td>
  *	<td>The name of the table on which the template is designed to run.</td>
  *  <td>transactions</td>
  *  <td>Yes</td>
  * </tr>
  * <tr>
  *	<td>@c -action=htmlreports_view_port</td>
  *	<td>Specifies this action</td>
  *  <td>N/A</td>
  *  <td>Yes</td>
  * </tr>
  * <tr>
  *	<td>@c --report-id</td>
  *	<td>The ID of the report that should be used to render the set.</td>
  *  <td>10</td>
  *  <td>Yes</td>
  * </tr>
  * <tr>
  *	<td>@c --view</td>
  *	<td>The view mode to view this report in.  (one of 'list', 'details', or 'table').  If this is 
  *     omitted, the <em>Default View</em> setting of the report will be used.
  *  @see @ref view_modes </td>
  *  <td>list</td>
  *  <td>No</td>
  * </tr>
  * </table>
  *
  * @note Any request parameter conforming to 
  * <a href="http://xataface.com/wiki/URL_Conventions">Xataface's URL conventions</a>
  * may be used to  help specify the result set that should be returned.
  *
  * @subsection returntype Return Type
  * 
  * This handler will return a text/html page with the result set defined by the
  * HTTP request rendered using the specified report template.
  */
class actions_htmlreports_view_report {

	function handle($params){
		try {
			$app = Dataface_Application::getInstance();
			$query = $app->getQuery();
			$mod = Dataface_ModuleTool::getInstance()->loadModule('modules_htmlreports');
			
			$reportid = @$query['--report-id'];
			if ( !$reportid ){
				throw new Exception("No report id specified");
			}
			
			$report = $mod->getReportById($reportid);
			if ( !$report ){
				throw new Exception("No report found with that id");
			}
			
			if ( $query['-table'] != $report->val('tablename') ){
				throw new Exception("The specified report is not designed to work on this table.");
			}
			
			$table = Dataface_Table::loadTable($query['-table']);
			$perms = $table->getPermissions();
			
			
			if ( $report->val('actiontool_permission') and !@$perms[$report->val('actiontool_permission')] ){
				throw new Exception(sprintf(
					"You don't have permission to view this report.  '%s' is required.",
					$report->val('actiontool_permission')
				));
				
			}
			
			
			
			
			if ( !@$query['--view'] and $report->val('default_view') ){
				$query['--view'] = $report->val('default_view');
			}
			
			require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'XfHtmlReportBuilder.class.php');
			if ( @$query['--view'] == 'table' ){
				$records = df_get_selected_records($query);
				if ( !$records ){
					$records = df_get_records_array($query['-table'], $query, 0, 999, false);
				}
				$results = XfHtmlReportBuilder::fillReportTable($records, $report->val('template_html'));
			} else if ( @$query['--view'] == 'details' ){
				
				$results = XfHtmlReportBuilder::fillReportSingle($app->getRecord(), $report->val('template_html'));
			} else {
				$records = df_get_selected_records($query);
				if ( !$records ){
					$records = df_get_records_array($query['-table'], $query, 0, 999, false);
				}
				$results = XfHtmlReportBuilder::fillReportMultiple($records, $report->val('template_html'));
			}
			header('Content-type: text/html; charset="'.$app->_conf['oe'].'"');
			df_register_skin('htmlreports', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates');
			
			$context = array(
				'html'=>$results,
				'report'=>$report
			);
			
			df_display($context, 'xataface/modules/htmlreports/view_report.html');
			//echo $results;
		} catch (Exception $ex){
			if ( $ex->getCode() == 2000 ){
				print($ex->getTraceAsString());
				exit;
			}
			return Dataface_Error::permissionDenied($ex->getMessage());
		}
	}
}