<?php
/*
 * Xataface HTML Reports Module
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
 * @brief HTTP handler to get the schema for a particular table.  This schema is 
 * used by the field browser to build a tree of fields and relationships
 * for the specified table.
 *
 * @see @ref insertingfield
 *
 * @section permissions Permissions
 *
 * In order to access this action, the user must be granted access to the 
 * <em>view schema</em> permission.  This is only granted to users with ALL
 * permissions by default, but other users can be granted this permission.  
 * Generally this permission should be granted along with the <em>manage reports</em>
 * permission so that any user that can edit a report can also use the field browser.
 *
 * @see @ref module_permissions
 *
 * @section preview_report_rest_api REST API
 *
 * @subsection post_parameters POST Parameters
 *
 * <table>
 * <tr><th>Parameter Name</th><th>Parameter Description</th><th>Example Input</th></tr>
 * <tr>
 *	<td>@c -table</td>
 *	<td>The name of the table for which to return the schema</td>
 *  <td>transactions</td>
 * </tr>
 * </table>
 *
 * @subsection returntype Return Type
 * 
 * This handler will return a application/json response containing a JSON data structure.
 *
 * @section exampleoutput Example Output
 *
 * @code
 * {
 *    "code":200,
 *    "schema":[
 *       {
 *          "data":"Fields",
 *          "children":[
 *             {
 *                "data":"Tool id",
 *                "attr":{
 *                   "xf-htmlreports-fieldname":"tool_id",
 *                   "xf-htmlreports-macro":"{$tool_id}"
 *                },
 *                "children":[
 *                   {
 *                      "data":"sum(Tool id)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@sum(tool_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"min(Tool id)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@min(tool_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"max(Tool id)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@max(tool_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"count(Tool id)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@count(tool_id)}"
 *                      }
 *                   }
 *                ]
 *             },
 *             {
 *                "data":"Facility",
 *                "attr":{
 *                   "xf-htmlreports-fieldname":"facility_id",
 *                   "xf-htmlreports-macro":"{$facility_id}"
 *                },
 *                "children":[
 *                   {
 *                      "data":"sum(Facility)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@sum(facility_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"min(Facility)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@min(facility_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"max(Facility)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@max(facility_id)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"count(Facility)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@count(facility_id)}"
 *                      }
 *                   }
 *                ]
 *             },
 *             {
 *                "data":"Tool name",
 *                "attr":{
 *                   "xf-htmlreports-fieldname":"tool_name",
 *                   "xf-htmlreports-macro":"{$tool_name}"
 *                },
 *                "children":[
 *                   {
 *                      "data":"sum(Tool name)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@sum(tool_name)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"min(Tool name)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@min(tool_name)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"max(Tool name)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@max(tool_name)}"
 *                      }
 *                   },
 *                   {
 *                      "data":"count(Tool name)",
 *                      "attr":{
 *                         "xf-htmlreports-macro":"{@count(tool_name)}"
 *                      }
 *                   }
 *                ]
 *             }
 *             
 *          ]
 *       },
 *       {
 *          "data":"Grafted Fields",
 *          "children":[
 * 
 *          ]
 *       },
 *       {
 *          "data":"Calculated Fields",
 *          "children":[
 * 
 *          ]
 *       },
 *       {
 *          "data":"Relationships",
 *          "data-key":"relationships",
 *          "children":[
 * 
 *          ]
 *       }
 *    ]
 * }
 * @endcode
 *
 * @see http://www.json.org/
 *
 * @section erroroutput Error Output
 *
 * In the case of an error, this will return a JSON data structure with only two keys:
 *
 * -# <b>code</b> - The error code.
 * -# <b>message</b> - The error message.
 *
 * 
 */
class actions_htmlreports_schemabrowser_getschema {
	
