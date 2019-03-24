<?php
/*
 * Xataface Tagger Module
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
 * @brief An AJAX action to add a tag. 
 * @param string -table The name of the source table (that contains the tagger widget).
 * @param string -field The name of the field with the tagger widget.
 * @param string -value The string that is being added as a tag.
 * @param string -src-record-id The xataface record ID of the source record (optional).  If this is omitted, then it is assumed
 *  that the source record hasn't been created yet (i.e. we are adding a tag on a new record form).
 *
 *
 * Required method: POST
 *
 * This action is consumed in the xataface/widgets/tagger.js file:  the addTag() function.
 *
 * @return Object of the form:
 * @code
 * {
 *	    code:  <int>  // The response code -- 200 for success
 *      message: <string>  // The response message
 *      label: <string>   // On success the label of the tag.
 *      recordID: <string>  // The record id of the tag that was added.
 * }
 * @endcode
 *
 * @section permissions Permissions
 *
 * This action requires the "add new related record" permission to be granted on the field's 
 * relationship.  If it is not granted, then this will return an error code (in the JSON return structure).
 *
 * 
 */
class actions_tagger_add_tag {
	/**
	 * @brief Handles the HTTP request.
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
			
			
		
			$tableName = @$_POST['-table'];
			if ( !$tableName ){
				throw new Exception("No table name specified");
			}
			
			$fieldName = @$_POST['-field'];
			if ( !$fieldName ){
				throw new Exception("No field name specified");
			}
			
			$value = @$_POST['-value'];
			if ( !$value ){
				throw new Exception("No value specified for tag.");
			}
			$configErrorStr = "Failed to add tag due to a configuration error.  See error log for details.";
			
			
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
			
			
			
				
			$srcRecordID = @$_POST['-src-record-id'];
			$srcRecord = null;
			$perms = null;
			if ( $srcRecordID ){
				$srcRecord = df_get_record_by_id($srcRecordID);
				
			}
			
			
			if ( $srcRecord ){
				$perms = $srcRecord->getPermissions(array('relationship'=>$relationshipName));
			
			} else {
				$perms = $table->getPermissions(array('relationship'=>$relationshipName));
			}
			//print_r($perms);
			$labelCol = $tagger->getLabeLColumn($field);
			
			
			$del = $table->getDelegate();
			$addMethod = $fieldName.'__addTag';
			
			if ( isset($del) and method_exists($del, $addMethod) ){
				$record = $srcRecord;
				if ( !$record ){
					$record = new Dataface_Record($table->tablename, array());
				}
				$drec = $del->$addMethod($record, $value);
			} else {
				$drec = new Dataface_Record($domainTable->tablename, array());
				$drec->setValue($labelCol, $value);
			}


			//$perms = $table->getPermissions(array('relationship'=>$relationshipName));
			$dperms = $domainTable->getPermissions();
			
			if ( !@$perms['add new related record'] /*and !@$dperms['new']*/ ){
				
				throw new Exception("Failed to add tag because you don't have sufficient permissions");
			}
			
			$res = $drec->save();
			if ( PEAR::isError($res) ){
				error_log($res->toString());
				throw new Exception("Error occurred while saving tag.  See error log for details.");
			}
			$drecCopy = df_get_record_by_id($drec->getId());
			unset($drec);
			$drec = $drecCopy;
			
			
			$label = $drec->strval($labelCol);
			
			
			
			$this->out(array(
				'code'=>200,
				'message'=>'Successfully added',
				'label'=> $label,
				'recordID'=>$drec->getId()
			));
			exit;
			
			
		
		} catch (Exception $ex){
			
			$this->out(array(
				'code'=>$ex->getCode(),
				'message'=> $ex->getMessage()
			));
			exit;
		}
	
	}
	
	/**
	 * @brief Writes JSON output.
	 *
	 * @param array $params The datastructure that should be converted to JSON and output.
	 */
	function out($params){
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
		
	}
}