<?php
/*
 * Xataface Depselect Module
 * Copyright (C) 2011  Steve Hannah <steve@weblite.ca>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Library General Public License for more details.
 *
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301, USA.
 *
 */
if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}
/**
 * @brief The main depselect module class.  This loads all of the dependencies for the
 * 	module.
 *
 * Of note, this module depends on the XataJax module for the loading
 * of its javascripts.
 *
 */
 class modules_ajax_upload {
	/**
	 * @brief The base URL to the depselect module.  This will be correct whether it is in the
	 * application modules directory or the xataface modules directory.
	 *
	 * @see getBaseURL()
	 */
	private $baseURL = null;

	private $pathsRegistered = false;

	/**
	 * @brief Initializes the depselect module and registers all of the event listener.
	 *
	 */
	function __construct(){
		$app = Dataface_Application::getInstance();


		// Now work on our dependencies
		$mt = Dataface_ModuleTool::getInstance();

		// We require the XataJax module
		// The XataJax module activates and embeds the Javascript and CSS tools
		$mt->loadModule('modules_XataJax', 'modules/XataJax/XataJax.php');


		// Register the tagger widget with the form tool so that it responds
		// to widget:type=tagger
		import('Dataface/FormTool.php');
		$ft = Dataface_FormTool::getInstance();
		$ft->registerWidgetHandler('ajax_upload', dirname(__FILE__).'/widget.php', 'Dataface_FormTool_ajax_upload');

		$app->registerEventListener('beforeSave', array($this, 'beforeSave'));

	}

	function registerPaths(){
		if ( !$this->pathsRegistered ){
			Dataface_JavascriptTool::getInstance()
				->addPath(dirname(__FILE__).'/js', $this->getBaseURL().'/js');
			Dataface_CSSTool::getInstance()
				->addPath(dirname(__FILE__).'/css', $this->getBaseURL().'/css');
		}
	}

	function block__head_slot(){
		echo '<script>XATAFACE_MODULES_AJAX_UPLOAD_URL='.json_encode($this->getBaseURL()).';</script>';
	}


	/**
	 * @brief Returns the base URL to this module's directory.  Useful for including
	 * Javascripts and CSS.
	 *
	 */
	public function getBaseURL(){
		if ( !isset($this->baseURL) ){
			$this->baseURL = Dataface_ModuleTool::getInstance()->getModuleURL(__FILE__);
		}
		return $this->baseURL;
	}



	public function beforeSave($event){
		$record = $event[0];
		if ( $record ){
			foreach ($record->table()->fields(false, true, true) as $fld){
				if ( @$fld['widget']['type'] == 'ajax_upload' and $record->valueChanged($fld['name']) ){
					$this->moveUploadedFile($record, $fld['name']);

				}
			}
		}

	}




	public function moveUploadedFile(Dataface_Record $record, $fieldName){

		$val = $record->val($fieldName);
		if ( !preg_match('/^xftmpimg:\/\//', $val) ){
			// This isn't a temp file == do nothing
			return;
		}
		$val = substr($val, 11);
		$field =& $record->table()->getField($fieldName);
		$tmpPath = $field['savepath'].DIRECTORY_SEPARATOR.'uploads';
		$filePath = $tmpPath.DIRECTORY_SEPARATOR.basename($val);
		$infoPath = $filePath.'.info';

		if ( !file_exists($filePath) ){
			throw new Exception("Upload to field $fieldName failed.  The temp file $filePath could not be found.");
		}

		if ( !file_exists($infoPath) ){
			throw new Exception("Upload to field $fieldName failed.  The info file $infoPath for file $filePath could not be found.");
		}

		$infoArr = unserialize(file_get_contents($infoPath));
		if ( !is_array($infoArr) ){
			throw new Exception("Upload to field $fieldName failed.  The info file $infoPath is expected to contain an array but contained something else.");

		}

		$destFileName = basename($infoArr['name']);
		if ( !$destFileName ){
			throw new Exception("No file name specified for uploaded file with id $val");
		}

		$pathinfo = pathinfo($destFileName);
		$filebase = $pathinfo['filename'];
		$extension = $pathinfo['extension'];
		$savepath = $field['savepath'];

		while ( file_exists( $savepath.DIRECTORY_SEPARATOR.$destFileName) ){
			$pathinfo = pathinfo($destFileName);
			$filebase = $pathinfo['filename'];
			$extension = $pathinfo['extension'];

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
			$destFileName = $filebase.'.'.$extension;
		}

		$infoArr['name'] = $destFileName;

		/*
		No validation or permissions checking because this is the wrong place to do it.
		Permissions are checked by QuickForm when the value is attempted to be changed.
		$res = array();

		if ( !$this->validate($field, $infoArr, $res) ){
			throw new Exception("Failed to upload file because validation failed: ".$res['message']);
		}
		*/

		if ( !copy($filePath, $savepath.DIRECTORY_SEPARATOR.$destFileName) ){
			throw new Exception("Failed to move uploaded file into upload directory.");
		}

		$record->setValue($fieldName, $destFileName);
		if ( @$infoArr['type'] and @$field['mimetype'] ){
			$record->setValue($field['mimetype'], $infoArr['type']);
		}



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


		// This bit of validation code is executed for files that have just been uploaded from the form.
		// It expects the value to be an array of the form:
		// eg: array('tmp_name'=>'/path/to/uploaded/file', 'name'=>'filename.txt', 'type'=>'image/gif').

		if ( !is_array(@$field['allowed_extensions']) and @$field['allowed_extensions']){
			$field['allowed_extensions'] = explode(',',@$field['allowed_extensions']);
		}
		if ( !is_array(@$field['allowed_mimetypes']) and @$field['allowed_mimetypes'] ){
			$field['allowed_mimetypes'] = explode(',',@$field['allowed_mimetypes']);
		}
		if ( !is_array(@$field['disallowed_extensions']) and @$field['disallowed_extensions'] ){
			$field['disallowed_extensions'] = explode(',',@$field['disallowed_extensions']);
		}
		if ( !is_array(@$field['disallowed_mimetypes']) and @$field['disallowed_extensions']){
			$field['disallowed_mimetypes'] = explode(',',@$field['disallowed_mimetypes']);
		}

		$field['allowed_extensions'] = @array_map('strtolower', @$field['allowed_extensions']);
		$field['allowed_mimetypes'] = @array_map('strtolower', @$field['allowed_mimetypes']);
		$field['disallowed_extensions'] = @array_map('strtolower', @$field['disallowed_extensions']);
		$field['disallowed_mimetypes'] = @array_map('strtolower', @$field['disallowed_mimetypes']);
		// We do some special validation for file uploads
		// Validate -- make sure that it is the proper mimetype and extension.
		if ( is_array( @$field['allowed_mimetypes'] ) and count($field['allowed_mimetypes']) > 0 ){
			if ( !in_array($value['type'], $field['allowed_mimetypes']) ){
				$params['message'] = "The file submitted in field '".$field['name']."' is not the correct type.  Received '".$value['type']."' but require one of (".implode(',', $field['allowed_mimetypes']).").";

				return false;
			}
		}

		if ( @is_array(@$field['disallowed_mimetypes']) and in_array($value['type'], $field['disallowed_mimetypes']) ){
			$params['message'] = "The file submitted in field '".$fieldname."' has a restricted mime type.  The mime type received was '".$value['type']."'.";
			return false;
		}

		$extension = '';
		$matches = array();
		if ( preg_match('/\.([^\.]+)$/', $value['name'], $matches) ){
			$extension = $matches[1];
		}
		$extension = strtolower($extension);


		if ( is_array( @$field['allowed_extensions'] ) and count($field['allowed_extensions']) > 0 ){
			if ( !in_array($extension, $field['allowed_extensions']) ){
				$params['message'] = "The file submitted does not have the correct extension.  Received file has extension '".$extension."' but the field requires either ".implode(' or ', $field['allowed_extensions']).".";

				return false;
			}
		}

		if ( @is_array( @$field['disallowed_extensions'] ) and in_array($extension, $field['disallowed_extensions']) ){
			$params['message'] = "The file submitted in field '".$fieldname."' has a restricted extension.  Its extension was '".$extension."' which is disallowed for this form.";
			return false;
		}

		if ( @$field['max_size'] and intval($field['max_size']) < intval(@$value['size']) ){
			$params['message'] = "The file submitted in field '".$fieldname."' is {$value['size']} bytes which exceeds the limit of {$field['max_size']} bytes for this field.";
			return false;
		}


		//$delegate =& $this->getDelegate();
		//if ( $delegate !== null and method_exists($delegate, $fieldname."__validate") ){
		//	/*
		//	 *
		//	 * The delegate defines a custom validation method for this field.  Use it.
		//	 *
		//	 */
		//	return call_user_func(array(&$delegate, $fieldname."__validate"), $this, $value, $params);
		//}
		return true;
	}


	function getMimeType($path){





		$mimetype='';
		if(function_exists('finfo_open')) {
			$res = finfo_open(FILEINFO_MIME); /* return mime type ala mimetype extension */
			$mimetype = finfo_file($res, $path);
		} else if (function_exists('mime_content_type')) {


			$mimetype = mime_content_type($path);

		}
		if (!$mimetype) {
			$mimetype = 'application/octet-stream';
		}

		return $mimetype;



	}




	function getThumbnail($url, $path){
		if ( isset($url) ){
			$baseUrl = $this->getBaseURL();
		} else {
			$baseUrl = dirname(__FILE__);
		}


		$mime = $this->getMimeType($path);
		if ( !$mime ) return $baseUrl.'/images/document_icon.png';

		if ( preg_match('/^image\//', $mime) ){
			if ( true or preg_match('/'.preg_quote(DATAFACE_SITE_URL.'/media/photos/', '/').'/', $url) ){
				return $url .= '?max_width=128&max_height=128';
			} else {
				return $baseUrl.'/images/image_icon.png';
			}
		} else if ( preg_match('/^audio\//', $mime) ){
			return $baseUrl.'/images/audio_icon.png';

		} else if ( preg_match('/^video\//', $mime) ){
			return $baseUrl.'/images/video_icon.png';
		} else if ( preg_match('/msword/', $mime) ){
			return $baseUrl.'/images/msword_icon.png';

		} else if ( preg_match('/midi/', $mime) ){

			return $baseUrl.'/images/midi_icon.png';

		} else if ( preg_match('/powerpoint/', $mime) ){

			return $baseUrl.'/images/ppt_icon.png';
		} else if ( preg_match('/wordperfect/', $mime) ){
			return $baseUrl.'/images/rtf_icon.png';
		} else if ( preg_match('/excel/', $mime) ){
			return $baseUrl.'/images/excel_icon.png';
		} else if ( preg_match('/xml/', $mime) ){

			return $baseUrl.'/images/xml_icon.png';
		} else {

			switch ($mime){

				case 'text/html':
					return $baseUrl.'/images/html_icon.png';
				case 'text/css':
					return $baseUrl.'/images/css_icon.png';
				case 'application/x-compressed':
				case 'application/x-gzip':
				case 'multipart/x-gzip':
				case 'application/zip':
				case 'application/x-zip':
				case 'application/x-zip-compressed':
					return $baseUrl.'/images/zip_icon.png';
				case 'application/rtf':
				case 'application/x-rtf':
				case 'text/richtext':
					return $baseUrl.'/images/rtf_icon.png';

				default:
					return $baseUrl.'/images/document_icon.png';




			}
		}

	}
}