	function handle($params){
		
		session_write_close();
		header('Connection:close');
		require_once(dirname(__FILE__).'/../classes/XfHtmlReportBuilder.class.php');
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		$table = Dataface_Table::loadTable($query['-table']);
		$perms = $table->getPermissions(array());
		
		
		try {
		
			if ( !@$perms['view schema'] ){
				throw new Exception("You don't have permission to view this table's schema.");
			}
			
			
			$opts = array();
			$opts[] = array(
				'data'=>df_translate('xataface.modules.htmlreports.schemabrowser.fields_label','Fields'),
				'children'=>array()
			);
			
			
			// First do the regular fields
			$fields =& $table->fields();
			
			$fieldOpts =& $opts[0]['children'];
			foreach ($fields as $field){
				$fperms = $table->getPermissions(array('field'=>$field['name']));
				if ( !@$fperms['view schema'] ) continue;
				$myopt= array(
					'data'=>$field['widget']['label'],
					'attr'=> array(
						'xf-htmlreports-fieldname'=>$field['name'],
						'xf-htmlreports-macro'=>'{$'.$field['name'].'}'
					)
				);
				
				$children = array();
				foreach ( XfHtmlReportBuilder::$SUMMARY_FUNCTIONS as $func ){
					$children[] = array(
						'data'=> $func.'('.$field['widget']['label'].')',
						'attr'=>array(
							'xf-htmlreports-macro'=>'{@'.$func.'('.$field['name'].')}'
						)
					);
				}
				
				$myopt['children'] = $children;
				$fieldOpts[] = $myopt;
			}
			
			unset($fieldOpts);
			
			
			// Next we do the grafted fields
			$fieldOpts = array();
			$graftedOpt = array(
				'data'=>df_translate('xataface.modules.htmlreports.schemabrowser.grafted_fields_label', 'Grafted Fields')
			);
			$graftedOpt['children'] =& $fieldOpts;
			
			unset($fields);
			$fields =& $table->graftedfields();
			foreach ($fields as $field){
				$fperms = $table->getPermissions(array('field'=>$field['name']));
				if ( !@$fperms['view'] or !@$fperms['view schema'] ) continue;
				$myopt = array(
					'data'=>$field['widget']['label'],
					'attr'=>array(
						'xf-htmlreports-fieldname'=>$field['name'],
						'xf-htmlreports-macro'=> '{$'.$field['name'].'}'
					)
				);
				
				$children = array();
				foreach ( XfHtmlReportBuilder::$SUMMARY_FUNCTIONS as $func ){
					$children[] = array(
						'data'=> $func.'('.$field['widget']['label'].')',
						'attr'=>array(
							'xf-htmlreports-macro'=>'{@'.$func.'('.$field['name'].')}'
						)
					);
				}
				
				$myopt['children'] = $children;
				$fieldOpts[] = $myopt;
			}
			
			$opts[] = $graftedOpt;
			
			
			unset($fieldOpts);
			unset($graftedOpt);
			unset($fields);
			
			
			// Next we do the calculated fields
			$fieldOpts = array();
			$calcOpt = array(
				'data'=>df_translate('xataface.modules.htmlreports.schemabrowser.calculated_fields_label', 'Calculated Fields')
			);
			$calcOpt['children'] =& $fieldOpts;
			$fields =& $table->delegateFields();
			
			foreach ($fields as $field){
				$fperms = $table->getPermissions(array('field'=>$field['name']));
				if ( !@$fperms['view schema'] ) continue;
				$myopt = array(
					'data'=>$field['widget']['label'],
					'attr'=>array(
						'xf-htmlreports-fieldname'=>$field['name'],
						'xf-htmlreports-macro'=> '{$'.$field['name'].'}'
					)
				);
				
				$children = array();
				foreach ( XfHtmlReportBuilder::$SUMMARY_FUNCTIONS as $func ){
					$children[] = array(
						'data'=> $func.'('.$field['widget']['label'].')',
						'attr'=>array(
							'xf-htmlreports-macro'=>'{@'.$func.'('.$field['name'].')}'
						)
					);
				}
				
				$myopt['children'] = $children;
				
				$fieldOpts[] = $myopt;
			}
			
			$opts[] = $calcOpt;
			
			unset($calcOpt);
			unset($fields);
			unset($fieldOpts);
			
			
			// Now we do the relationships
			
			$rOpt = array(
				'data'=>df_translate('xataface.modules.htmlreports.schemabrowser.relationships_label', 'Relationships'),
				'data-key'=>'relationships'
			);
			$rOptOpts = array();
			$rOpt['children'] =& $rOptOpts;
			$opts[] = $rOpt;
			
			$relationships =& $table->relationships();
			foreach ($relationships as $rname=>$r){
				$rtable = Dataface_Table::loadTable($r->getDomainTable());
				$rperms = $table->getPermissions(array('relationship'=>$rname));
				if ( !@$rperms['view schema'] ) continue;
				
				$rfieldOpts = array();
				$rfields = $r->fields(true);
				$thisrOpt = array(
					'data'=>$r->getLabel(),
					'attr'=>array(
						'xf-htmlreports-relationshipname'=>$rname
					)
				);
				$thisrOpt['children'] =& $rfieldOpts;
				
				foreach ($rfields as $rfield){
					$fieldDef = $r->getField($rfield);
					$ftable = Dataface_Table::loadTable($fieldDef['tablename']);
					$fperms = $table->getPermissions(array('field'=>$fieldDef['name']));
					if ( !@$fperms['view schema'] ){
						continue;
					}
					
					$rfieldOpts[] = array(
						'data' => $fieldDef['widget']['label'],
						'attr'=>array(
							'xf-htmlreports-macro' => '{$'.$rname.'.'.$fieldDef['name'].'}',
							'xf-htmlreports-fieldname'=>$fieldDef['name']
						)
					);
					
					
					
					
				}
				
				
				//$calcOpt = array(
				//	'data'=>df_translate('xataface.modules.htmlreports.schemabrowser.calculated_fields_label', 'Calculated Fields')
				//);
				//$calcOpt['children'] =& $fieldOpts;
				$rcfields =& $rtable->delegateFields();
				
				foreach ($rcfields as $field){
					$fperms = $rtable->getPermissions(array('field'=>$field['name']));
					if ( !@$fperms['view schema'] ) continue;
					$myopt = array(
						'data'=>$field['widget']['label'],
						'attr'=>array(
							'xf-htmlreports-fieldname'=>$field['name'],
							'xf-htmlreports-macro'=> '{$'.$rname.'.'.$field['name'].'}'
						)
					);
					
					
					
					$rfieldOpts[] = $myopt;
				}
				unset($rcfields);
				
				unset($rfieldOpts);
				unset($rtable);
				$rOptOpts[] = $thisrOpt;
				
			}
			
			
			
			
			$root = array(
				'data'=>$table->getLabel(),
				'children'=>$opts
			);
			
			$this->out(array(
				'code'=>200,
				'schema'=>$opts
			));
			exit;
			
			
		
		} catch (Exception $ex){
			$this->out(array(
				'code'=>$ex->getCode(),
				'message'=>$ex->getMessage()
			));
			exit;
		}
	
	}
	
	function out($params){
		header('Content-type: application/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($params);
	}
	
	
}