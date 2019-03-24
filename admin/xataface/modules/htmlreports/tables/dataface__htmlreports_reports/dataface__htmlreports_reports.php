<?php
class tables_dataface__htmlreports_reports {

	private $tablenames = null;
	private $action_categories = null;

	
	function titleColumn(){
		return 'actiontool_label';
	}
	
	function getTitle($record){
		return $record->display('actiontool_label');
	}
	
	function getPermissions($record, $params=array()){
		$table = Dataface_Table::loadTable('dataface__version');
		if ( $record and $record->val('tablename') ){
			$table = Dataface_Table::loadTable($record->val('tablename'));
		}
		$perms = $table->getPermissions();
		if ( @$perms['manage reports'] ){
			return array(
				'new'=>1,
				'edit'=>1,
				'delete'=>1,
				'list'=>1,
				'find'=>1,
				'view'=>1,
				'link'=>1
			);
		} else {
			return Dataface_PermissionsTool::NO_ACCESS();
		}
	}
	
	function block__before_form(){
            
                $mod = Dataface_ModuleTool::getInstance()->loadModule('modules_htmlreports');
		$ckeditor = Dataface_ModuleTool::getInstance()->loadModule('modules_ckeditor');
                $ckeditor->registerPaths();
	
                /*
		$jt = Dataface_JavascriptTool::getInstance();
		$ct = Dataface_CSSTool::getInstance();
		
		$s = DIRECTORY_SEPARATOR;
		
		$ct->addPath(dirname(__FILE__).$s.'..'.$s.'..'.$s.'css',
			$mod->getBaseURL().'/css'
		);
		
		$jt->addPath(dirname(__FILE__).$s.'..'.$s.'..'.$s.'js',
			$mod->getBaseURL().'/js');
		*/
                $mod->addPaths();
		Dataface_JavascriptTool::getInstance()->import('xataface/modules/htmlreports/reports_form.js');
	}



	function block__before_template_html_widget(){
	
		$jt = Dataface_JavascriptTool::getInstance();
		
		$jt->import('ckeditor/plugins/insertmacro/insertmacro.js');
		
	}
	
	
	
	
	function valuelist__tablenames(){
		if ( !isset($this->tablenames) ){
			$app = Dataface_Application::getInstance();
			$tables = $app->_conf['_tables'];
			$out = array();
			
			
			if ( @$app->_conf['_htmlreports_tables'] ){
				$tables = array_merge($tables, $app->_conf['_htmlreports_tables']);
			}
			
			foreach ($tables as $tname=>$label){
				if ( Dataface_Table::tableExists($tname) ){
					$table = Dataface_Table::loadTable($tname);
					$perms = $table->getPermissions();
					if ( @$perms['view schema'] ){
						$out[$tname] = $label;
					}
				}
			}
			$this->tablenames = $out;
		}
		return $this->tablenames;
		
		
	}
	
	function beforeInsert($record){
		if ( class_exists('Dataface_AuthenticationTool') ){
			$username = Dataface_AuthenticationTool::getInstance()->getLoggedInUserName();
			$record->setValue('created_by', $username);
		}
		 
	}
	
	
	function valuelist__action_categories(){
		if ( !isset($this->action_categories) ){
			$this->action_categories = array();
			import('Dataface/ActionTool.php');
			$at = Dataface_ActionTool::getInstance();
			
			$cats = array();
			
			foreach ($at->actions as $action){
				if ( @$action['category'] ){
					$cats[$action['category']] = $action['category'];
				}
			}
			asort($cats);
			$this->action_categories = $cats;
		}
		return $this->action_categories;
	}
	
	
	function valuelist__action_permissions(){
		if ( !isset($this->action_permissions) ){
			$this->action_permissions = array();
			import('Dataface/PermissionsTool.php');
			$perms = Dataface_PermissionsTool::ALL();
			foreach ($perms as $key=>$val){
				$perms[$key] = $key;
			}
			$this->action_permissions = $perms;
		}
		return $this->action_permissions;
	
	}

}