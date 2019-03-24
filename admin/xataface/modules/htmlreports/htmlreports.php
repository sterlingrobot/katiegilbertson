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
 * @brief The main HTML Report module class.  This includes all of the 
 * core module tasks.
 *
 * @section accessing_module Accessing Objects Of This Class
 *
 * @code
 * $moduleTool = Dataface_ModuleTool::getInstance();
 * $reportsModule = $moduleTool->loadModule('modules_htmlreports');
 * @endcode
 */
class modules_htmlreports {
	
        private $pathsAdded = false;
    
	/**
	 * @brief The name of the table that stores the report template.
	 */
	const REPORTS_TABLE = 'dataface__htmlreports_reports';

	
	/**
	 * @brief The base URL to the datepicker module.  This will be correct whether it is in the 
	 * application modules directory or the xataface modules directory.
	 *
	 * @see getBaseURL()
	 */
	private $baseURL = null;
	
	/**
	 * @brief Returns the SQL required to build the reports table.
	 * @return string The SQL string
	 */
	public static function getReportsTableSQL(){
		
		$sql = "create table `".self::REPORTS_TABLE."` (
			`report_id` int(11) not null auto_increment primary key,
			`actiontool_name` varchar(255),
			`actiontool_category` varchar(255),
			`actiontool_label` varchar(255),
			`actiontool_permission` varchar(255),
			`icon` varchar(255),
			`tablename` varchar(255) not null,
			`template_css` text,
			`template_html` longtext not null,
			`default_view` varchar(255),
			`created_by` varchar(255),
			`private` tinyint(1) default 0,
			`date_created` datetime,
			`last_modified` datetime
			
		
		)";
		
		return $sql;
	
	}
	
	
	
	/**
	 * @brief Performs an SQL query or an array of queries.
	 * @param mixed $sql The SQL query string to be run or an array of queries.
	 * @return resource The MySQL resource.
	 * @throws Exception If there is an error.
	 */
	public static function q($sql){
		if ( is_array($sql) ){
			$res = null;
			foreach ($sql as $q){
				$res = self::q($q);
			}
			return $res;
		} else {
			$res = xf_db_query($sql, df_db());
			if ( !$res ){
				throw new Exception(xf_db_error(df_db()));
			}
			return $res;
		}
	}
	
	
	/**
	 * @brief Performs an SQL query - but catches errors and tries to create the
	 * reports table if it fails the first time.  This allows the reports table
	 * to be created on demand if necessary.  Kind of a cheap trick for an auto
	 * install.
	 * @param mixed $sql The SQL query string to be run or an array of queries.
	 * @return resource The MySQL resource.
	 * @throws Exception If there is an error.
	 */
	public static function queryReports($sql){
		try {
			return self::q($sql);
		} catch ( Exception $ex){
			self::q(self::getReportsTableSQL());
			return self::q($sql);
		}
	}
	
	
	
	
	/**
	 * @brief Constructor for the module.  This is called only once per request 
	 * when the module is initialized.
	 */
	public function __construct(){
		//echo "here";exit;
		Dataface_Table::setBasePath(self::REPORTS_TABLE, dirname(__FILE__));
		$conf =& Dataface_Application::getInstance()->_conf;
		if ( !isset($conf['_allowed_tables']) ){
			$conf['_allowed_tables'] = array();
		}
		$conf['_allowed_tables'][self::REPORTS_TABLE] = self::REPORTS_TABLE;
		
		self::queryReports("select report_id from `".self::REPORTS_TABLE."` limit 1");
		
		// Now add reports as actions
		$query = Dataface_Application::getInstance()->getQuery();
		
		$sql = "select report_id, actiontool_name, actiontool_category, actiontool_label, tablename, created_by, `private`, date_created, last_modified from `".self::REPORTS_TABLE."` where `tablename`='".addslashes($query['-table'])."' limit 100";
		$res = self::queryReports($sql);
		$reports = array();
		$table = Dataface_Table::loadTable($query['-table']);
		$at = Dataface_ActionTool::getInstance();
		
		while ($row = xf_db_fetch_assoc($res) ){
			$report = new Dataface_Record(self::REPORTS_TABLE, $row);
			//$perms = $table->getPermissions(array('report'=>$report));
			$icon = $this->getBaseURL().'/images/report_icon.png';
			if ( $report->val('default_view') ){
				$icon = $this->getBaseURL().'/images/'.basename($report->val('default_view')).'_icon.png';
				
			}
			if ( $report->val('icon') ){
				$icon = $report->val('icon');
			}
			//if ( @$perms['view report'] ){
				$action = array(
					'name'=>$row['actiontool_name'],
					'label'=>$row['actiontool_label'],
					'category'=>$row['actiontool_category'],
					'url'=>'{$this->url(\'-action=htmlreports_view_report\')}&--report-id='.$row['report_id'],
					'table'=>$query['-table'],
					'icon'=>$icon,
					'permission' => $report->val('actiontool_permission')
				);
                                if ( $row['actiontool_category'] == 'list_row_actions' ){
                                    $action['url'] = '{$record->getURL(\'-action=htmlreports_view_report\')}&--report-id='.$row['report_id'];
                                    $action['url_condition'] = '$record';
                                    $action['condition'] = '$record';
                                }
				$at->addAction($row['actiontool_name'], $action);
			//}
		}
		
		
		
		Dataface_Application::getInstance()->addHeadContent(
			'<script>XATAFACE_MODULES_HTMLREPORTS_URL="'.$this->getBaseURL().'";</script>'
		);
		
		
		
		
		
	
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
	
	
	public function addPaths(){
            if ( !$this->pathsAdded ){
                $this->pathsAdded = true;
		Dataface_JavascriptTool::getInstance()->addPath(dirname(__FILE__).'/js', $this->getBaseURL().'/js');
		Dataface_CSSTool::getInstance()->addPath(dirname(__FILE__).'/css', $this->getBaseURL().'/css');
            }
	}
	
	
	
	
	/**
	 * @brief Returns a report by its report id
	 * @param int $id The integer id.
	 * @return Dataface_Record The report.
	 */
	public function getReportById($id){
		return df_get_record(self::REPORTS_TABLE, array('report_id'=>'='.$id));
	}
	
	
}