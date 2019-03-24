<?php
import('Services/JSON.php');

class actions_DataGrid_view {
	function handle(&$params){
		$app =& Dataface_Application::getInstance();
		$query =& $app->getQuery();
	
		// We need to load the current data grid from the database.
		// Its id is provided in the -gridid request parameter.
		
		
		
		$mt =& Dataface_ModuleTool::getInstance();
		$mod =& $mt->loadModule('modules_DataGrid');
		if ( PEAR::isError($mod) ) return $mod;
		
		if ( !@$query['-gridid'] ){
			// No grid was specified.. so we will just take the first grid
			$grids = $mod->getDataGrids();
			if ( !$grids ){
				// No grids were found.  We need to create one
				$table =& Dataface_Table::loadTable($query['-table']);
				$grid = $mod->createDataGrid($query['-table'].' default grid', $query['-table'], array_keys($table->fields()));
				$res = $mod->saveDataGrid($grid);
				if ( PEAR::isError($res) ) return $res;
				$dataGrid =& $grid;
			} else {
				$dataGrid = $grids[0];
			}
		}
		
		if ( PEAR::isError($dataGrid) ) return $dataGrid;
		if ( !@$dataGrid ) $dataGrid =& $mod->getDataGrid($query['-gridid']);
		if ( !$dataGrid ) return PEAR::raiseError("Error, the specified data grid could not be found");
		
		$json = new Services_JSON;
		$jsonFieldDefs = $json->encode($dataGrid->getFieldDefs(true));
		df_register_skin('DataGrid', DATAFACE_PATH.'/modules/DataGrid/templates');
		df_display(array('grid'=>&$dataGrid, 'fieldDefs' => $jsonFieldDefs, 'json'=>&$json), 'DataGrid/view.html');
	}

}