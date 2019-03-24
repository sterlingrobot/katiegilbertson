<?php
import('HTML/QuickForm.php');
$GLOBALS['HTML_QUICKFORM_ELEMENT_TYPES']['advmultiselect'] = array('HTML/QuickForm/advmultiselect.php', 'HTML_QuickForm_advmultiselect');
import('Services/JSON.php');
class actions_DataGrid_new {
	function handle(&$params){
		$app =& Dataface_Application::getInstance();
		$query =& $app->getQuery();
		
		$table =& Dataface_Table::loadTable($query['-table']);
		
		
		$form = new HTML_QuickForm();
		foreach ($query as $key=>$val){
			 if ($key{0} == '-' and $key{1} != '-' ){
			 	$form->addElement('hidden',$key,$val);
			 }
		}
		$form->addElement('hidden', '--redirect');
		$form->setDefaults(array('--redirect'=>$app->url('')));
		
		$form->addElement('text','title','Title');
		
		
		$columns = array();
		
		$options = array($query['-table'] => $query['-table']);
		
		
		foreach ($table->relationships() as $rname => $relationship){
			$options['::'.$rname] = '::'.$rname;
			$destTables = $relationship->getDestinationTables();
			$rfields = $relationship->fields(true);
			$fkeys = $relationship->getForeignKeyValues();
			//print_r($fkeys);
			$rcolumns = array();
			foreach ($rfields as $rfname){
				list($rftable,$rfname) = explode('.', $rfname);
				if ( isset($fkeys[$rftable][$rfname]) ) continue;
				$columns['::'.$rname][$rname.'.'.$rfname] = $rname.'.'.$rfname;
			}
			//foreach ($destTables as $destTable){
			//	
			//	foreach ($destTable->relationships() as $destRelationshipName => $destRelationship ){
			//		$options['::'.$rname.'::'.$destRelationshipName] = '::'.$rname.'::'.$destRelationshipName;
			//		
			//	}
			//	unset($destTable);
			//}
		}
		
		$form->addElement('select','table', 'From table', $options, array('onchange'=>'Dataface.modules.DataGrid.newForm.updateFieldsOptions(this);'));
		
		$options = array();
		foreach ( $table->fields() as $fieldName => $fieldDef ){
			$options[$fieldName] = $fieldName;

		}
		$columns[$query['-table']] = $options;
		
		
		$fieldSelector =& $form->addElement('advmultiselect', 'fields', 'Selected Columns', $options);
		$fieldSelector->setButtonAttributes('moveup'  , 'class=inputCommand');
		$fieldSelector->setButtonAttributes('movedown', 'class=inputCommand');
		
		$form->addElement('submit','submit','Create Grid');
		
		if ( $form->validate() ){
			$res = $form->process(array(&$this,'process'), true);
			if ( PEAR::isError($res) ) return $res;
			
			if ( @$query['--redirect'] ){
				$url = $query['--redirect'];
			} else {
				$url = $app->url('');
			}
			$url = preg_replace('/[&]--[^=]+\=[^&]*/', '', $url);
			$url .= '&--msg='.urlencode('Grid Saved Successfully');
			header('Location: '.$url);
			exit;
			
		}
		
		
		import('HTML/QuickForm/Renderer/ArraySmarty.php');
		import('Dataface/SkinTool.php');
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty(Dataface_SkinTool::getInstance());
		$form->accept($renderer);
		df_register_skin('DataGrid', DATAFACE_PATH.'/modules/DataGrid/templates');
		
		$json = new Services_JSON;
		
		df_display(array('form'=>$renderer->toArray(), 'columns'=>$json->encode($columns)), 'DataGrid/new.html');
	}
	
	function process($vals){
		$app =& Dataface_Application::getInstance();
		$query =& $app->getQuery();
		
		$mt =& Dataface_ModuleTool::getInstance();
		$mod =& $mt->loadModule('modules_DataGrid');
		if ( PEAR::isError($mod) ) return $mod;
		
		$grid = $mod->createDataGrid($vals['title'], $query['-table'], $vals['fields']);
		$res = $mod->saveDataGrid($grid);
		return $res;
	}
}