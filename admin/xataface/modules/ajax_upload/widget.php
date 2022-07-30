<?php
/*
 * Xataface Ajax Upload Module
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
/**
 * @brief A Dataface_FormTool wrapper for building depselect widgets in Dataface_QuickForm forms.
 *
 * All widget types require a wrapper of this kind to implement the glue between the field and the 
 * database records.  This particular wrapper only implements the buildWidget() method but
 * it could also implement pushValue() and pullValue() methods to define how data should be treated
 * when passing between Dataface_RecordObjects and the HTML_QuickForm widget.
 *
 * Note that the modules_depselect class actually registers this class with Dataface_FormTool so that
 * it knows of its existence.
 *
 */
class Dataface_FormTool_ajax_upload  {

	/**
	 * Defines how a ajax_upload widget should be built.
	 *
	 * @param Dataface_Record $record The Dataface_Record that is being edited.
	 * @param array &$field The field configuration data structure that the widget is being generated for.
	 * @param HTML_QuickForm The form to which the field is to be added.
	 * @param string $formFieldName The name of the field in the form.
	 * @param boolean $new Whether this widget is being built for a new record form.
	 * @return HTML_QuickForm_element The element that can be added to a form.
	 *
	 */
	function &buildWidget(&$record, &$field, &$form, $formFieldName, $new=false){
		$factory = Dataface_FormTool::factory();
		$mt = Dataface_ModuleTool::getInstance();
		$mod = $mt->loadModule('modules_ajax_upload');
		//$atts = $el->getAttributes();
		$widget =& $field['widget'];
		$atts = array();
		if ( !@$atts['class'] ) $atts['class'] = '';
		$atts['class'] .= ' xf-ajax-upload';
		if ( !@$atts['data-xf-table'] ){
			$atts['data-xf-table'] = $field['tablename'];
		}
		$atts['data-xf-max-file-size'] = $this->getMaxFileUploadSize($field);
		if ( @$field['allowed_extensions'] ){
			$atts['data-xf-allowed-extensions'] = $field['allowed_extensions'];
		}
		if ( @$field['allowed_mimetypes'] ){
			$atts['data-xf-allowed-mimetypes'] = $field['allowed_mimetypes'];
		}
		
		if ( @$field['disallowed_mimetypes'] ){
			$atts['data-xf-disallowed-mimetypes'] = $field['disallowed_mimetypes'];
		}
		
		if (@$field['disallowed_extensions'] ){
			$atts['data-xf-disallowed-extensions'] = $field['disallowed_extensions'];
		}
		
		$thumbnailWidth = 128;
		if ( @$widget['thumbnail_width'] ) $thumbnailWidth = intval($widget['thumbnail_width']);
		
		$thumbnailHeight = 128;
		if ( @$widget['thumbnail_height'] ) $thumbnailHeight = intval($widget['thumbnail_height']);
		
		$atts['data-xf-thumbnail-width'] = $thumbnailWidth;
		$atts['data-xf-thumbnail-height'] = $thumbnailHeight;
		
		
		$savepath = $field['savepath'];
		$s = DIRECTORY_SEPARATOR;
		$fval = $record->val($field['name']);
		if ( $fval ){
			$fpath = $savepath.$s.basename($fval);
			if ( file_exists($fpath) ){
				$atts['data-xf-file-size'] = filesize($fpath);
			}
		
		}
		
		
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->addPath(dirname(__FILE__).'/js', $mod->getBaseURL().'/js');
		
		$ct = Dataface_CSSTool::getInstance();
		$ct->addPath(dirname(__FILE__).'/css', $mod->getBaseURL().'/css');
		
		// Add our javascript
		$jt->import('xataface/modules/ajax_upload/ajax_upload.js');
		
		
		
		//$el->setAttributes($atts);
		$el = $factory->addElement('text', $formFieldName, $widget['label'], $atts);
		if ( PEAR::isError($el) ) throw new Exception($el->getMessage(), $el->getCode());
		
	
		return $el;
	}
	
	
	function getMaxFileUploadSize(&$field){
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		//echo "Upload MB: ".$upload_mb;
		$upload_bytes = $upload_mb*1000*1024;
        if (@$field['validators'] and @$field['validators']['maxfilesize'] and @$field['validators']['maxfilesize']['arg']) {
            $upload_bytes = min(intval($field['validators']['maxfilesize']['arg']), $upload_bytes);
        }
		if ( @$field['max_size'] ){
			$upload_bytes = min(intval($field['max_size']), $upload_bytes);
		}
        if (@$field['validators']['maxfilesize'])
		return $upload_bytes;
	
	}
	

}