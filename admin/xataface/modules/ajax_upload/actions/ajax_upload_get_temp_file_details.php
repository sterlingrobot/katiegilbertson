<?php
class actions_ajax_upload_get_temp_file_details {
	function handle($params){
		session_write_close();
		header('Connection:close');
	
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		//if ( !@$query['--fileid'] ){
		//	throw new Exception("No file id specified");
		//}
		
		if ( !@$query['--field'] ){
			throw new Exception("No field specified");
		}
		
		if ( !@$query['-table'] ){
			throw new Exception("No table specified");
		}
		
		$table = Dataface_Table::loadTable($query['-table']);
		$field =& $table->getField($query['--field']);
		
		$path = $field['savepath'];
		if ( @$query['--fileid'] ){
			$uploadsDir = $path.DIRECTORY_SEPARATOR.'uploads';
			$filePath = $uploadsDir.DIRECTORY_SEPARATOR.basename($query['--fileid']);
			
			if ( !file_exists($filePath) ){
				throw new Exception("File does not exist: ".$filePath);
			}
			$infoPath = $filePath.'.info';
			if ( !file_exists($infoPath) ){
				throw new Exception("Info file does not exist");
			}
			
			$serializedData = trim(file_get_contents($infoPath));
			if ( !$serializedData ){
				throw new Exception("No info found in info file.");
			}
			$data = unserialize($serializedData);
		} else {
			$recordId = $query['--recordId'];
			if ( $recordId ) $record = df_get_record_by_id($recordId);
			else $record = new Dataface_Record($query['-table'], array());
			
			$val = $record->val($query['--field']);
			if ( !$record->checkPermission('view', array('field'=>$query['--field']))){
				throw new Exception("Permission denied.  You don't have permission to view this field.");
				
			}
			if ( !$val ){
                                throw new Exception("No value found for this field : {$query['--field']}");
			}
			// was $filePath = $path.DIRECTORY_SEPARATOR.basename($val);
			$filePath = $path.DIRECTORY_SEPARATOR.$val;
			
			$data = array(
				'name'=> $val,
				'type'=>Dataface_ModuleTool::getInstance()->loadModule('modules_ajax_upload')->getMimeType($filePath),
				'size'=>filesize($filePath),
				'url' => $record->display($query['--field'])
			);
		}
		
		
		$out = array(
			'name' => $data['name'],
			'type' => $data['type'],
			'size' => $data['size']
		);
		if ( @$data['url'] ) $out['url'] = $data['url'];
		
		header('Content-type: text/json; charset="'.$app->_conf['oe'].'"');
		echo json_encode($out);
		
		
	}
}