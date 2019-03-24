//require <jquery.packed.js>
//require <jquery-ui.min.js>
//require <tag-it.js>
//require <RecordDialog/RecordDialog.js>
//require-css <jquery-ui/jquery-ui.css>
//require-css <xataface/widgets/tagger/tagger.css>
(function(){
	
	var $ = jQuery;
	
	
	
	
	function decorateField(field){
	
		/*************************************************************************
		 * GETTERS AND SETTERS
		 */
		 
		function isFrozen(){
			return $(field).attr('data-frozen');
		}
		
		function isRemoveAllowed(){
			return !$(field).attr('data-xf-tagger-no-remove');
		}
		
		function isAddExistingAllowed(){
			return !$(field).attr('data-xf-tagger-no-add-existing');
		}
		
		function isAddNewAllowed(){
			return !$(field).attr('data-xf-tagger-no-add-new');
		}
		 
		function getFieldRecordID(){
			return $(field).attr('data-record-id');
		}
		
		function getFieldTable(){
			return $(field).attr('data-xf-tagger-table');
		}
		
		function getFieldField(){
			return $(field).attr('data-xf-tagger-field')
		}
		
		function getTagId(li){
			return $(li).attr('data-xf-record-id');
		}
		
		function getFieldIsNew(){
			return $(field).attr('data-new');
		}
		
		/**
		 * Sets the record id of an li tag.
		 */
		function setTagId(li, id){
			$(li).attr('data-xf-record-id', id);
		}
		
		
		/**
		 * Sets the label of an li tag.
		 */
		function setTagLabel(li, label){
			
			$('input', li).val(label);
			$('span.tagit-label', li).text(label);
			pushData();
		}
		
		function getTagLabel(li){
			return $('input', li).val();
		}
		
		/**
		 * Synchronizes the data from the tag list to the hidden field.
		 */
		function pushData(){
			var out = [];
			$('li.tagit-choice', ul).each(function(){
				var id = getTagId(this);
				var str = '';
				if ( id ){
					str += 'xfid://'+id+' ';
				}
				str += $('input', this).val();
				out.push(str);
			});
			$(field).val(out.join("\n"));
			$(field).trigger('change');
		}
		
		
		
		
		/*****
		 * END GETTERS AND SETTERS
		 *************************************************************************/
		 
		 
		/*************************************************************************
		 * WEB SERVICE WRAPPERS
		 */
		 
		function addOrLoadTag(li, callback, failedCallback){
			if ( typeof(callback) != 'function' ) callback = function(){};
			if ( typeof(failedCallback) != 'function' ) failedCallback = function(){};
			
			loadTagID(li, function(o){
					var recordID = o.recordID;
					var label = o.label;
					
					setTagId(li, recordID);
					//editTag(li);
					
					callback({recordID: getTagId(li), label: getTagLabel(li)});
				
				},
				function(){
					
					addTag(li, function(){
							//editTag(li);
							callback({recordID: getTagId(li), label: getTagLabel(li)});
						},
						failedCallback
					);
				}
			);
			
		
		}
		 
		/**
		 * Edits a tag
		 */
		function editTag(li){
			
			var recordID = getTagId(li);
			
			if (!recordID ){
				// Neither the record ID nor a new flag has been assigned
				// so let's try to load the tag id
				loadTagID(li, function(o){
					var recordID = o.recordID;
					var label = o.label;
					setTagId(li, recordID);
					editTag(li);
					
					},
					function(){
						addTag(li, function(){
							editTag(li);
						});
					}
				);
			} else {
			
				var table = recordID.split('?');
				table = table[0];
				var dlg = new xataface.RecordDialog({
					recordid: recordID,
					table: table,
					callback: function(data){
					
						refreshTagLabel(li);
						
					}
				
				});
				
				dlg.display();
				
			}
		}
	
	
		/**
		 * Adds a tag to the database.
		 *
		 * @param HTMLElement li The li tag in the list that is being added.
		 * @param function callback A callback function to run on success.
		 */
		function addTag(li, callback, failedCallback){
			if ( typeof(callback) != 'function' ) callback = function(){};
			if ( typeof(failedCallback) != 'function' ) failedCallback = function(){};
			var value = getTagLabel(li);
			var q = {
					'-action': 'tagger_add_tag',
					'-table': getFieldTable(),
					'-field': getFieldField(),
					'-src-record-id': getFieldRecordID(),
					'-value': value
				};
			
			$.post(DATAFACE_SITE_HREF, q, function(response){
				try {
					if ( typeof(response) == 'string' ){
						eval('response='+response+';');
					}
					
					if ( response.code == 200 ){
						
						setTagId(li, response.recordID);
						setTagLabel(li, response.label);
						
						callback({
							recordID: response.recordID, 
							label: response.label
						});
					} else if ( response.message ){
						failedCallback(response.message);
					} else {
						throw 'Failed to add tag because of an unspecified server error.  See server log for details.';
					}
				} catch (e){
					//console.log("Error in addTag() response: "+e);
					alert(e);
				}
			});
		
		}
		
		
		/**
		 * Uses the recordID of a tag to refresh the label of a tag.
		 */
		function refreshTagLabel(li, callback){
			if ( typeof(callback) != 'function' ){
				callback = function(){};
			}
			
			var recordID = getTagId(li);
			if ( !recordID ){
				loadTagID(li, function(){
					refreshTagLabel(li, callback);
				});
			}
			
			
			var q = {
				'-action': 'tagger_get_label',
				'-table': getFieldTable(),
				'-field': getFieldField(),
				'-src-record-id': getFieldRecordID(),
				'-record-id': recordID
			};
			
			$.get(DATAFACE_SITE_HREF, q, function(response){
				try {
					if ( typeof(response) == 'string' ){
						eval('response='+response+';');
						
					}
					
					if ( response.code == 200 ){
						setTagLabel(li, response.label);
						callback({recordID: recordID, label: response.label});
					}
					if ( getTagId() ){
						$(li).removeClass('unsaved');
					}
				} catch (e){
					//console.log("Error in refreshTagLabel response: "+e);
					alert(e);
				}
			});
		}
		
		
		/**
		 * Loads a tag details.
		 *
		 * @param HTMLElement li The li tag that we are loading.
		 * @param function callback A callback function
		 *
		 */
		function loadTagID(li, callback, notFoundCallback){
			if ( typeof(callback) != 'function' ){
				callback = function(){};
			}
			if ( typeof(notFoundCallback) != 'function' ){
				notFoundCallback = function(){};
			}
			var recordID = getTagId(li);
			var label = $('span.tagit-label', li).text();
			
			if ( recordID ){
				callback({
					recordID: recordID, 
					label: label}
				);
			} else {
				var q = {
					'-action': 'tagger_get_record_id',
					'-table': getFieldTable(),
					'-field': getFieldField(),
					'-value': getTagLabel(li),
					'-src-record-id': getFieldRecordID()
				};
				
				$.get(DATAFACE_SITE_HREF, q, function(response){
					try {
						if ( typeof(response) == 'string' ){
							eval('response='+response+';');
							
						}
						if ( response.code == 200 ){
							
							setTagId(li, response.recordID);
							
							setTagLabel(li, response.label);
							
							
							callback({
								recordID: response.recordID, 
								label: response.label
							});
						} else if ( response.code == 404 ){
							notFoundCallback();
						
						} else if ( response.message ){
							throw response.message;
						} else {
							throw 'Failed to load tag id due to an unspecified server error';
						}
						
					} catch (e){
						//console.log(1);
						alert(e);
					}
					
				});
				
			}
		}
		
		/***
		 * END WEB SERVICE WRAPPERS
		 ********************************************************************************/
		
		
		
		/**
		 * A utility method to filter the list of autocomplete results.
		 */
		function filterList(term, results){
			
			var out = [];
			for ( var i=0; i<results.length; i++){
				if ( !results[i] ) continue;
				if ( results[i].toLowerCase().indexOf(term.toLowerCase()) != -1 ){
					out[out.length] = results[i];
				}
				//out[i] = results[i];
			}
			return $.map(out, function(item){
				return {label: item, value: item};
			});
			$.each(results, function(){
				if ( this.toLowerCase().indexOf(term.toLowerCase()) != -1 ){
					out[out.length] = this;
				}
			});
			
			return out;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//// END OF Functions
		
		var vocabCache = null;
		var ul = document.createElement('ul');
		$(field).after(ul);
		$(field).hide();
		
		
		var val = $(field).val();
		val = val.split("\n");
		$.each(val, function(item,val){
			var id = null;
			if ( val.indexOf('xfid://') == 0 ){
				var parts = val.split(' ');
				id = parts.shift();
				id = id.replace(/^xfid:\/\//, '');
				val = parts.join(' ');
				
			}
		
			var li = document.createElement('li');
			
			setTagId(li, id);
			
			$(li).text(val);
			if ( !val.trim() ) return;
			$(ul).append(li);
		});
		//alert($(ul).html());
		
		$(ul).tagit({
			//availableTags: ['foo','bar','Canada','Mexico','United States']
			onAdd: function(val, ul, li){
				var tags = $(field).val().split("\n");
				if ( tags.length == 1 && tags[0] == '' ) tags[0] = val;
				else tags.push(val);
				
				$(field).val(tags.join("\n"));
				$(li).dblclick(function(){
					editTag(li);
				});
				addOrLoadTag(li, function(){
					//alert($(field).val()+"\nfoo2");
				
				}, function(msg){
					$(li).addClass('unsaved');
				});
				//alert($(field).val()+"\nfoo");
			},
			
			onRemove: function(val, ul, li){
				var id = getTagId(li);
				var fullVal = val;
				if ( id ) fullVal = 'xfid://'+id + ' ' + val;
				
				var tags = $(field).val().split("\n");
				var idx = tags.indexOf(fullVal);
				if ( idx != -1 ){
					tags.splice(idx,1);
				} else {
					idx = tags.indexOf(val);
					if ( idx != -1 ){
						tags.splice(idx,1);
					}
				
				}
				$(field).val(tags.join("\n"));
				//alert($(field).val()+"\nfoo");
			},
			availableTags: function(request, response){
				if ( vocabCache != null ){
					var found = filterList(request.term, vocabCache);
					
					response(found);
					return;
				}
				var q = {
					'-action': 'tagger_autocomplete',
					'-table': getFieldTable(),
					'-field': getFieldField(),
					'-search': request.term
				};
				if ( getFieldIsNew() ){
					q['-new'] = 1;
				} else {
					q['-record-id'] = getFieldRecordID();
				}
				
				$.get(DATAFACE_SITE_HREF, q, function(res){
					try {
					
						if ( typeof(res) == 'string' ){
							eval('res='+res+';');
						}
						
						if ( res.code == 200 ){
							if ( res.all ){
								vocabCache = res.matches;
								var found = filterList(request.term, vocabCache);
							} else {
								var found = filterList(request.term, res.matches);
							}
							
							response(found);
						} else {
							if ( typeof(res.message) == 'string' ){
								throw res.message;
							} else {
								throw 'Autocomplete failed due to an unspecified server error.  See server log for details.';
							}
						}
					} catch (e){
						alert(e);
					}
				
				});
			}
		
		});
		
		
		// Let's make it active so we can edit the tags' records inline
		$('li.tagit-choice', ul).dblclick(function(){
			//alert('here');
			editTag(this);
			
		});
		
		if ( !isRemoveAllowed() ){
			// If we aren't allowed to remove from this relationship then
			// we need to disable the 'remove' buttons.
			$('li.tagit-choice a.close', ul).remove();
		}
		
		if ( isFrozen() ){
			$('input', ul).attr('readonly',1);
		}
	}
	
	registerXatafaceDecorator(function(node){
	//$(document).ready(function(){
		
		$('textarea.xf-tagger', node).each(function(){
		
			var field = this;
			decorateField(field);
			
			
		});
		
	});
	
})();
