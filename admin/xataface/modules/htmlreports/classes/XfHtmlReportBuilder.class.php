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
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'simple_html_dom.php';

/**
 * @brief The report builder class that handles the merging of a template with 
 * a dataset to produce a report.
 */
class XfHtmlReportBuilder {
	
	const COMPILE_ERROR = 501;
	public static $SUMMARY_FUNCTIONS = array('sum','min','max','count');
	public static $ALLOWED_EXPRESSION_FUNCTIONS = array(
		'floor',
		'ceil',
		'round',
		'max',
		'min',
		'abs',
		'acos',
		'acosh',
		'asinh',
		'asin',
		'atan',
		'atan2',
		'atanh',
		'base_convert',
		'bindec',
		'cos',
		'cosh',
		'decbin',
		'dechex',
		'decoct',
		'deg2rad',
		'exp',
		'expm11',
		'fmod',
		'getrandmax',
		'hexdec',
		'hypot',
		'lcd_value',
		'log',
		'log10',
		'log1p',
		'mt_getrandmax',
		'mt_rand',
		'mt_srand',
		'octdec',
		'pi',
		'pow',
		'deg2rad',
		'rand',
		'sin',
		'sinh',
		'sqrt',
		'srand',
		'tan',
		'tanh',
		'M_E',
		'E_EULER',
		'M_LNPI',
		'M_LN2',
		'M_LN10',
		'M_LOG2E',
		'M_LOG10E',
		'M_PI',
		'M_PI2',
		'M_PI4',
		'M_1_PI',
		'M_2_PI',
		'M_SQRT_PI',
		'M_2SQRT_PI',
		'M_SQRT1_2',
		'M_SQRT2',
		'M_SQRT3'
	);
	
	
	
	private $templateContent = null;
	private $records = null;
	private $output = '';
	
	private $context = null;
	
	/**
	 * @brief A flag to indicate whether compile errors should be thrown
	 *  if fields or relationships of the template cannot be found.
	 */
	protected $strict = false;
	
	
	/**
	 * @brief Parses out the fields from macros in a given DOM element.
	 * @param simple_html_dom The dom element whose inner text is being checked for macros.
	 * @return array(string) List of field names that were found in the element as macros.
	 */
	public static function getFields($el){
		$out = array();
		if ( preg_match_all('/\{\$([^\}]+)\}/', $el->outertext, $matches, PREG_SET_ORDER) ){
			foreach ($matches as $match){
				$out[] = trim($match[1]);
			}
		}
		$out = array_unique($out);
		return $out;
	}
	
	/**
	 * @brief Parses out summary fields from macros in a given DOM element.
	 * @param simple_html_dom The dom element whose inner text is being checked for macros.
	 * @return array(string) List of summary fields in the form func(field_name).
	 */
	public static function getSummaryFields($el){
		$out = array();
		if ( preg_match_all('/\{@([a-zA-Z]+\([^\}]+\))\}/', $el->outertext, $matches, PREG_SET_ORDER) ){
			foreach ($matches as $match){
				$out[] = trim($match[1]);
			}
		}
		$out = array_unique($out);
		return $out;
	
	}
	
	public static function display($record, $key, $displayMethod='htmlValue'){
		if ( isset($record->pouch['__reports_display'][$key]) ){
			if ( $displayMethod == 'strval' ){
				$out = $record->pouch['__reports_strval'][$key];
			} else {
				$out = $record->pouch['__reports_display'][$key];
			}
		} else {
			//echo "{About to display ".(2/3)." with $displayMethod for column of ".$record->table()->getType($key)." ".$record->$displayMethod($key)." / ".setlocale(LC_NUMERIC,"0")."}";
			$out = $record->$displayMethod($key);
			
			
			
		}
		if ( $displayMethod == 'strval'){
			
			$locale_data = localeconv();
			$decimal = $locale_data['decimal_point'];
			$out = str_replace($decimal, '.', (string)$out);
			//echo "[Changed decimal place: $out]";
		}
		return $out;
	}
	
