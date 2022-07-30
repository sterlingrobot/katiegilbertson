<?php
class actions_ajax_upload_delete_temp_file {
	
	const CODE_NO_SUCH_FILE = 404;
	const CODE_PERMISSION_DENIED = 400;
	const CODE_FIELD_EMPTY = 405;
	

	function handle($params){
	
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		if ( !@$_POST['--field'] ) throw new Exception("No field specified");
		//if ( !@$_POST['--fileId'] ) throw new Exception("No file id specified");
	
		$fieldName = $_POST['--field'];
		$tableName = $_POST['-table'];
		$fileId = null;
		$recordId = null;
		if ( @$_POST['--fileId'] ){
			$fileId = $_POST['--fileId'];
		} else if ( @$_POST['--recordId'] ){
			$recordId = $_POST['--recordId'];
		}
		
		
		$table = Dataface_Table::loadTable($tableName);
		$field =& $table->getField($fieldName);
		try {
			$savepath = $field['savepath'];
			if ( $fileId ){
				
				$uploadsPath = $savepath.DIRECTORY_SEPARATOR.'uploads';
				if ( !is_dir($uploadsPath) ){
					throw new Exception("Uploads directory for field $field of table $table does not exist.");
				}
				
				$filePath = $uploadsPath.DIRECTORY_SEPARATOR.basename($fileId);
				if ( !file_exists($filePath) ){
					throw new Exception("The file does not exist.", self::CODE_NO_SUCH_FILE);
				}
				
				if ( !@unlink($filePath) ){
					throw new Exception("Failed to delete file.  There is likely a permissions issue preventing the file from being deleted.");
					
				}
			} else if ( $recordId ){
				$record = df_get_record_by_id($recordId);
				if ( !$record ){
					throw new Exception("Could not find record with id $recordId.  File could not be deleted.");
				}
				if ( !$record->checkPermission('edit', array('field'=>$fieldName)) ){
					throw new Exception('Failed to delete file because you don\'t have edit permission on this field.', self::CODE_PERMISSION_DENIED);
					
				}
				
				$val = $record->val($fieldName);
				if ( !$val ){
					throw new Exception('There was no file to delete.', self::CODE_FIELD_EMPTY);
				}
				
				$filePath = $savepath.DIRECTORY_SEPARATOR.basename($val);
				if ( file_exists($filePath) ){
					//throw new Exception("The file does not exist.", self::CODE_NO_SUCH_FILE);
					if ( !@unlink($filePath) ){
						throw new Exception("Failed to delete file.  There is likely a file-system permissions issue preventing the file from being deleted.");
						
					}
				}
				
				
				$record->setValue($fieldName, null);
				$record->save();
				
			
			} else {
				throw new Exception("Must supply either --recordId or --fileId parameter.");
			}
			
			$this->out(array(
				'code'=>200,
				'message' => 'Successfully deleted file.'
			));
		} catch (Exception $ex){
		
			if ( $ex->getCode() ){
				$this->out(array(
					'code'=>$ex->getCode(),
					'message' => $ex->getMessage()
				));
			} else {
				error_log('[ajax_upload] '.$ex->getMessage().' on line '.__LINE__.' or file '.__FILE__);
				
				$this->out(array(
					'code'=>500,
					'message' => 'Failed to delete file due to a server error.'
				));
			}
		}
	}
	
	function out($params){
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
	}
}