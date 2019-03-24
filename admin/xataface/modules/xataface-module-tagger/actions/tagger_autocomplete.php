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
 * @brief An REST action that returns autocomplete options for a particular tagger field. 
 *
 * GET parameters:
 *
 * @param string -table The table name of the source record (that contains the tagger field).
 * @param string -field The name of the field in the source table for which we are retrieving autocomplete options.
 * @param int -new If this is set (e.g. to 1) it means that we are doing autocomplete on a new record.
 * @param int -record-id The Xataface record id of the source record (only necessary if -new=0).
 *
 * @return A JSON data structure containing a response code, a message, and the autocomplete options.  The structure of the response is as follows:
 * @code
 * {
 *		code: <int>  // The response code. 200 for success.  Anything else denotes an error.
 * 		message: <string>  // The response message.. Could be an error message.
 *		matches: [		// Array of string label matching tags.
 *			<string>,
 *			<string>
 *			... etc ...
 *		],
 *		all: <boolean>  // True if the returned matches comprise all possible results.
 *
 * @section permissions Permissions
 *
 * This action is subject to a few different permissions.  Access to all of the following permissions must be granted in order to have access to this action:
 *
 * - "add existing related record" on the field's relationship.
 * - "new" on the field if the -new flag has been set in the GET parameters.
 * - "edit" on the field if the -record-id has been specified.
 */
class actions_tagger_autocomplete {

	/**
	 * @brief Handles the HTTP request.
	 */
	function handle($params){
		session_write_close();
		header('Connection: close');
		try {
			$app = Dataface_Application::getInstance();
			
			$query = $app->getQuery();
			//print_r($query);exit;
			if ( !$query['-field'] ){
				throw new Exception("No -field parameter found in tagger_autocomplete.");
			}
			
			$table = Dataface_Table::loadTable($query['-table']);
			
			if ( PEAR::isError($table) ) throw new Exception($table->getMessage());
			
			
			$field = $table->getField($query['-field']);
			if ( PEAR::isError($field) ) throw new Exception($field->getMessage());
			
			
			if ( !@$field['relationship'] ){
				throw new Exception("No relationship for field '".$field['name']."' of table '".$table->tablename."' to load autocomplete values for the tagger widget.");
				
			}
			
			$rperms = array();
			$fperms = array();
			$record = null;
			if ( @$query['-new'] ){
				$rperms = $table->getPermissions(array(
					'relationship'=>$field['relationship']
				));
				$fperms = $table->getPermissions(array(
					'field'=>$field['name']
				));
			} else {
				$rec = df_get_record_by_id($query['-record-id']);
				$rperms = $rec->getPermissions(array(
					'relationship'=>$field['relationship']
				));
				$fperms = $rec->getPermissions(array(
					'field'=>$field['name']
				));
				$record = $rec;
			
			}
			
			if ( !@$rperms['add existing related record'] ){
				throw new Exception("Cannot obtain autocomplete information because you don't have permission to add existing records to this relationship.");
				
			}
			
			if ( @$query['-new'] and !@$fperms['new'] ){
				throw new Exception('Cannot obtain autocomplete information because you don\'t have permission to input data into this field.');
				
			}
			
			if ( !@$query['-new'] and !@$fperms['edit'] ){
				throw new Exception("Cannot obtain autocomplete information because you don't have permission to edit this field.");
			}
			
			
			
			
			
			
			
			
			if ( $field['widget']['type'] != 'tagger' ){
				throw new Exception("Attempt to get autocomplete values for the '".$field['name']."' field of the '".$table->tablename."' table but field is not set up to use the tagger widget.  Please set widget:type=tagger on this field.");
				
			}
			
			$relationship = $table->getRelationship($field['relationship']);
			if ( PEAR::isError($relationship) ){
				throw new Exception($relationship->getMessage());
			}
			
			$domainTableName = $relationship->getDomainTable();
			if ( PEAR::isError($domainTableName) ){
				throw new Exception($relationship->getMessage());
			}
			
			$domainTable = Dataface_Table::loadTable($domainTableName);
			if ( PEAR::isError($domainTable) ){
				throw new Exception($relationship->getMessage());
			}
			
			$qt = Dataface_QueryTool::loadResult($domainTableName, null, array('-skip'=>0, '-limit'=>500));
			$count = $qt->cardinality();
			
			
			$labelCol = null;
			if (  @$field['tagger_label']  ){
				$labelCol = $field['tagger_label'];
			} else {
				
				$labelCol = $domainTable->guessField(
					array('varchar'=>10, 'char'=>8, 'enum'=>3, 'text'=>1),
					array('/name|title|value/'=>10, '/nom/'=>2)
				);
				
			}
			//echo $domainTable->tablename;
			//print_r($domainTable->fields());
			//exit;
			if ( !$labelCol ){
				throw new Exception("No label column could be found for the field ".$field['name'].".  Please specify a tagger_label directive.");
				
			}
			
			//echo $labelCol.'/';
			//echo $domainTableName;exit;
			
			if ( $count < 500 ){
				// If there are less than 500, let's just load all of them
				//$qt->loadSet(array($labelCol));
				//$recs = $qt->getRecordsArray();
				$recs = df_get_records_array($domainTableName,array('-limit'=>500));
				$matches = array();
				foreach ($recs as $rec){
					$perms = array();
					if ( $record ){
						$perms = $record->getPermissions(array(
							'relationship'=>$field['relationship'],
							'domain_record'=>$rec
						));
					} else {
						$perms = $table->getPermissions(array(
							'relationship'=>$field['relationship'],
							'domain_record'=>$rec
						));
					}
					if ( @$perms['add existing related record'] ){
						$matches[] = $rec->val($labelCol);
					} 
				}
				
				$this->out(array(
					'code'=>200,
					'message'=>'Successfully loaded records',
					'matches'=>$matches,
					'all'=>true
				));
				exit;
				
			} else {
				$recs = df_get_records_array($domainTableName, array($labelCol=>$query['-search']));
				if ( PEAR::isError($recs) ){
					throw new Exception($recs->getMessage());
				}
				
				$matches = array();
				foreach ($recs as $rec){
					$perms = array();
					if ( $record ){
						$perms = $record->getPermissions(array(
							'relationship'=>$field['relationship'],
							'domain_record'=>$rec
						));
					} else {
						$perms = $table->getPermissions(array(
							'relationship'=>$field['relationship'],
							'domain_record'=>$rec
						));
					}
					if ( @$perms['add existing related record'] ){
						$matches[] = $rec->val($labelCol);
					} 
				}
				//echo "here";exit;
				$this->out(array(
					'code'=>200,
					'message'=>'Successfully loaded records',
					'matches'=>$matches,
					'all'=>false
				));
				exit;
			}
		} catch (Exception $ex){
			$this->out(array(
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage()
			));
			exit;
		}
		
		
	}
	
	
	/**
	 * Processes the JSON output.
	 * @param array $params The data structure that is to be encoded.
	 */
	function out($params){
		header('Content-type: text/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);	
	}
}