	public static function set($record, $key, $value, $strval=null){
		if ( !isset($strval) ) $strval = $value;
		if ( strpos($key, '.') === false ){
			$record->setValue($key, $value);
		} else {
			$record->pouch['__reports_display'][$key] = $value;
			$record->pouch['__reports_strval'][$key] = $strval;
		}
	}
	
	
	/**
	 * @brief Prints the grouped data.
	 * @param array &$groupData The data structure group data that stores the report structure.
	 * @param array(array(string)) $groupFields The array of group fields corresponding to each level
	 *		of grouping.
	 * @param array(array(string)) $summaryFields The array of summary fields corresponding to 
	 * 		each level of grouping.
	 * @param array(simple_html_dom) $groupHeaders Array of DOM elements representing the group headers.
	 * @param array(simple_html_dom) $groupFooters Array of DOM elements representing the group footers.
	 */
	public static function printGroupData(&$groupData, $groupFields, $summaryFields, $groupHeaders, $groupFooters, $template, $asTable){
		$header = $groupHeaders[$groupData['level']];
		$footer = $groupFooters[$groupData['level']];
		$gfields = $groupFields[$groupData['level']];
		$sfields = $summaryFields[$groupData['level']];
		if ( !@$groupData['summaryRecord'] ){
			throw new Exception("No summary record provided.", 2000);
		}
		$headerText = '';
		if ( $header ){
			
			$headerText = self::fillReportSingle($groupData['summaryRecord'], $header->innertext);
		}
		$bodyText = '';
		if ( isset($groupData['sections']) ){
			foreach ($groupData['sections'] as $key=>$section){
				$bodyText .= self::printGroupData($groupData['sections'][$key], $groupFields, $summaryFields, $groupHeaders, $groupFooters, $template, $asTable);
				
			}
		} else if ( isset($groupData['records']) ){
			$bodyText = self::fillReportMultiple($groupData['records'], $template, false /* no headers */, $asTable);
		}
		$footerText = '';
		if ( $footer ){
			$footerText = self::fillReportSingle($groupData['summaryRecord'], $footer->innertext);
		}
		return $headerText.$bodyText.$footerText;
	}
	
