<?php
class actions_ajax_upload_get_thumbnail {
	
	const PERMISSION_DENIED = 401;
	
	function handle($params){
	
	
		session_write_close();
		header('Connection:close');
		
		$app = Dataface_Application::getInstance();
		$query =& $app->getQuery();
		$mod = Dataface_ModuleTool::getInstance()->loadModule('modules_ajax_upload');
		$baseUrl = $mod->getBaseURL();
		$basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
		
		
		
		if ( !@$query['--field'] ) throw new Exception("no field specified");
		if ( !@$query['-table'] ) throw new Exception("no table specified");
		
		$recordId = @$query['--recordId'];

		
		$record = null;
		if ( $recordId ) $record = df_get_record_by_id($recordId);
		
		if ( !$record ) $record = new Dataface_Record($query['-table'], array());
		
		$table = Dataface_Table::loadTable($query['-table']);
		//if ( is_a($record, 'Dataface_Record') and $table->tablename != $record->table()->tablename ){
		//	throw new Exception("Table does not match record.");
		//} else if ( is_a($record, 'Dataface_RelatedRecord') and 
		
		if ( !$table->hasField($query['--field']) ){
			throw new Exception("The specified field does not exist");
		}
		
		if (!$record->checkPermission('view', array('field'=>$query['--field'])) ){
			throw new Exception("You don't have permission to view this field.", self::PERMISSION_DENIED);
		}
		
		$fieldVal = null;
		
		if ( @$query['--tempfileid'] ){
			$fieldVal = $query['--tempfileid'];
		} else {
			$fieldVal = $record->val($query['--field']);
			//echo "Field val: ".$fieldVal.']';
		}
		
		$s = DIRECTORY_SEPARATOR;
		
		$filePath = $basePath.$s.'images'.$s.'file_not_found.png';
		
		$field =& $table->getField($query['--field']);
		$fieldBaseDir = $field['savepath'];
		if ( @$query['--tempfileid'] ){
			$fieldBaseDir .= $s.'uploads';
		}
		
		if ( $fieldVal ){
			
			$testPath = $fieldBaseDir.$s.$fieldVal;
			if ( file_exists($testPath) ){
				// was $filePath = basename($testPath);
				$filePath = $testPath;
				
				
				$mimetype = $mod->getMimeType($filePath);
				if ( !preg_match('/^image\//', $mimetype) ){
					$filePath = $mod->getThumbnail(null, $filePath);
				}
			}
		}
		//echo $filePath;exit;
		
		$width = 75;
		if ( @$query['--max_width'] ){
			$width = intval($query['--max_width']);
		}
		$height = 75;
		if ( @$query['--max_height'] ){
			$height = intval($query['--max_height']);
		}
		
		
		require_once 'lib/thumbnail.lib.php';
		Xataface_Thumbnail::outputThumbnail($filePath, $width, $height);
		
		
	}
}