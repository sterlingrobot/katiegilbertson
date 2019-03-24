<?php
/**
 * @ingroup widgetsAPI
 */
class Dataface_FormTool_file {
	function pushValue(&$record, &$field, &$form, &$element, &$metaValues){
		// The widget is a file upload widget
		$formTool =& Dataface_FormTool::getInstance();
		$formFieldName = $element->getName();
		$table =& $record->_table;
		$app =& Dataface_Application::getInstance();
		if ( $element->isUploadedFile() ){
		    $cachePath = $app->_conf['cache_dir'].'/'.basename($app->_conf['_database']['name']).'-'.basename($table->tablename).'-'.basename($field['name']).'-';

			$cachedFiles = glob($cachePath.'*');
			foreach ($cachedFiles as $cachedFile){
				@unlink($cachedFile);
			}
			// Need to delete the cache for this field

			// a file has been uploaded
			$val = $element->getValue();
				// eg: array('tmp_name'=>'/path/to/uploaded/file', 'name'=>'filename.txt', 'type'=>'image/gif').
			if ( PEAR::isError($val) ){
				$val->addUserInfo(
					df_translate(
						'scripts.Dataface.QuickForm.pushValue.ERROR_GETTING_ELEMENT_VALUE',
						"Error getting element value for element $field[name] in QuickForm::pushField ",
						array('fieldname'=>$field['name'],'line'=>0,'file'=>'')
						)
					);
				throw new Exception($val->toString(), E_USER_ERROR);
				return $val;
			}



			if ( $table->isContainer($field['name']) ){
				$src = $record->getContainerSource($field['name']);
				if ( strlen($record->strval($field['name']) ) > 0  // if there is already a valud specified in this field.
					and file_exists($src)	// if the old file exists
					and is_file($src)  // make sure that it is only a file we are deleting
					and !is_dir($src)  // don't accidentally delete a directory
				){
					// delete the old file.
					if ( !is_writable($src) ){
						throw new Exception("Could not save field '".$field['name']."' because there are insufficient permissions to delete the old file '".$src."'.  Please check the permissions on the directory '".dirname($src)."' to make sure that it is writable by the web server.", E_USER_ERROR);
					}
					@unlink( $src);
				}

				// Make sure that the file does not already exist by that name in the destination directory.
				$savepath = $field['savepath'];
				$filename = basename($val['name']);	// we use basename to guard against maliciously named files.
				$filename = str_replace(chr(32), "_", $filename);
				$matches = array();
				if ( preg_match('/^(.*)\.([^\.]+)$/', $filename, $matches) ){
					$extension = $matches[2];
					$filebase = $matches[1];
				} else {
					$extension = '';
					$filebase = $filename;
				}
				while ( file_exists( $savepath.'/'.$filename) ){
					$matches = array();
					if ( preg_match('/(.*)-{0,1}(\d+)$/', $filebase, $matches) ){
						$filebase = $matches[1];
						$fileindex = intval($matches[2]);
					}
					else {
						$fileindex = 0;
						// We should just leave the filebase the same.
						//$filebase = $filename;

					}
					if ( $filebase{strlen($filebase)-1} == '-' ) $filebase = substr($filebase,0, strlen($filebase)-1);
					$fileindex++;
					$filebase = $filebase.'-'.$fileindex;
					$filename = $filebase.'.'.$extension;
				}

				if (!is_writable( $field['savepath']) ){
					throw new Exception(
						df_translate(
							'scripts.Dataface.QuickForm.pushValue.ERROR_INSUFFICIENT_DIRECTORY_PERMISSIONS',
							"Could not save field '".$field['name']."' because there are insufficient permissions to save the file to the save directory '".$field['savepath']."'. Please Check the permissions on the directory '".$field['savepath']."' to make sure that it is writable by the web server.",
							array('fieldname'=>$field['name'], 'savepath'=>$field['savepath'])
							), E_USER_ERROR);
				}
				$filePath = $field['savepath'].'/'.$filename;
				move_uploaded_file($val['tmp_name'], $filePath);
				chmod($filePath, 0744);

				// Now transform the image
				if (@$field['transform']) {
					$commands = array_map('trim', explode(';', $field['transform']));
					foreach ($commands as $command) {
						if (!trim($command)) {
							continue;
						}
						list($nameAndOp, $arg) = array_map('trim', explode(':', $field['transform']));
						if (!$nameAndOp) {
							throw new Exception("No name/op specified for field transform.");
						}
						if (!$arg) {
							throw new Exception("No argument provided for transform ".$nameAndOp);
						}
						$op = null;
						list($thumbName, $op) = @explode(' ', $nameAndOp);
						if (!$thumbName) {
							throw new Exception("No name provided for transform operation ".$command);

						}

						if (!$op) {
							$op = $thumbName;
							$thumbName = "default";
						}

						$thumbDir = $field['savepath'].'/'.basename($thumbName);
						if (!file_exists($thumbDir)) {
							if (!mkdir($thumbDir)) {
								throw new Exception("Failed to create directory ".$thumbDir);
							}
						}

						$thumbPath = $thumbDir.'/'.$filename;
						if (file_exists($thumbPath)) {
							if (!unlink($thumbPath)) {
								throw new Exception("Failed to delete old thumbnail ".$thumbPath);
							}
						}

						import('xf/image/crop.php');
						$crop = new \xf\image\Crop;

						list($dimensions) = array_map('trim', explode(' ', $arg));
						list($maxWidth, $maxHeight) = array_map('intval', explode('x', $dimensions));


						switch ($op) {
							case 'fit' :
								// we fit the image to the given dimensions
								$crop->fit($filePath, $thumbPath, $maxWidth, $maxHeight);
								break;
							case 'fill' :
								// we fill the given dimensions with the image
								$crop->fill($filePath, $thumbPath, $maxWidth, $maxHeight);
								break;
						}

						if ($thumbName == 'default') {
							copy($thumbPath, $filePath);
							unlink($thumbPath);
							chmod($filePath, 0744);
						} else {
							chmod($thumbPath, 0744);
						}
					}
				}

				$out = $filename;
				$element->last_pushed_value = $out;

			} else {
				if ( file_exists($val['tmp_name']) ){
					if ( !@$app->_conf['multilingual_content'] ){
						// THis is a bit of a hack.  If we are using multilingual
						// content, then Dataface_DB will parse every query
						// before sending it to the database.  It is better if
						// that query is short - so we only pass the whole value
						// if we are not parsing the query.
						$out = file_get_contents($val['tmp_name']);
					} else {
						// If we are parsing the query, then we will just store
						// the path to the blob.
						$out = $val['tmp_name'];
					}

				} else {
					$out = null;
				}
				$element->last_pushed_value = $out;
			}

			if ( is_array( $metaValues ) ){
				if ( isset( $field['filename'] ) ){
					// store the file name in another field if one is specified
					$metaValues[$field['filename']] = $val['name'];

				}
				if ( isset( $field['mimetype'] ) ){
					// store the file mimetype in another field if one is specified
					$metaValues[$field['mimetype']] = $val['type'];

				}
			}

			return $out;


		}

		if ( $table->isContainer($field['name']) ){
		    if (isset($element->last_pushed_value) and !empty($element->last_pushed_value)) {
                return $element->last_pushed_value;
            }
			return $record->val($field['name']);
		}
		return null;

	}

	function pullValue(&$record, &$field, &$form, &$element){
		/*
		 *
		 * We don't bother pulling the values of file widgets because it would take too long.
		 *
		 */

		$widget =& $field['widget'];
		$formFieldName = $element->getName();

		$val = null;
		if ( $widget['type'] == 'webcam' ) $val = $record->getValueAsString($field['name']);
		if ( $record->getLength($field['name']) > 0 ){
			// there is already a file set, let's add a preview to it
			if ( $record->isImage($field['name']) ){
				$element->setProperty('image_preview', df_absolute_url($record->q($field['name'])));
			}
			$element->setProperty('preview', df_absolute_url($record->q($field['name'])));
			//echo "Adding preview for field '$fieldname':".$record->qq($fieldname);
		} else {
			//echo "No data in field '$fieldname'";
		}

		return $val;
	}

}