	/**
	 * @brief Generates a group key for a record based on a list of fields to group by.  Records
	 *	  whose values are the same for the fields listed will have equivalent group keys.
	 *
	 * @param Dataface_Record $record The record that is subject of this calculation.
	 * @param array $fields List of field names to group by.
	 * @return string The key. 
	 */
	public static function getGroupKey(Dataface_Record $record, $fields){
		$out = array();
		foreach ($fields as $f){
			$out[] = urlencode($f).'='.urlencode($record->val($f));
		}
		return implode('&', $out);
	}
	
	
	/**
	 * @brief Fills a report in table mode.  Table mode simply produces a 2-dimensional table where 
	 *		the columns are the fields that appear in the template, in the order that they appear.
	 *		The template itself is only used to define which fields should appear in the table
	 *		in this case.
	 * @param array $records List of Dataface_Record objects to include in the table.
	 * @param string $template The template which includes the fields to be used in this report.
	 * @param boolean $headersAndFooters Whether to group report and include summary totals.
	 * @return string The completed HTML report.
	 */
	public static function fillReportTable($records, $template, $headersAndFooters=true){

		return self::fillReportMultiple($records, $template, $headersAndFooters, true);
	}
	
	
	/**
	 * @brief Fills a report for multiple records at once.
	 * @param array $records List of Dataface_Record objects upon which the report is to be filled.
	 * @param string $template The template to fill.
	 * @param boolean $headersAndFooters Whether to process headers and footers in the template.
	 * @param boolean $asTable Whether to produce a table report.
	 * @return string The processed report.
	 */
	public static function fillReportMultiple($records, $template, $headersAndFooters=true, $asTable=false){
		if ( !is_array($records) ){
			throw new Exception("Records must be an array.", 2000);
		}
		if ( is_string($template) ){
			$el = str_get_html($template);
		} else {
			$el = $template;
		}
		$groupFields = array();
		$summaryFields = array();
		
		$sectionHeaderEls = $el->find('div.xf-htmlreports-section-header');
		$sectionFooterEls = $el->find('div.xf-htmlreports-section-footer');
		$numLevels = max(count($sectionHeaderEls), count($sectionFooterEls));

		if ( $numLevels > 0 ){
			if ( !$headersAndFooters ){
				// Caller doesn't want headers and footers.  We clear them out
				// then process the remainder of the template.
				foreach ($sectionHeaderEls as $myel){
					$myel->outertext = '';
				}
				foreach ($sectionFooterEls as $myel){
					$myel->outertext = '';
				}
				return self::fillReportMultiple($records, $el->outertext, false, $asTable);
			}
			$groupFooters = array();
			$groupHeaders = array();
			
			
			for ($i=0; $i<$numLevels; $i++){
				$groupFields[$i] =  $summaryFields[$i] = array();
				$groupHeaders[$i] = $groupFooters[$i] = null;
			}
			
			for ($i=0; $i<$numLevels; $i++){
			
				$j = $numLevels-$i-1;
				
				if ( isset($sectionHeaderEls[$i]) ){
					$groupFields[$i] = array_merge($groupFields[$i], self::getFields($sectionHeaderEls[$i]));
					$summaryFields[$i] = array_merge($summaryFields[$i], self::getSummaryFields($sectionHeaderEls[$i]));
					$groupHeaders[$i] = $sectionHeaderEls[$i];
				} 
				
				
				if ( isset($sectionFooterEls[$j]) ){
					$groupFields[$i] = array_merge($groupFields[$i], self::getFields($sectionFooterEls[$j]));
					$summaryFields[$i] = array_merge($summaryFields[$i], self::getSummaryFields($sectionFooterEls[$j]));
					$groupFooters[$i] = $sectionFooterEls[$j];
				}
				
			}
			
			for ( $i=0; $i<$numLevels; $i++){
				$groupFields[$i] = array_unique($groupFields[$i]);
				$summaryFields[$i] = array_unique($summaryFields[$i]);
				
			}
			
			
			// Now that we have our groupings and summaries we can proceed to
			// group the records and create summary rows.
			
	
			foreach ($records as $record){
				$node =& $tree;
				for ( $i=0; $i<$numLevels; $i++){	
					$groupKey = self::getGroupKey($record, $groupFields[$i]);
					if ( !isset($node[$groupKey]) ){
						$node[$groupKey] = array(
							'summaryRecord'=> null,
							'level'=>$i
						);
						if ( $i<$numLevels-1 ){
							$node[$groupKey]['sections'] = array();
						} else {
							$node[$groupKey]['records'] = array();
						}
					}
					
					$temp =& $node[$groupKey];
					unset($node);
					if ( isset($temp['sections']) ){
						$node =& $temp['sections'];
					} else {
						$node =& $temp['records'];
					}
					unset($temp);
					
				}
				$node[] = $record;
				unset($node);
			}
			$out = '';
			foreach (array_keys($tree) as $groupKey){
				$groupData =& $tree[$groupKey];
				self::compileGroupData($groupData, $groupFields, $summaryFields);
				$out .= self::printGroupData($groupData, $groupFields, $summaryFields, $groupHeaders, $groupFooters, $template, $asTable);
				
				
			}
		
		} else {
		
			$out = '';
			
				
			
			if ( !is_array($records) ){
				throw new Exception("No array of records provided.", 2000);
			}
			
			
			if ( $asTable ){
				$tableTags = $el->find('table');
				
				if ( count($tableTags) == 1 ){
					$thead = $tableTags[0]->find('thead');
					$tbody = $tableTags[0]->find('tbody');
					if ( count($thead) == 1 and count($tbody) == 1){
						$bodyout = array();
						$rowTemplates = $tableTags[0]->find('tbody tr');
						foreach ($records as $rec){
							foreach ($rowTemplates as $rowTemplate){
								$bodyout[] = self::fillReportSingle($rec, $rowTemplate->outertext);
							}
						}
						
						$tbody[0]->innertext = implode('', $bodyout);
						return $el->outertext;
					}
					
					
				}
				$fields = self::getFields($el);
				$rowtemplate = '<tr>';
				foreach ($fields as $field){
					$fielddef = $records[0]->table()->getField($field);
					if ( PEAR::isError($fielddef) ) continue;
					$rowtemplate .= '<td class="xf-htmlreports-field xf-htmlreports-field-'.str_replace('.','_', $field).'">{$'.$field.'}</td>';
				} 
				$rowtemplate .= '</tr>';
				$template = $rowtemplate;
				
				$cols = array();
				foreach ($fields as $fieldname){
					$field = $records[0]->table()->getField($fieldname);
					if ( PEAR::isError($field) ) continue;
					$label = $field['widget']['label'];
					if ( @$field['column'] and @$field['column']['label'] ) $label = $field['column']['label'];
					$cols[] = $label;
					
				}
				
				$colTemplate = '<tr><th>'.implode('</th><th>', array_map('htmlspecialchars', $cols)).'</th></tr>';
				$out = '<table><thead>'.$colTemplate.'</thead><tbody>';
			}
			
			foreach ($records as $rec){
				$out .= self::fillReportSingle($rec, $template);
			}
			if ( $asTable ){
				$out .= '</tbody></table>';
			}
		}
		return $out;
	
	}
	
	
	public static function isDelegateField(Dataface_Table $table, $fieldname){
		if ( strpos($fieldname, '.') !== false ){
			list($rel, $fld) = explode('.', $fieldname);
			$relObj = $table->getRelationship($rel);
			if ( PEAR::isError($relObj) ) throw new Exception($relObj->getMessage(), $relObj->getCode());
			$rtable = $relObj->getTable($fld);
			if ( PEAR::isError($rtable) ) throw new Exception($rtable->getMessage(), $rtable->getCode());
			return self::isDelegateField($rtable, $fld);
		} else {
			$flds =& $table->delegateFields(true);
			return isset($flds[$fieldname]);
		}
	}
	
