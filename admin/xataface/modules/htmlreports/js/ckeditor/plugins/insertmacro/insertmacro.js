//require <xataface/modules/htmlreports/schemabrowser.js>
//require <xataface/modules/htmlreports/RelationshipSelector.js>
//require <ckeditor.js>
//require-css <ckeditor/plugins/insertmacro/insertmacro.css>




(function(){
	var SchemaBrowser = xataface.modules.htmlreports.SchemaBrowser;
	var RelationshipSelector = xataface.modules.htmlreports.RelationshipSelector;
	
	var $ = jQuery;
	//alert('here');
	//CKEDITOR.config.toolbar_XBasic = [['button-pre', 'Bold', 'Italic', 'Underline', 'InsertMacro']];
	CKEDITOR.config.toolbar_XBasic = $.merge([], CKEDITOR.config.toolbar_Full);
	CKEDITOR.config.toolbar_XBasic.push(['InsertMacro', 'setrelationship', 'validatetemplate','addsectionheader','addsectionfooter', 'previewreport', 'previewreporttable']);
	//[['button-pre', 'Bold', 'Italic', 'Underline', 'InsertMacro']];
	CKEDITOR.plugins.add('insertmacro', {
		init: function(editor){
			
			
			
			
			var pluginName = 'insertmacro';
			//CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/foo.js');
			
			
			editor.addCss('div.xf-htmlreports-section-header{ border: 1px dotted #8cacbb; background-image: url('+XATAFACE_MODULES_HTMLREPORTS_URL+'/images/section_header.png); background-repeat: no-repeat;padding: 10px 0px;}');
			editor.addCss('div.xf-htmlreports-section-footer{ border: 1px dotted #8cacbb; background-image: url('+XATAFACE_MODULES_HTMLREPORTS_URL+'/images/section_footer.png); background-repeat: no-repeat;padding: 10px 0px;}');
			
			
			
			/**
			 * The button to add a field to the template.
			 */
			editor.addCommand(pluginName, new CKEDITOR.command(editor, {
				
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
			
					XataJax.ready(function(){
					
						
					
						var div = document.createElement('div');
						
						var sb = new SchemaBrowser({
							query: {'-table': tableName}
						});
						sb.bind('fieldClicked', function(event){
							try {
								editor.insertText(event.macro);
							} catch (e){
								alert('Please select the position in the template where you would like this field to be inserted.');
							}
						});
						sb.update();
						
						$(div).append(sb.getElement());
						//$(div).append(sb.prevButton.getElement());
						//$(div).append(btn);
						$('body').append(div);
						$(div).dialog({
							title: 'Insert Field',
							width: 300,
							height: $(window).height(),
							position: ['right','top'],
							zIndex: 9999
							
							
						});
						//$('body').append(sb.getElement());
					});
				}
			
			}));
			//alert('here');
			editor.ui.addButton('InsertMacro', {
				label: 'Insert Field',
				command: pluginName
			});
			
			
			
			/**
			 * The button to set the relationship for a table, ol, or ul tag.
			 */
			editor.addCommand('setrelationship', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
			
					XataJax.ready(function(){
					
						
					
						var div = document.createElement('div');
						
						var sb = new RelationshipSelector({
							table: tableName
						});
						sb.bind('relationshipSelected', function(event){
							var element = editor.getSelection().getStartElement();
							if ( !element ){
								//alert('no element selected..');
								return;
							}
							var nativeEl = element.$;
							
							// See if there is already a relationship attribute
							var existing = $(nativeEl).parents('[relationship]');
							if ( existing.size() >= 1 ){
								//alert(existing.attr('relationship'));
								var rel = sb.getSelectedRelationship();
								if ( rel ){
									existing.attr('relationship', rel);
								} else {
									existing.removeAttr('relationship');
								}
							} else {
								var container = $(nativeEl).parents('ul,ol,table').first();
								if ( container.size() == 0){
									alert('Please click inside a table or list and try again.');
									return;
								}
								var rel = sb.getSelectedRelationship();
								if ( rel ){
									container.attr('relationship', rel);
								} else {
									container.removeAttr('relationship');
								}
								
							}
							
							
							
						});
						sb.update();
						
						$(div).append(sb.getElement());
						//$(div).append(sb.prevButton.getElement());
						//$(div).append(btn);
						$('body').append(div);
						$(div).dialog({
							title: 'Select Relationship for Portal',
							width: 300,
							height: 150,
							zIndex: 9999,
							modal: true
							
							
						});
						
						
						sb.bind('loaded', function(event){
							var element = editor.getSelection().getStartElement();
							if ( !element ){
								alert('no element selected..');
								$(div).dialog('close');
								return;
							}
							var nativeEl = element.$;
							
							// See if there is already a relationship attribute
							var existing = $(nativeEl).parents('[relationship]');
							if ( existing.size() >= 1 ){
								//alert(existing.attr('relationship'));
								sb.setSelectedRelationship(existing.attr('relationship'));
							}
						});
						//alert(existing.size());
						//$('body').append(sb.getElement());
					});
					
				}
			}));
			
			editor.ui.addButton('setrelationship', {
				label: 'Set Relationship',
				command: 'setrelationship'
			});
			
			
			/**
			 * The button to validate the current template.
			 */
			editor.addCommand('validatetemplate', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
					
					var data = editor.getData();
					
					var q = {
						'-table': tableName,
						'--template': data,

						'-action': 'htmlreports_validate_template'
					};
					$.post(DATAFACE_SITE_HREF, q, function(response){
					
						try {
							if ( response.message){
								alert(response.message);
							} else {
								alert('Unspecified server error.  See error log for details.');
							}
						
						} catch (e){
							alert(e);
						}
					});
					
					
				}
			}));
			
			editor.ui.addButton('validatetemplate', {
				label: 'Validate Template',
				command: 'validatetemplate'
			}); 
			
			
			/**
			 * The button to add a section header
			 */
			editor.addCommand('addsectionheader', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
					
					var rootEl = editor.document.$;
					
					// First let's see if there is an existing header
					

					var existing = $('div.xf-htmlreports-section-header', rootEl);
					var htmlToAdd = '<div class="xf-htmlreports-section-header"><h2>Header Text</h2></div>';
					if ( existing.size() > 0 ){
						var last = existing.get(existing.size()-1);
						var el = new CKEDITOR.dom.element(last);
						var range = new CKEDITOR.dom.range(editor.document);
						range.setStartAfter(el);
						range.setEndAfter(el);
						editor.getSelection().selectRanges([range]);
						editor.insertHtml(htmlToAdd);
						
					} else {
					
						editor.document.getBody().append(new CKEDITOR.dom.element($(htmlToAdd).get(0)), true);
						/*					//alert('here');
						var range = new CKEDITOR.dom.range(editor.document);
						range.setStart(editor.document, 0);
						range.setEnd(editor.document, 0);
											//alert('here');
						editor.getSelection().selectRanges([range]);
						
						editor.insertHtml(htmlToAdd);
						*/	
					}
					
					editor.updateElement();
				}
			}));
			
			
			
			editor.ui.addButton('addsectionheader', {
				label: 'Add Section Header',
				command: 'addsectionheader'
			});
			
			
			/**
			 * The button to add a section header
			 */
			editor.addCommand('addsectionfooter', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
					
					var rootEl = editor.document.$;
					
					// First let's see if there is an existing header
					
					
					var existing = $('div.xf-htmlreports-section-footer', rootEl);
					var htmlToAdd = '<div class="xf-htmlreports-section-footer"><h3>Footer Text</h3></div>';
					if ( existing.size() > 0 ){
						var last = existing.get(0);
						//var prev = $(last).prev();
						//if ( prev.size() > 0 ) last = prev.get(0);
						
						var el = new CKEDITOR.dom.element(last);
						var range = new CKEDITOR.dom.range(editor.document);
						el.insertBeforeMe(new CKEDITOR.dom.element($(htmlToAdd).get(0)));
						/*
						range.setStartAfter(el);
						range.setEndAfter(el);
						editor.getSelection().selectRanges([range]);
						editor.insertHtml(htmlToAdd);
						*/
						
					} else {
						//alert($(editor.document.getBody().$).html());
						editor.document.getBody().appendHtml(htmlToAdd);
						
						//editor.document.getDocumentElement().insertAfterMe(new CKEDITOR.dom.element($(htmlToAdd).get(0)));
						/*
						var range = new CKEDITOR.dom.range(editor.document);
						
						range.setStartAfter(editor.document.getDocumentElement());
						range.setEndAfter(editor.document.getDocumentElement());
						editor.getSelection().selectRanges([range]);
						editor.insertHtml(htmlToAdd);*/
					}
					editor.updateElement();
					
					
				}
			}));
			
			editor.ui.addButton('addsectionfooter', {
				label: 'Add Section Footer',
				command: 'addsectionfooter'
			});
			
			
			
			/**
			 * The button to preview report
			 */
			editor.addCommand('previewreport', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
					
					var form = $('<form>')
						.attr('method','post')
						.attr('target','_blank')
						.attr('action', DATAFACE_SITE_HREF)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '-action')
								.attr('value', 'htmlreports_preview_report')
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '-table')
								.attr('value', tableName)
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '--template')
								.attr('value', editor.getData())
								
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '--css')
								.attr('value', editor.element.getAttribute('data-xf-htmlreports-css'))
						)
					);
					$('body').append(form);
					form.submit();
					/*
					var q = {
						'-action': 'htmlreports_preview_report',
						'-table': tableName,
						'--template': editor.getData()
					};
					
					$.post(DATAFACE_SITE_HREF, q, function(response){
						
						var div = document.createElement('div');
						$(div).html(response);
						$('body').append(div);
						$(div).dialog({
							title: 'Report Preview',
							width: $(window).width()-40,
							height: $(window).height()-40,
							zIndex: 9999
						});
						
						
						
								
						
					});
					*/
					
				}
			}));
			
			editor.ui.addButton('previewreport', {
				label: 'Preview Report',
				command: 'previewreport'
			});
			
			
			/**
			 * The preview report as table button
			 */
			editor.addCommand('previewreporttable', new CKEDITOR.command(editor, {
			
			
				exec: function(){
					//alert(editor.element);
				
					var tableName = editor.element.getAttribute('data-xf-htmlreports-tablename');
					
					
					var form = $('<form>')
						.attr('method','post')
						.attr('target','_blank')
						.attr('action', DATAFACE_SITE_HREF)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '-action')
								.attr('value', 'htmlreports_preview_report')
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '-table')
								.attr('value', tableName)
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '--template')
								.attr('value', editor.getData())
								
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '--css')
								.attr('value', editor.element.getAttribute('data-xf-htmlreports-css'))
						)
						.append(
							$('<input/>')
								.attr('type', 'hidden')
								.attr('name', '--view')
								.attr('value', 'table')
						)
						;
					$('body').append(form);
					form.submit();
					/*
					var q = {
						'-action': 'htmlreports_preview_report',
						'-table': tableName,
						'--template': editor.getData()
					};
					
					$.post(DATAFACE_SITE_HREF, q, function(response){
						
						var div = document.createElement('div');
						$(div).html(response);
						$('body').append(div);
						$(div).dialog({
							title: 'Report Preview',
							width: $(window).width()-40,
							height: $(window).height()-40,
							zIndex: 9999
						});
						
						
						
								
						
					});
					*/
					
				}
			}));
			
			editor.ui.addButton('previewreporttable', {
				label: 'Preview Report as Table',
				command: 'previewreporttable'
			});
			
		}
	
	});
	
	
	
})();