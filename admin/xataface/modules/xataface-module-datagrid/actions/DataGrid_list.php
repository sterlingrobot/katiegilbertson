<?php

class actions_DataGrid_list {
	function handle(&$params){
		$app =& Dataface_Application::getInstance();
		$query =& $app->getQuery();
		$mt =& Dataface_ModuleTool::getInstance();
		$mod =& $mt->loadModule('modules_DataGrid');
		if ( PEAR::isError($mod) ) return $mod;
		
		$res = mysql_query("select gridID,gridName from dataface__DataGrids where tableName='".addslashes($query['-table'])."'", df_db());
		$grids = array();
		
		while ( $row = mysql_fetch_assoc($res) ){
			$grids[$row['gridID']] = array(
				'name'=>$row['gridName'],
				'url' => $app->url('-gridid='.$row['gridID'].'&-action=DataGrid_view')
			);
		}
		//print_r($grids);
		df_register_skin('DataGrid', DATAFACE_PATH.'/modules/DataGrid/templates');
		
		df_display(array('grids'=>$grids), 'DataGrid/list.html');
		
	}

}