	/**
	 * @brief Exracts all of the records of a particular node in a group data tree.  
	 *	  This includes all records in all child nodes as well.
	 * @param array &$groupData The group data node structure.
	 * @return array Array of Dataface_Record objects.
	 */
	public static function extractRecordsFromGroupData(&$groupData){
	
		$out = array();
		if ( isset($groupData['records']) ){
			return $groupData['records'];
		} else if ( isset($groupData['sections']) ){
			
			foreach ($groupData['sections'] as $key=>$section){
				$secRecs = self::extractRecordsFromGroupData($groupData['sections'][$key]);
				foreach ($secRecs as $rec){
					$out[] = $rec;
				}
			}
		}
		return $out;
	
	}
	
	
	/**
	 * @brief Compiles a group data tree so that its aggregate summary totals are filled.
	 * @param array &$groupData The data structure for the group.  This is a tree 
	 * 	where each node contains the following properties: @code
	 *	array(
	 *		'summaryRecord' => <Dataface_Record>      // Dataface_Record to hold the data for the headers
	 *												  // and footers.
	 *		'sections'		=> <array>				  // (Optional) Array of child nodes.
	 *		'records'		=> <array>				  // (Optional - only in leaf nodes)
	 *												  // array or records in this node.
	 *      'level'			=> <int>				  // The level 0, 1, 2, etc.. of this node
	 * @endcode
	 * @param array $groupFields Array of arrays of group fields.
	 * @param array $summaryFields Array of arrays of summary fields.
	 */
	public static function compileGroupData(&$groupData, $groupFields, $summaryFields){
		$gfields = $groupFields[$groupData['level']];
		$sfields = $summaryFields[$groupData['level']];
		
		$records = self::extractRecordsFromGroupData($groupData);
		if ( !$records ) return;
		$summaryRecord = new Dataface_Record($records[0]->table()->tablename, array());
		foreach ($gfields as $gf ){
			if ( strpos($gf, '.') === false ){
				$summaryRecord->setValue($gf, $records[0]->val($gf));
			} else {
				self::set($summaryRecord, $gf, $records[0]->htmlValue($gf), $records[0]->strval($gf) );
			}
		}
		
		
		foreach ($sfields as $opt){
			$summaryRecord->pouch['summaries'][$opt] = self::evaluateAggregateExpression($records, $opt);
			
		}
		$groupData['summaryRecord'] = $summaryRecord;
		if ( isset($groupData['sections']) ){
			foreach (array_keys($groupData['sections']) as $secid){
				$secGroupData =& $groupData['sections'][$secid];
				self::compileGroupData($secGroupData, $groupFields, $summaryFields);
				unset($secGroupData);
			}
		}
		
		
		
	}
	
