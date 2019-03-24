<?php
import('Services/JSON.php');
class actions_DataGrid_datasource_update {
	function handle(&$params){
		$app =& Dataface_Application::getInstance();
		$query =& $app->getQuery();
		$json = new Services_JSON;
		if ( !@$query['-data'] ){
			$this->error('No Data was provided');
		}
		
		
		$updates = $json->decode($query['-data']);
		
		$mt =& Dataface_ModuleTool::getInstance();
		$mod =& $mt->loadModule('modules_DataGrid');
		if ( PEAR::isError($mod) ) return $mod;
		
		$dataGrid = $mod->getDataGrid($query['-gridid']);
		if ( !$dataGrid ) return PEAR::raiseError("Error, the specified data grid could not be found");
		
		$result = array(
		);
		
		foreach ( $updates as $recordID => $vals ){
			$res = $dataGrid->saveRow($recordID, $vals, true /*secure*/);
			if ( is_array($res) ){
				foreach ($res as $err){
					$result['errors'][$recordID] = $err->getMessage();
				}
			} else if ( PEAR::isError($res) ) $result['errors'][strval($recordID)] = $res->getMessage();
			 else if ( is_a($res, 'Dataface_Record') ){
				$result['updated_rows'][$recordID] = $dataGrid->buildRow($res);
			}
		}
		
		$result['success'] = 1;
		header('Content-type: text/json');
		echo $json->encode($result);exit;
	}
}