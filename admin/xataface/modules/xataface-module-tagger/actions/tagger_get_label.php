<?php
/*
 * Xataface Tagger Module v 0.1
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
 * @brief A REST HTTP action for retrieving a tag's label given information about the source record and tagger field.
 *
 * This action takes the following GET parameters:
 *
 * @param string -table The source table name.
 * @param string -src-record-id Optional source record id.
 * @param string -field The name of the source table's field (i.e. the field using the tagger widget).
 * @param string -record-id The id of the target record.
 *
 * @return This action will return a JSON object with the following structure:
 * @code
 * {
 *		code: <int>  			// The response code.  200 for success.
 * 		message: <string> 		// The response message.
 *		recordID: <string> 		// The record ID of the target record (i.e. the record that is represented by the tag).
 *		label: <string>			// The associated tag label for the record.
 *  }
 * @endcode
 */
class actions_tagger_get_label {
	/**
	 * @brief Handles HTTP request.
	 */
	function handle($params){
		session_write_close();
		header('Connection: close');
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		try {
		
			$mt = Dataface_ModuleTool::getInstance();
			$tagger = $mt->loadModule('modules_tagger');
			
			if ( PEAR::isError($tagger) ){
				error_log($tagger->toString());
				throw new Exception("Tag was not added.  Failed to load the tagger module.  See error log for details.");
			}
			
			$srcRecordID = @$_GET['-src-record-id'];
			$srcRecord = null;
			
			if ( $srcRecordID ){
				$src_record = df_get_record_by_id($srcRecordID);
			}
		
			$tableName = @$_GET['-table'];
			if ( !$tableName ){
				throw new Exception("No table name specified");
			}
			
			$fieldName = @$_GET['-field'];
			if ( !$fieldName ){
				throw new Exception("No field name specified");
			}
			
			$tagID = @$_GET['-record-id'];
			if ( !$tagID ){
				throw new Exception("No tag id specified for tag.");
			}
			
			
			
			$configErrorStr = "Failed to retrieve tag label due to a configuration error.  See error log for details.";
			
			$tagRecord = df_get_record_by_id($tagID);
			if ( !$tagRecord ){
				throw new Exception('Tag could not be found', 404);
			}
			
			
			$table = Dataface_Table::loadTable($tableName);
			if ( PEAR::isError($table) ){
				error_log($table->toString());
				throw new Exception($configErrorStr);
			}
			
			$field =& $table->getField($fieldName);
			if ( PEAR::isError($field) ){
				error_log($field->toString());
				throw new Exception($configErrorStr);
			}
			
			if ( !@$field['relationship'] ){
				error_log(sprintf(
					'Failed to add tag because the field %s of table %s does not specify a relationship.',
					$fieldName,
					$tableName
				));
				throw new Exception($configErrorStr);
			}
			
			$relationshipName = $field['relationship'];
			
			if ( @$field['widget']['type'] != 'tagger' ){
				error_log(sprintf(
					'Failed to add tag because field %s of table %s is not set up with the tagger widget.',
					$fieldName,
					$tableName
				));
				throw new Exception($configErrorStr);
			}
			
			$relationship = $table->getRelationship($relationshipName);
			if ( PEAR::isError($relationship) ){
				error_log($relationship->toString());
				throw new Exception($configErrorStr);
				
			}
			
			$domainTable = Dataface_Table::loadTable($relationship->getDomainTable());
			if ( PEAR::isError($domainTable) ){
				error_log($domainTable->toString());
				throw new Exception($configErrorStr);
			}
			
			
			$perms = null;
			if ( $srcRecord ){
				$perms = $srcRecord->getPermissions(array('relationship'=>$relationshipName));
			} else {
				$perms = $table->getPermissions(array('relationship'=>$relationshipName));
			}
			
			
			$dperms = $domainTable->getPermissions();
			
			if ( !@$perms['add new related record'] and !@$dperms['new'] ){
				
				throw new Exception("Failed to add tag because you don't have sufficient permissions", 403);
			}
			
			
			$labelColumn = $tagger->getLabeLColumn($field);
			
			
		
			$this->out(array(
				'code'=>200,
				'message'=>'Tag found',
				'recordID'=>$tagRecord->getId(),
				'label'=>$tagRecord->strval($labelColumn)
			));
			exit;
			
			
		
		} catch (Exception $e){
		
			$this->out(array(
				'code'=>$e->getCode(),
				'message'=>$e->getMessage()
			));
			exit;
		}
		
	}
	
	/**
	 * @brief Outputs JSON given a data structure input.
	 */
	function out($params){
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);	
	}
	
}