	/**
	 * @brief Evaluates an aggregate expression given a list of records.
	 *
	 * @param array $records Array of Dataface_Record objects upon which the aggregate 
	 *		expression is to be calculated.
	 * @param string $expression The expression to be evaluated.  E.g. sum(subtotal)
	 * @return mixed Depending on the field type of the parameter may return a string
	 *		of a date, an integer, or a double.
	 */
	public static function evaluateAggregateExpression(array $records, $expression){
	
		$parsed = self::parseExpression($expression);
		switch ($parsed['opt']){
		
			case 'sum':
				$total = 0;
				if ( !$records ) return $total;
				if ( $records[0]->table()->isInt($parsed['field']) ){
					foreach ($records as $rec){
						$total += intval($rec->val($parsed['field']));
					}
				} else {
					foreach ($records as $rec){
						$total += doubleval($rec->val($parsed['field']));
					}
				}
				return $total;
				
			case 'count':
				return count($records);
				
			case 'max':
				$max = 0;
				if ( !$records ) return $max;
				if ( $records[0]->table()->isInt($parsed['field']) ){
					foreach ($records as $rec){
						$max = max($max, intval($rec->val($parsed['field'])));
					}
					return $max;
				} else if ( $records[0]->table()->isFloat($parsed['field']) ){
					foreach ($records as $rec){
						$max = max($max, doubleval($rec->val($parsed['field'])));
					}
					return $max;
					
				} else if ( $records[0]->table()->isDate($parsed['field'])){
					foreach ($records as $rec){
						$max = max($max, strtotime($record->strval($parsed['field'])));
					}
					return date('Y-m-d H:i:s', $max);
				} else {
					$max = null;
					foreach ($records as $rec){
						if ( !isset($max) ){
							$max = $rec->val($parsed['field']);
						} else if ( ($currtest = $rec->val($parsed['field'])) > $max ){
							$max = $currtest;
						}
					}
					return $max;
				}
				
				
			case 'min':
				$min = null;
				if ( !$records ) return $min;
				if ( $records[0]->table()->isInt($parsed['field']) ){
					foreach ($records as $rec){
						if ( !isset($min)  ){
							$min = intval($rec->val($parsed['field']));
						} else {
							$min = min($min, intval($rec->val($parsed['field'])));
						}
					}
					return $min;
				} else if ( $records[0]->table()->isFloat($parsed['field']) ){
					foreach ($records as $rec){
						if ( !isset($min) ){
							$min = doubleval($rec->val($parsed['field']));
						} else {
							$min = min($min, doubleval($rec->val($parsed['field'])));
						}
					}
					return $min;
					
				} else if ( $records[0]->table()->isDate($parsed['field'])){
					foreach ($records as $rec){
						if ( !isset($min) ){
							$min = strtotime($record->strval($parsed['field']));
						} else {
					
							$min = min($min, strtotime($record->strval($parsed['field'])));
						}
					}
					return date('Y-m-d H:i:s', $min);
				} else {
					$min = null;
					foreach ($records as $rec){
						if ( !isset($min) ){
							$min = $rec->val($parsed['field']);
						} else if ( ($currtest = $rec->val($parsed['field'])) > $min ){
							$min = $currtest;
						}
					}
					return $min;
				}
			default:
				throw new Exception("Unrecognized aggregate operator: ".$parsed['opt'], self::COMPILE_ERROR);
			
			
		}
	}
	
	
	/**
	 * @brief Parses a summary field expression.  E.g. sum(subtotal) or count(user_id).
	 * 
	 * @see $SUMMARY_FUNCTIONS For array of function names that may be used.
	 * @param string $expression The aggregate function expression.
	 * @return array Data structure with the component parts of the expression: @code array(
	 *		'opt' => <string> 		// The operation.  e.g. sum, max, count, etc...
	 * 		'field'=> <string>		// The name of the field used as a parameter to the function.
	 * )
	 * @endcode
	 */
	public static function parseExpression($expression){
		$expression = trim($expression);
		if ( preg_match('/^([a-zA-Z]+)\(([^\)]+)\)$/', $expression, $matches)){
			return array(
				'opt'=>trim(strtolower($matches[1])),
				'field'=>trim($matches[2])
			);
		} else {
			throw new Exception("Failed to parse expression '".$expression."'.  It does not match the required pattern.");
			
		}
	}
	
