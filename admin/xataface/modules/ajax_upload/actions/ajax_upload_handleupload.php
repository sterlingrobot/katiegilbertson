<?php
class actions_ajax_upload_handleupload {
	
	const VALIDATION_ERROR=501;

	public function handle($params){
		
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		try {
			
			if ( !@$_POST['-table'] ) throw new Exception("No table specified");
			if ( !@$_POST['--field'] ) throw new Exception("No field specified");
			
			
			$fieldName = $_POST['--field'];
			$tableName = $_POST['-table'];
			$recordId = null;
			$new = false;
			if ( @$_POST['--record-id'] ){
				$recordId = $_POST['--record-id'];
			}
			
			$record = null;
			if ( $recordId ){
				$record = df_get_record_by_id($recordId);
				if ( PEAR::isError($record) ){
					throw new Exception("Record not loaded for record id ($recordId): ".$record->getMessage());
				}
			}
			
			
			
			if ( !$record ){
				$record = new Dataface_Record($tableName, array());
				$new = true;
			}
			
			if ( is_a($record, 'Dataface_RelatedRecord') ){
				$record = $record->toRecord($record->_relationship->getTable($fieldName)->tablename);
			}
			
			if ( !$record ){
				throw new Exception("Record is null.");
			}
			
			if ( PEAR::isError($record) ) throw new Exception($record->getMessage());
			
			// At this point $record should be a Dataface_Record object
			
			if ( $new and !$record->checkPermission('new', array('field'=>$fieldName)) ){
				throw new Exception("Permission denied.  You don't have permission to add new values to this field.", 401);
				
			}
			
			
			
			if ( !$new and !$record->checkPermission('edit', array('field'=>$fieldName)) ){
				throw new Exception("Permission denied.  You don't have permission to edit this field.", 401);
			}
			
			
			
			// At this point it appears that the user has permission to set this 
			// field value
			
			// We should start to validate the file upload based on other parameters
			// in the fields.ini file (e.g. mimetype, extension, file size, etc..).
			
			$table = $record->table();
			error_log("Table is ".$table->tablename);
			$field =& $table->getField($fieldName);
			if ( PEAR::isError($field) ){
				throw new Exception("Failed to get field definition for field $fieldName");
			}
			if ( $field['Type'] != 'container' ){
				throw new Exception("The upload field '".$fieldName."' of table '".$tableName."' is not a container.  Set it to Type=container in the fields.ini file.");
			}
			
			$savePath = $field['savepath'];
			if ( !is_dir($savePath) ){
				throw new Exception("The save path for $tableName.$fieldName is set to $savePath which does not exist.  Please create this directory in order to enable file uploads.");
				
			}
			
			if ( !is_writable($savePath) ){
				throw new Exception("The save path for $tableName.$fieldName is set to $savePath which is not writable.  Please make this directory writable to proceed.");
				
			}
			
			$tmpDir = $savePath.DIRECTORY_SEPARATOR.'uploads';
			$tmpUrl = $field['url'].'/uploads';
			if ( !is_dir($tmpDir) ){
				mkdir($tmpDir);
				if ( !is_dir($tmpDir) ){
					throw new Exception("Failed to create directory $tmpDir in order to upload files.  Please check that the webserver has permission to create this directory or create this directory first and make it writable by the webserver.");
				}
			}
			
			// Clean all old entries in the temp dir.
			$files = scandir($tmpDir);
			$now = time();
			$cutOff = $now-3600;
			foreach ($files as $f){
				if ( $f == '.' or $f == '..' ) continue;
				$fpath = $tmpDir.DIRECTORY_SEPARATOR.$f;
				if ( filemtime($fpath) < $cutOff ){
					@unlink($fpath);
				}
			}
			
			
			// Now that we have a place to put the file we can get the file information 
			// and ensure that it meets all of our requirements.
			
			if ( !@$_FILES['files'] ){
				throw new Exception("No files were provided");
			}
			
			$fileInfo = array(
				'name' => $_FILES['files']['name'][0],
				'type' => $_FILES['files']['type'][0],
				'tmp_name' => $_FILES['files']['tmp_name'][0],
				'error' => $_FILES['files']['error'][0],
				'size' => $_FILES['files']['size'][0]
			);
	
				
			if ( !$fileInfo ){
				throw new Exception("No file was provided");
			}
			
			// Let's make sure the file complies with the validation parameters
			$result = array();
			if ( !$this->validate($field, $fileInfo, $result) ){
				throw new Exception("Validation failure: ".$result['message'], self::VALIDATION_ERROR);
			}
			
			
			
			$fileId = uniqid();
			$filePath = $tmpDir.DIRECTORY_SEPARATOR.$fileId;
			
			if ( !move_uploaded_file($fileInfo['tmp_name'], $filePath) ){
				throw new Exception("Failed to upload file with error code: ".$fileInfo['error']);
			}
			$fileUrl = $tmpUrl.'/'.$fileId;	
			
			// Now we serialize the file info so that it will be available later when the record
			// is saved.
			$infoPath = $filePath.'.info';
			file_put_contents($infoPath, serialize($fileInfo));
			
			// Now to generate the output
			
			$out = array();
			
			if ( !$recordId ){
				$recordId = '';
			}
			
			$out[] = array(
				'id' => $fileId,
				'name' => $fileInfo['name'],
				'size' => filesize($filePath),
				'type' => $this->getMimeType($filePath),
				'url' => $fileUrl,
				'thumbnail_url' => $this->getThumbnail($fileUrl, $filePath),
				'delete_url' => DATAFACE_SITE_HREF.'?-action=ajax_upload_delete&--file='.urlencode($fileId).'&--field='.urlencode($fieldName).'&-table='.urlencode($tableName).'&--record-id='.urlencode($recordId),
				'delete_type' => 'DELETE'
			);
			
			
			header('Vary: Accept');
			if (isset($_SERVER['HTTP_ACCEPT']) &&
				(strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
				header('Content-type: application/json');
			} else {
				header('Content-type: text/plain');
			}
			echo json_encode($out);
		} catch (Exception $ex){
		
			if ( $ex->getCode() ){
				//echo "there";exit;
				//throw $ex;
				$out = array();
				$out[] = array(
					'code'=>$ex->getCode(),
					'message' =>$ex->getMessage(),
					'error'=>1
				);
				header('Vary: Accept');
				if (isset($_SERVER['HTTP_ACCEPT']) &&
					(strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
					header('Content-type: application/json');
				} else {
					header('Content-type: text/plain');
				}
				echo json_encode($out);
					
				
			} else {
				//echo "here";exit;
				throw $ex;
			}
		}
		
	
		
		
		
	}
	
	function getMimeType($path){
		return Dataface_ModuleTool::getInstance()->loadModule('modules_ajax_upload')->getMimeType($path);

	}
	
	
	
	
	function getThumbnail($url, $path){
		return Dataface_ModuleTool::getInstance()->loadModule('modules_ajax_upload')->getThumbnail($url, $path);
		
		
	}
	
	
	
	/**
	 * @brief Validates against a field of this table.  This checks if a value is valid for this
	 * a field of this table.
	 *
	 * @param string $fieldname The name of the field
	 * @param mixed $value The value to validate for the field.
	 * @param array $params Array of parameters. This may be used to pass parameters OUT of this function.
	 *				  For example.  Setting the 'message' attribute of this array will pass out a message
	 *				  to be displayed to the user along with the error upon failed validation.
	 * @return boolean True if it validates ok, false otherwise.
	 */
	function validate(&$field, $value, &$params){
		return Dataface_ModuleTool::getInstance()->loadModule('modules_ajax_upload')->validate($field, $value, $params);
	}

}