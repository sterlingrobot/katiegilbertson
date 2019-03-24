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
 * @brief A module to add a "tagger" widget to Xataface.  This allows many-to-many
 * relationships to be edited on the edit form of the source record in a simple
 * tag-like environment.
 *
 * @see Dataface_FormTool_tagger For the actual widget building definition.	
 *
 * 
 * @depends modules_XataJax
 */
class modules_tagger {

	/**
	 * @brief The base URL to the tagger module.  This will be correct whether it is in the 
	 * application modules directory or the xataface modules directory.
	 *
	 * @see getBaseURL()
	 */
	private $baseURL = null;
	
	
	/**
	 * @brief Initializes the tagger module and registers all of the event listener.
	 *
	 */
	function __construct(){
		$app = Dataface_Application::getInstance();
		
		// Register the beforeSave event handler to be called before any records
		// in the system are saved.
		$app->registerEventListener('beforeSave', array($this, 'beforeSave'));
		
		// Register the afterSave event handler to be called after any records
		// are saved.
		$app->registerEventListener('afterSave', array($this, 'afterSave'));
		
		// Register the initTransientField event handler which is called 
		// when transient field data is loaded for the first time.
		$app->registerEventListener('initTransientField', array($this, 'initField'));
		
		// Now work on our dependencies
		$mt = Dataface_ModuleTool::getInstance();
		
		// We require the XataJax module
		// The XataJax module activates and embeds the Javascript and CSS tools
		$mt->loadModule('modules_XataJax', 'modules/XataJax/XataJax.php');
		
		
		// Register the tagger widget with the form tool so that it responds
		// to widget:type=tagger
		import('Dataface/FormTool.php');
		$ft = Dataface_FormTool::getInstance();
		$ft->registerWidgetHandler('tagger', dirname(__FILE__).'/widget.php', 'Dataface_FormTool_tagger');
		
		
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
	
	


	
	/**
	 * @brief Returns the name of the column that we are using for the label of a tag 
	 * for a given field.
	 *
	 * @param array $field A Xataface field definition array.
	 */
	function getLabelColumn($field){
		$labelCol = null;
		if (  @$field['tagger_label']  ){
			$labelCol = $field['tagger_label'];
		} else {
			$relationship = @$field['relationship'];
			if ( !$relationship ){
				throw new Exception("Label column could not be found for field $field[name] because no relationship was specified.");
				
			}
			$table = Dataface_Table::loadTable($field['tablename']);
			if ( PEAR::isError($table) ){
				throw new Exception($table->getMessage());
			}
			$relObj = $table->getRelationship($relationship);
			
			if ( PEAR::isError($relObj) ){
				throw new Exception($relObj->getMessage());
			}
			$domainTable = Dataface_Table::loadTable($relObj->getDomainTable());
			if ( PEAR::isError($domainTable) ){
				throw new Exception($domainTable->getMessage());
			}
			$labelCol = $domainTable->guessField(
				array('varchar'=>10, 'char'=>8, 'enum'=>3, 'text'=>1),
				array('/name|title|value/'=>10, '/nom/'=>2)
			);
			
		}
		
		if ( !$labelCol ){
			throw new Exception("No label column could be found for the field ".$tfield['name'].".  Please specify a tagger_label directive.");
			
		}
		return $labelCol;
	}
	
	
	/**
	 * @brief Handler for the initTransientField event.  This loads the 
	 * tags into a transient field for the first time.
	 *
	 * @param stdClass $event An event object.  Contains record, field, and out properties.
	 * @return void
	 */
	function initField($event){
		
		//print_r($event->field);exit;
		
		if ( $event->field['widget']['type'] == 'tagger' ){
			//die('here');
			$relationship = @$event->field['relationship'];
			if ( !$relationship ){
				$event->out = null;
				return;
			}
			$rrecs = $event->record->getRelatedRecordObjects($relationship, 'all');
			$out = array();
			try {
				$labelCol = $this->getLabelColumn($event->field);
				foreach ($rrecs as $rrec){
					$drec = $rrec->toRecord();
					$out[] = 'xfid://'.$drec->getId().' '.$drec->strval($labelCol);
					
					unset($drec);
				}
				$event->out = implode("\n", $out);
				return;
			} catch ( Exception $ex){
				error_log($ex->getMessage());
				$event->out = null;
				return;
			}
			
		} 
	}

	/**
	 * @brief Handler for beforeSave event.  Basically tallies up all of the
	 * tagger fields that need to be processed.
	 *
	 * @param array $params Parameters.  First element is the record, 2nd element is the IO object.
	 */
	function beforeSave($params){
		$record = $params[0];
		if ( $record ){
			
			foreach ($record->_table->fields(false, true, true) as $fld){
			
				// Go through all fields in the table to see if any of them
				// are tagger widgets.... we collect them all and record
				if ( @$fld['relationship'] and @$fld['widget']['type'] == 'tagger' and $record->valueChanged($fld['name']) ){
					// Only mark the field for handling if the widget:type=tagger
					// and a relationship is specified and the value has changed.
					if ( !@$record->pouch['tagger__fields'] ){
						$record->pouch['tagger__fields'] = array();
					}
					
					// We store the fields in the record pouch so that we can access them'
					// in the afterSave handler
					$record->pouch['tagger__fields'][] = $fld['name'];
				}
			}
		}
	}
	
	
	/**
	 * @brief Handler for the afterSave() event.  This goes through all changed tagger
	 *  fields and saves any changes to the database.
	 *
	 * The tagger works by going through all of the related records in relationship
	 * marked in the tagger field, and adding any related records that haven't been
	 * previously added and removing ones that have been removed.
	 *
	 * This works similar to the checkbox widget on transient fields.
	 *
	 * @param array $params Array of parameters.  First element is the record, 2nd element is the IO object.
	 */
	function afterSave($params){
		$record = $params[0];
		$io = $params[1];
		
		// The tagger__fields array was populated in the beforeSave handler
		// with the fields that have changed - and use the tagger widget.
		if ( @$record->pouch['tagger__fields'] ){
			foreach ($record->pouch['tagger__fields'] as $f){
				$tfield =& $record->_table->getField($f);
				
				$val = $record->val($f);
                                
				$relationship = $record->_table->getRelationship($tfield['relationship']);
				$domainTable = Dataface_Table::loadTable($relationship->getDomainTable());
				if ( PEAR::isError($domainTable) ){
					return $domainTable;
				}
				if (is_array($val)) {
				    $val = implode("\n", $val);
				}
				if ( trim($val) ){
                    $tval = explode("\n", $val);
                } else {
                    $tval = array();
                }
				
				// Load existing records in the relationship
				$texisting =& $record->getRelatedRecordObjects($tfield['relationship'], 'all');
				if ( !is_array($texisting) or PEAR::isError($texisting) ){
					error_log('Failed to get related records for record '.$record->getId().' in its relationship '.$tfield['relationship']);
					unset($tval);
					unset($orderCol);
					unset($tval_new);
					unset($torder);
					unset($trelationship);
					unset($tval_existing);
					unset($relationship);
					unset($domainTable);
					continue;
				}
				//$texistingIds = array();
				$texistingLabels = array();
				$labelMap = array();
				$labelCol = $this->getLabelColumn($tfield);
				
				if ( !$labelCol ){
					throw new Exception("No label column could be found for the field ".$tfield['name'].".  Please specify a tagger_label directive.");
					
				}
				
				
				foreach ($texisting as $terec){
					$drec = $terec->toRecord();
					$texistingLabels[$drec->getId()] = trim($terec->val($labelCol));
					$labelMap[$drec->getId()] = $terec;
					unset($drec);
					
				}
				
				$del = $record->_table->getDelegate();
				$addMethod = $f.'__addTag';
				
				
				
				// Load currently checked records
				
				// Now we have existing ids in $texistingIds
				// and checked ids in $tcheckedIds
				
				// See which records we need to have removed
				$texistingLabels = array_map('trim', $texistingLabels);
				$tval = array_map('trim', $tval);
				$temp = array();
				foreach ($tval as $k=>$v){
					if ( preg_match('/^xfid:\/\/([^ ]+) (.*)$/', $v, $matches) ){
						$temp[$matches[1]] = $matches[2];
					} else {
						
						$drec = df_get_record($domainTable->tablename, array($labelCol=>'='.$v));
						if ( !$drec ){
							// the record isn't created yet.. so let's create it
							if ( isset($del) and method_exists($del, $addMethod) ){
								$drec = $del->$addMethod($record, $v);
							} else {
								$drec = new Dataface_Record($domainTable->tablename, array());
								$drec->setValue($labelCol, $v);
							}
							if ( !$record->checkPermission('add new related record', array(
								'relationship' => $tfield['relationship']
							))){
								$res = PEAR::raiseError("Failed to add tag '$val' because you don't have permission to add new related records to this relationship.");
								
							} else {
								$res = $drec->save();
							}
							
							if ( PEAR::isError($res) ){
								return PEAR::raiseError('Failed save tags for field "'.$tfield['widget']['label'].'" because the tag "'.$v.'" could not be saved.  The error while trying to save the tag was: '.$res->getMessage(), DATAFACE_E_NOTICE);
								
							}
						}
						$temp[$drec->getId()] = $v;
						
						unset($drec);
					}
				}
				$tval = $temp;
				
				
				$existingIds = array_keys($texistingLabels);
				$newIds = array_keys($tval);
				
				$tremoves = array_diff($existingIds, $newIds);
				$tadds = array_diff($newIds, $existingIds);
				
				foreach ($tremoves as $tid){
					$trec = $labelMap[$tid];
                                        $res = $io->removeRelatedRecord($trec, false, true/*secure*/);
					if ( PEAR::isError($res) ) return $res;
					unset($trec);
				}
				
				foreach ($tadds as $tid){
					$drec = df_get_record_by_id($tid);
					if ( !$drec ){
						
						return PEAR::raiseError('Failed to add tag '.$tid.' because it could not be found.');
						
					}
				
					//$trecvals = $checkedId2ValsMap[$tid];
					$trec = new Dataface_RelatedRecord($record, $tfield['relationship'], $drec->vals());
					
					$res = $io->addExistingRelatedRecord($trec, true);
					if ( PEAR::isError($res) ) return $res;
					unset($drec, $trec);
				}
				
				unset($tadds);
				unset($tremoves);
				unset($tcheckedIds, $tcheckedId2ValsMap);
				unset($tcheckedRecords);
				unset($tchecked);
				unset($texistingIds);
				unset($texisting);
					
			}
			
			unset($record->pouch['tagger__fields']);
		}
	}
	
	/**
	 * @brief Utility method to check if a form data structure (as passed to
	 * the Dataface_Form_Template.html template) contains any tagger fields.
	 *
	 * @param array &$form The form data structure.
	 *
	 * @return boolean True if the form contains a tagger field.
	 */
	private function formRequiresTagger(&$form){
		
		if ( @$form['elements'] and is_array($form['elements']) ){
			foreach ($form['elements'] as $e ){
				if ( @$e['field']['widget']['type'] == 'tagger' ){
					return true;
				}
			}
		}
		
		if ( @$form['sections'] and is_array($form['sections']) ){
			foreach ($form['sections'] as $s ){
				if ( $s['elements'] and is_array($s['elements']) ){
					foreach ($s['elements'] as $e ){
						if ( @$e['field']['widget']['type'] == 'tagger' ){
							return true;
						}
					}
				}
			}
		}
		
		return false;
		
	}
	
	/**
	 * @brief Fills the after_form_open_tag block to add tagger javascripts to the form
	 * appropriately.
	 *
	 * @param array $params An associative array of the Smarty tag parameters.  This
	 * block expects at least the following data structure:
	 * @code
	 * array(
	 *     'form' => <array> // The form data structure as passed to the Dataface_Form_Template.html template
	 * )
	 * @endcode
	 */
	function block__after_form_open_tag($params=array()){
	
		$form = $params['form'];
		if ( !$this->formRequiresTagger($form) ){
			return null;
		}
		
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->addPath(dirname(__FILE__).'/js', $this->getBaseURL().'/js');
		
		$ct = Dataface_CSSTool::getInstance();
		$ct->addPath(dirname(__FILE__).'/css', $this->getBaseURL().'/css');
		
		// Add our javascript
		$jt->import('xataface/widgets/tagger.js');
		
	}
	
	/**
	 * @brief Returns the subset of fields input that are marked as tag cloud fields.
	 * Fields that are marked with tag_cloud=1 in the fields.ini file are considered
	 * to be tag cloud fields.
	 *
	 * @param array $fields Field definition array (from fields.ini file).
	 * @return array All of the field definitions that have tag_cloud=1
	 *
	 * @see Dataface_Table::fields()
	 */
	function getTagCloudFields($fields){
		$out = array();
		foreach ($fields as $k=>$f){
			if ( @$f['tag_cloud'] ){
				$out[$k] =& $f;
			}
			unset($f);
		}
		return $out;
	}
	
	
	/**
	 * @brief Renders a tag cloud for a transient field.
	 *
	 * @param array $field A field definition.  This definition must define
	 * a relationship, be marked transient=1, and be marked tag_cloud=1
	 *
	 * @return void
	 *
	 * @see getTagCloudFields()
	 * @see drawScalarTagCloud()
	 */
	function drawTransientTagCloud($field){
		
		$table = Dataface_Table::loadTable($field['tablename']);
		$perms = $table->getPermissions(array('relationship'=>$field['relationship']));
		if ( !@$perms['view related records'] ){
			return;
		}
		$relationship = $table->getRelationship($field['relationship']);
		
		try {
			$labelCol = $this->getLabelColumn($field);
		} catch (Exception $ex){
			return;
		}
		
		$sql = $relationship->getSQL();
		
		$frompos = strpos(strtolower($sql), ' from ');
		//echo "Frompos $frompos";
		$select = 'select count(*) as num, `'.$labelCol.'`';
		$newsql = $select.' '.substr($sql, $frompos);
		$newsql = preg_replace('/`([^`]+)` ?= ?\'\$[^\']+\'/', '`$1` is not null', $newsql);
		$newsql .= ' group by `'.$labelCol.'` limit 50';
		//echo $newsql;
		$res = xf_db_query($newsql, df_db());
		if ( !$res ) throw new Exception(xf_db_error(df_db()));
		$jt = Dataface_JavascriptTool::getInstance();
		$jt->addPath(dirname(__FILE__).'/js', $this->getBaseURL().'/js');
		
		$ct = Dataface_CSSTool::getInstance();
		$ct->addPath(dirname(__FILE__).'/css', $this->getBaseURL().'/css');
		
		// Add our javascript
		$jt->import('tagcloud.js');
		
		echo '<div class="xf-tagcloud">';
		echo '<h2>'.htmlspecialchars($field['widget']['label']).'</h2>';
		echo '<ul>';
		while ($row = xf_db_fetch_row($res) ){
			$link = df_absolute_url(DATAFACE_SITE_HREF.'?-action=list&-table='.$field['tablename'].'&'
				.urlencode($field['relationship'].'/'.$labelCol).'='.urlencode('='.$row[1]));
			
		
			echo '<li data-xf-frequency="'.$row[0].'"><a href="'.htmlspecialchars($link).'">'.htmlspecialchars($row[1]).'</a></li>';
		}
		echo '</ul>
		
		</div>';
		
		//echo '['.$sql.']';
	
	}
	
	function drawScalarTagCloud($field){}
	
	
	/**
	 * @brief Draws a tag cloud for the specified field.
	 *
	 * @param array $field A field data structure.
	 * @return void
	 *
	 * @see drawScalarTagCloud()'
	 * @see drawTransientTagCloud()
	 */
	function drawTagCloud($field){
		if ( @$field['transient'] and @$field['relationship'] ){
			$this->drawTransientTagCloud($field);
		} else if (!@$field['transient'] ){
			$this->drawScalarTagCloud($field);
		}
		
	}
	
	/**
	 * @brief A block that draws the tag clouds for the current table.
	 */
	function block__after_left_column(){
		
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		$table = Dataface_Table::loadTable($query['-table']);
		$fields = $this->getTagCloudFields($table->fields(false,true,true));
		
		
		foreach ($fields as $k=>$v){
			$this->drawTagCloud($v);
		}
		
	}
}