	/**
	 * @brief Fills a repord for a single record.
	 * @param Dataface_Record $record The record containing the data for the report.
	 * @param string $template The template for the report.
	 * @param boolean $strict Whether to throw compile exceptions in cases where fields or
	 *		relationships cannot be found.
	 * @return string The filled report.
	 */
	public static function fillReportSingle(Dataface_Record $record, $template, $strict = false){
		if ( !$record ) throw new Exception("Null record provided.", 2000);
		if ( is_string($template) ){
			$el = str_get_html($template);
		} else {
			$el = $template;
		}
		$builder = new XfHtmlReportBuilder();
		if ( $strict ){
			$builder->strict = true;
		}
		return $builder->fillReport($record, $record, $el, '');
	}
	
	
	/**
	 * @brief Validates a given template against a specified table to ensure that there 
	 * are no errors.  If there is a problem, an exception with code COMPILE_ERROR
	 * will be thrown.  Otherwise it will just pass without incident.
	 *
	 * @param Dataface_Table $table The table against which the template is to be compiled.
	 * @param string $template The template that is being analysed.
	 * @throws Exception(code=XfHtmlReportBuilder::COMPILE_ERROR) if there is a problem.
	 */
	public static function validateTemplate(Dataface_Table $table, $template){
		$rec = new Dataface_Record($table->tablename, array());
		self::fillReportSingle($rec, $template, true);
		return true;
	}
	
	
	/**
	 * @brief Fills the report given a specific root record.
	 * @param Dataface_Record $root The root record that this report is being filled from.
	 * @param mixed $record Either a Dataface_Record or Dataface_RelatedRecord object that is
	 *		the subject of the current row.
	 * @param simple_html_dom DOM element of the template.
	 * @return string The filled HTML content of the report.
	 */
	public function fillReport($root, $record, $el, $basePath='', $contextParams=null){
		$oldContext = $this->context;
		$this->context = new stdClass;
		$this->context->root = $root;
		$this->context->record = $record;
		$this->context->el = $el;
		$this->context->basePath = $basePath;
		$this->context->rrec = null;
		if ( is_array($contextParams)){
			foreach ($contextParams as $k=>$v){
				$this->context->{$k} = $v;
			}
		}
		if ( !isset($this->context->formatValues) ) $this->context->formatValues = true;
		if ( !isset($this->context->displayMethod) ) $this->context->displayMethod = 'htmlValue';
		
		//$dom = str_get_html($template);
		$rrec = null;
		if ( is_a($record, 'Dataface_RelatedRecord') ){
			$rrec = $record;
			$record = $rrec->toRecord();
			$this->context->rrec = $rrec;
			$this->context->record = $record;
		}
		$uls = $el->find('ul[relationship]');
		foreach ($uls as $ul){
			$rel = $ul->relationship;
			
			if ( $this->strict ){
			
				$relationship = $root->table()->getRelationship($rel);
				if ( PEAR::isError($relationship) ){
					throw new Exception(sprintf(
							'Relationship "%s" does not exist in table "%s" in the ul tag: %s',
							$rel,
							$root->table()->tablename,
							str_replace($ul->innertext,'',$ul->outertext)
						),
						self::COMPILE_ERROR
					);
				}
			}
			
			$relatedRecords = $root->getRelatedRecordObjects($rel);
			$lis = $ul->find('li');
			
			$newlis = array();
			foreach ($relatedRecords as $rrec){
				foreach ($lis as $li){
					$newlis[] = $this->fillReport($root, $rrec, $li, $rel.'.' );
				}
			}
			
			$ul->innertext = implode("\n", $newlis);
			
		}
		
		$ols = $el->find('ol[relationship]');
		foreach ($ols as $ol){
			$rel = $ol->relationship;
			
			
			if ( $this->strict ){
			
				$relationship = $root->table()->getRelationship($rel);
				if ( PEAR::isError($relationship) ){
					throw new Exception(sprintf(
							'Relationship "%s" does not exist in table "%s" in the ol tag: %s',
							$rel,
							$root->table()->tablename,
							str_replace($ol->innertext,'',$ol->outertext)
						),
						self::COMPILE_ERROR
					);
				}
			}
			
			$relatedRecords = $root->getRelatedRecordObjects($rel);
			$lis = $ol->find('li');
			
			$newlis = array();
			foreach ($relatedRecords as $rrec){
				foreach ($lis as $li){
					$newlis[] = $this->fillReport($root, $rrec, $li, $rel.'.' );
				}
			}
			
			$ol->innertext = implode("\n", $newlis);
			
		}
		
		$tables = $el->find('table[relationship]');
		foreach ($tables as $table){
			$rel = $table->relationship;
			
			if ( $this->strict ){
			
				$relationship = $root->table()->getRelationship($rel);
				if ( PEAR::isError($relationship) ){
					throw new Exception(sprintf(
							'Relationship "%s" does not exist in table "%s" in the table tag: %s',
							$rel,
							$root->table()->tablename,
							str_replace($table->innertext,'',$table->outertext)
						),
						self::COMPILE_ERROR
					);
				}
			}
			
			$tbody = $table->find('tbody');
			$relatedRecords = $root->getRelatedRecordObjects($rel);
			$trs = $tbody[0]->find('tr');
			
			$newtrs = array();
			foreach ($relatedRecords as $rrec){
				foreach ($trs as $tr){
					$newtrs[] = $this->fillReport($root, $rrec, $tr, $rel.'.' );
				}
			}
			
			$tbody[0]->innertext = implode("\n", $newtrs);
			
		}
		
		
		$content = $el->outertext;
		
		$prevContent = $content;
		while ( preg_match('#\{%[\s\S]+?%\}#', $content) ){
			$content = preg_replace_callback('#\{%([\s\S]+?)%\}#', array($this, '_replace_expressions'), $content);
			if ( $content == $prevContent ) break;  // no change made this round... prevent infinite loop
			$prevContent = $content;
		}
		
		$content = preg_replace_callback('#\{\$([a-zA-Z0-9_\.]+)\}#', array($this, '_replace_fields'), $content);
		
		$content = preg_replace_callback('#\{@([a-zA-Z]+\([a-zA-Z0-9_\.]+\))\}#', array($this, '_replace_summary_fields'), $content);
		
		
		
		
		$this->context = $oldContext;
		return $content;
		
		
	
	}
	
	function pub_if($str, $p1, $p2){
		if ( trim($str) ) return $p1;
		else return $p2;
	}
	
	function pub_strtolower($str){
		return strtolower($str);
	}
	
	
	function pub_year($date){
		return date('Y', strtotime($date));
	}
	
	function pub_month($date){
		return date('m', strtotime($date));
	}
	
	function pub_day($date){
		return date('d', strtotime($date));
	}
	
	function pub_substr($str, $start, $len=null){
		
		$start = intval($start);
		if ( $len ){
			return substr($str, $start, intval($len));
		} else {
			return substr($str, $start);
		}
		
	}
	
	function pub_strftime($date, $format){
		$time = strtotime($date);
		return strftime($format, $time);
	}
	
	function _replace_expressions($matches){
		//$oldLocale = setlocale(LC_NUMERIC, "0");
		//setlocale(LC_NUMERIC, "en_US");
		$matches[1] = str_replace(' ', '', $matches[1]);
		$segments = explode('|', $matches[1]);
	
		$matches[1] = array_shift($segments);
		$el = str_get_html('<html><body>'.$matches[1].'</body></html>');
		
		$out = $this->fillReport($this->context->root, $this->context->record, $el, $this->context->basePath, array(
			'displayMethod' => 'strval',
			'formatValues' => false
		));

		if ( preg_match('/^<html><body>([\s\S]*)<\/body><\/html>$/', (string)$out, $omatches)){
			$out = preg_replace('/&[a-zA-Z]{2,6};/', '', $omatches[1]);
		} else {
			throw new Exception("Error parsing after filling report:". htmlspecialchars($out));
		}
		
		$out = strip_tags($out);
		
		//echo '['.htmlspecialchars($out).']';
		// make sure that we only have arithmetic
		
		if ($segments){
			while ($segments){
				$func = array_shift($segments);
				$params = array_map('trim',explode(':', $func));
				$func = array_shift($params);
				array_unshift($params, $out);
				
				//print_r($params);
				if ( !preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $func) ){
					throw new Exception("Invalid function name $func");
				}
				$methodName = 'pub_'.$func;
				if ( !method_exists($this, $methodName) ){
					throw new Exception("Attempt to use template function ".$func." that doesn't exist.");
				}
				$method = array($this, $methodName);
				$out = call_user_func_array($method, $params);
			
				
			}
			return $out;
		} else {
			$pattern = array();
			foreach (self::$ALLOWED_EXPRESSION_FUNCTIONS as $f){
				$pattern[] = '/\b'.preg_quote($f, '/').'\b/i';
			}
			$stripped = preg_replace($pattern, '', $out);
			
			
			if ( preg_match('#[^0-9\.\,\+\-\*/\(\) ]#', $stripped ) ){
				throw new Exception("Illegal Expression: ".htmlspecialchars($out)." produced by ".htmlspecialchars($matches[1]));
			}
			eval('$out='.$out.';');
			//setlocale(LC_NUMERIC, $oldLocale);
			return $out;
		}
	}	
	
	
	/**
	 * @brief Internal function used by preg_replace_callback to replace the field macros.
	 *
	 * @private
	 */
	function _replace_fields($matches){
		//print_r($matches);
		if ( $this->strict ){
			
			if ( !$this->context->root->table()->hasField($matches[1]) ){
				throw new Exception(sprintf(
						'Field "%s" does not exist in table "%s" so the macro "%s" cannot be resolved.',
						$matches[1],
						$this->context->root->table()->tablename,
						$matches[0]
					),
					self::COMPILE_ERROR
				);
			}
		}
		//echo "{Context display method is ".$this->context->displayMethod."}";
		$displayMethod = $this->context->displayMethod or 'htmlValue';
		$parts = explode('.', $matches[1]);
		if ( count($parts)>1 and $this->context->rrec ){
			$rel = $parts[0];
			$fld = $parts[1];
			
			if ( isset($this->context->rrec) and $this->context->rrec->_relationshipName == $rel ){
				$out =  $this->context->rrec->$displayMethod($fld);
				
				if ( $displayMethod == 'strval' ){
					$locale_data = localeconv();
					$decimal = $locale_data['decimal_point'];
					$out = str_replace($decimal, '.', $out);
				}
				return $out;
			} else {
				//echo "Related record: ".$this->context->rrec->_relationshipName;
			}
		} else {
			//echo "Getting field ".$parts[0].".";
			if ( !$this->context->root ){
				throw new Exception("NO root record to replace fields from.", 2000);
			}
			//return $this->context->root->htmlValue($matches[1]);
			return self::display($this->context->root, $matches[1], $displayMethod);
		}
		
		return $matches[0];
	}
	
	
	/**
	 * @brief Internal function used by preg_replace_callback to replace summary macros.
	 */
	function _replace_summary_fields($matches){
		//print_r($matches);
		
		//echo "Getting field ".$parts[0].".";
		if ( !$this->context->root ){
			throw new Exception("NO root record to replace fields from.", 2000);
		}
		if ( isset($this->context->root->pouch['summaries'][$matches[1]]) ){
			$val = $this->context->root->pouch['summaries'][$matches[1]];
			$expr = self::parseExpression($matches[1]);
			$fld = $expr['field'];
			
			if ( $this->strict ){
			
				if ( !$this->context->root->table()->hasField($fld) ){
					throw new Exception(sprintf(
							'Field "%s" does not exist in table "%s" so the summary macro "%s" cannot be resolved.',
							$fld,
							$this->context->root->table()->tablename,
							$matches[0]
						),
						self::COMPILE_ERROR
					);
				}
				
				if ( !in_array($expr['opt'] , self::$SUMMARY_FUNCTIONS) ){
					throw new Exception(sprintf(
							'Summary function "%s" is not supported.  Currently only %s are supported.  (Found in macro "%s").',
							$expr['opt'],
							'"'.implode('", "', self::$SUMMARY_FUNCTIONS).'"',
							$matches[0]
						),
						self::COMPILE_ERROR
					);
					
				}
			}
			//if ( self::isDelegateField($this->context->root->table(), $fld) ) return $val;
			
			//$old = $this->context->root->val($fld);
			//self::set($this->context->root,$fld, $val);
			//$out = self::display($this->context->root,$fld);
			//self::set($this->context->root, $fld, $old);
			if ( @$this->context->formatValues ){
				$out = $this->context->root->table()->format($fld, $val);
			} else {
				$locale_data = localeconv();
				$decimal = $locale_data['decimal_point'];
				$out = str_replace($decimal, '.', (string)$val);
				//echo "[Changed decimal place: $out]";
			
				
				
			}
			return $out;
		}
		//return $this->context->root->htmlValue($parts[0]);
	
	
		return $matches[0];
	}
	
}