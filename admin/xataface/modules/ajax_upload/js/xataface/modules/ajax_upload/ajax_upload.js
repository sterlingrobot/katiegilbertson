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
 
 
//require <jquery.packed.js>
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>
//require <xataface/modules/ajax_upload/lib/jquery.iframe-transport.js>
//require <xataface/modules/ajax_upload/lib/jquery.fileupload.js>
//require <xataface/modules/ajax_upload/lib/jquery.lightbox-0.5.min.js>
//require-css <xataface/modules/ajax_upload/lib/jquery-lightbox/css/jquery.lightbox-0.5.css>

//require-css <xataface/modules/ajax_upload/ajax_upload.css>  
(function(){
	var $ = jQuery;
	window.XF_LANG = window.XF_LANG || null;
	
	/**
	 * Deletes a file that is currently in a field.  This really just duplicates
	 * the functionality that exists for the standard file widget as the delete_file
	 * action already esxists.
	 *
	 * @param {String} recordId The record id of the record who is having it's file
	 *	removed.
	 * @param {String} field The name of the field from which the file is being deleted.
	 * @param {Function} callback A callback function to be called if the file is successfully
	 *		removed.
	 */
	function deleteFile(table, field, recordId, callback){
		if ( confirm('Are you sure you want to delete this file?  This cannot be undone.') ){
			var q = {
				'-action': 'ajax_upload_delete_temp_file',
				'--field': field,
				'-table': table,
				'--recordId': recordId,
                                '--lang' : window.XF_LANG
			};
                        if ( !window.XF_LANG ){
                            delete q['--lang'];
                        }
			$.post(DATAFACE_SITE_HREF, q, function(res){
		
				try {
					if ( res.code == 200 ){
						callback(res);
					} else {
						if ( res.message ) throw res.message;
						else throw 'Failed to delete file.  Unspecified server error.  See server logs for details.';
					}
				} catch (e){
					try {
						callback(res);
					} catch (e2){
						alert(e2);
					}
				}
			});
		}
	
	}
	
	/**
	 * Deletes a temporary field that has been uploaded but hasn't been saved to the
	 * record yet.
	 *
	 */
	function deleteTempFile(table, field, fileId, callback){
	
		var q = {
		
			'-action': 'ajax_upload_delete_temp_file',
			'--field': field,
			'-table': table,
			'--fileId': fileId,
                        '--lang' : window.XF_LANG
		};
		if ( !window.XF_LANG ){
                    delete q['--lang'];
                }
		
		$.post(DATAFACE_SITE_HREF, q, function(res){
		
			try {
				if ( res.code == 200 ){
					callback(res);
				} else {
					if ( res.message ) throw res.message;
					else throw 'Failed to delete file.  Unspecified server error.  See server logs for details.';
				}
			} catch (e){
				try {
					callback(res);
				} catch (e2){
					alert(e2);
				}
			}
		});
	}
	
	
	/**
	 * When defining the javascript for a widget, we always wrap it in
	 * registerXatafaceDecorator so that it will be run whenever any new content is
	 * loaded ino the page.  This makes it compatible with the grid widget.
	 *
	 * If you don't do this, the widget will only be installed on widgets at page load time
	 * so when new rows are added via the grid widget, the necessary javascript won't be installed
	 * on those widgets.
	 */
	registerXatafaceDecorator(function(node){
		
		$('input.xf-ajax-upload', node).each(function(){
			
			var self = this;
			if ( $(self).val()){
				$(self).attr('data-xf-file-name', $(self).val());
			}
			var fieldName = $(self).attr('data-xf-field');
			var tableName = $(self).attr('data-xf-table');
			var thumbnailWidth = $(self).attr('data-xf-thumbnail-width');
			var thumbnailHeight = $(self).attr('data-xf-thumbnail-height');
			var uploadInProgress = false; // flag to check if there is an upload in progress
			
			
			var formGroup = $(self).closest('.xf-form-group');
			var form = $(self).closest('form');
			
			
			
			
			
			var recordId = formGroup.attr('data-xf-record-id') || '';
			var recordIdTable = recordId.substr(0, recordId.indexOf('?'));
			var recordIsRelated = (recordIdTable.indexOf('/') > 0);
			if ( recordId == 'new' || recordId == undefined || (recordId.indexOf(tableName) !== 0 && !recordIsRelated) ) recordId = '';

			
			var deleteBtn = $('<button class="xf-ajax-file-upload-delete-button">Delete</button>');
			var replaceBtn = $('<button class="xf-ajax-file-upload-replace-button">Replace</button>');
			var cancelUploadBtn = $('<button class="xf-ajax-file-upload-cancel-upload-button">Cancel</button>');
			var cancelReplaceBtn = $('<button class="xf-ajax-file-upload-cancel-replace-button">Cancel</button>');
			var jqXHR = null;
			
			
			
			function fileSizeStr(bytes){
				bytes = parseFloat(bytes);
				var units = 'bytes';
				if ( bytes > 1000 ){
					bytes = bytes/1000.0;
					units = 'kb';
				}
				
				if ( bytes > 1000 ){
					bytes = bytes/1000.0;
					units = 'mb';
				}
				
				if ( bytes > 1000 ){
					bytes = bytes / 1000.0;
					units = 'gb';
				}
				
				return (Math.round(bytes*100)/100)+' '+units;
			
			}
			
			
			
			function update(){
			
				if ( $(self).val() ){
					var val = $(self).val();
					
					var thumbnailUrl = DATAFACE_SITE_HREF+'?-action=ajax_upload_get_thumbnail&--field='+encodeURIComponent(fieldName)+'&-table='+encodeURIComponent(tableName);
					if ( val.indexOf('xftmpimg://') == 0 ){
						thumbnailUrl += '&--tempfileid='+encodeURIComponent(val.substr(11));
					}
					if ( recordId ){
						thumbnailUrl += '&--recordId='+encodeURIComponent(recordId);
					}
					
					var previewUrl = thumbnailUrl;
					
					thumbnailUrl += '&--max_width='+encodeURIComponent(thumbnailWidth);
					thumbnailUrl += '&--max_height='+encodeURIComponent(thumbnailHeight);
					if ( window.XF_LANG) {
                                            thumbnailUrl += '&--lang='+encodeURIComponent(window.XF_LANG);
                                        }
					previewUrl += '&--max_width='+encodeURIComponent(Math.round($(window).width()*0.75));
					previewUrl += '&--max_height='+encodeURIComponent(Math.round($(window).height()*0.75));
                                        if ( window.XF_LANG){
                                            previewUrl += '&--lang='+encodeURIComponent(window.XF_LANG);
                                        }
					if ( !$(self).attr('data-xf-file-size') || 
						 !$(self).attr('data-xf-file-type') || 
						 !$(self).attr('data-xf-file-name') || 
						 ($(self).attr('data-xf-file-name').indexOf('xftmpimg://') == 0) ){
						
						var q = {
						
							'-table': tableName,
							'--field': fieldName,
							//'--fileid': val.substr(11),
							'-action': 'ajax_upload_get_temp_file_details',
                                                        '--lang' : window.XF_LANG
						};
                                                if ( !window.XF_LANG ){
                                                    delete q['--lang'];
                                                }
						
						if ( val.indexOf('xftmpimg://') == 0){
							q['--fileid'] = val.substr(11);
						} else {
							q['--recordId'] = recordId;
						}
						
						//alert("about to make request for file "+val.substr(11)); 
						var loadedDetails = false;
						$(self).attr('data-xf-file-name', 'Loading details...');
						$(self).attr('data-xf-file-size', 0);
						$(self).attr('data-xf-file-type', '...');
						previewDiv.hide();
						uploadDiv.show();
						setTimeout(function(){
							if ( !loadedDetails ){
								$(self).attr('data-xf-file-name', 'File not found');
								$(self).attr('data-xf-file-size', 0);
								$(self).attr('data-xf-file-type', '');
							}
						}, 500);
						
						
						$.get(DATAFACE_SITE_HREF, q, function(res){
							
							try {
								$(self).attr('data-xf-file-name', res.name);
								$(self).attr('data-xf-file-size', res.size);
								$(self).attr('data-xf-file-type', res.type);
								if ( res.url ){
									$(self).attr('data-xf-preview-url', res.url);
								}
								loadedDetails = true;
								update();
							} catch (e){
								$(self).attr('data-xf-file-name', 'File not found');
								$(self).attr('data-xf-file-size', 0);
								$(self).attr('data-xf-file-type', 'none');
								$(self).attr('data-xf-preview-url', '');
								update();
							}
							
						});
				
					
					} else {
					
						var fileName = $(self).attr('data-xf-file-name');
						if ( !fileName ){
							fileName = val;
						}
						
						var fileSize = $(self).attr('data-xf-file-size');
						//if ( !fileSize ){
						//	fileSize = '??';
						//}
						
						$('.xf-ajax-upload-filename', previewDiv).text(fileName);
						
						if ( fileSize ){
							$('.xf-ajax-upload-filesize', previewDiv).show().text(fileSizeStr(fileSize));
						} else {
							$('.xf-ajax-upload-filesize', previewDiv).hide();
						}
						previewLink.unbind('click');
						var fileType = $(self).attr('data-xf-file-type');
						if ( fileType.indexOf('image/') == 0 ){
							previewLink.lightBox();
							previewLink.attr('href', previewUrl);
						} else if ( $(self).attr('data-xf-preview-url') )  {
							previewLink.attr('href', $(self).attr('data-xf-preview-url'));
						} else {
							previewLink.click(function(){ return false;});
						}
						
						// The field is not empty.. we show the preview
						$('.xf-ajax-upload-thumbnail', previewDiv).attr('src', thumbnailUrl);
						
						
				
						previewDiv.show();
						uploadDiv.hide();
					}
					
					
				
				} else {
				
				
					// The field is empty.  We show the upload form.
					
					previewDiv.hide();
					uploadDiv.show();
				}
			}
			
			$(self).hide();
			
			
			
			/**
			 * The delete button that appears on the preview field deletes the file 
			 * that is currently uploaded to this field.
			 *
			 * Clicking this button should delete the file, hide the preview 
			 * panel and show the upload button again.
			 */
			deleteBtn.click(function(){
			
				function deleteCallback(res){
					
					try {
						if ( res.code == 200 || res.code == 404 ){
							$(self).val('');
							update();
						 } else {
							if ( res.message ){
								throw res.message;
							} else {
								throw "Failed to delete file due to server error.  Check server error log for details.";
								
							}
						}
					
					} catch (e){
					
						alert(e);
					}
				}
			
			
				//if ( previewDiv.hasClass('xf-temp-file') ){
				if ( $(self).val().indexOf('xftmpimg://') === 0 ){
					//alert($(self).val().substr(11));
					deleteTempFile(
						$(self).attr('data-xf-table'), 
						$(self).attr('data-xf-field'), 
						$(self).val().substr(11),
						deleteCallback
					);
					
				} else {
					deleteFile(
						$(self).attr('data-xf-table'),
						$(self).attr('data-xf-field'),
						recordId,
						deleteCallback
					);
				}
				
				return false;
			});
			
			replaceBtn.click(function(){
			
				uploadDiv.show();
				cancelReplaceBtn.show();
				return false;
			});
			
			cancelReplaceBtn.click(function(){
			
				uploadDiv.hide();
				cancelReplaceBtn.hide();
				return false;
			});
			
			
			cancelUploadBtn.click(function(){
				try {
					jqXHR.abort();
				} catch (e){}
				return false;
			});
			
			$('div.xf-ajax-upload-wrapper', $(self).parent()).remove();
			
			var wrapperDiv = $('<div/>')
				.addClass('xf-ajax-upload-wrapper')
				.insertAfter(self);
				
			
			var previewImg = $('<img src="#" class="xf-ajax-upload-thumbnail"/>');
			var previewLink = $('<a href="#" target="_blank" class="xf-ajax-upload-thumbnail-preview"/>')
				.append(previewImg);
			
		
			var previewDiv = $('<div/>')
				.addClass('xf-ajax-upload-file-preview')
				//.css('display','none')
				.append(previewLink)
				.append($('<div class="xf-ajax-upload-filename">Filename</div>'))
				.append($('<div class="xf-ajax-upload-filesize">Filesize</div>'))
				.append(
					$('<div class="xf-ajax-upload-buttons"/>')
						.append(deleteBtn)
						.append(replaceBtn)
				)
				;
			wrapperDiv.append(previewDiv);
			
			var progressBar = $('<div/>')
				.addClass('xf-ajax-upload-progress-bar')
				.progressbar();
			
			var progressDiv = $('<div/>')
				.addClass('xf-ajax-upload-progress')
				.append(progressBar)
				.append(cancelUploadBtn)
				
				.hide();
				
			wrapperDiv.append(progressDiv);
			
				
			
			var uploadDiv = $('<div/>')
				.addClass('xf-ajax-upload-filechooser')
			
				.append(
					$('<input type="file" class="xf-file-input"/>')
				)
				.append(cancelReplaceBtn.hide())
				
				
				;
			wrapperDiv.append(uploadDiv);
				
			var uploadForm = $(@@(xataface/modules/ajax_upload/upload_form.html)).insertAfter(uploadDiv);
			
                        var formData = [
					{name: '-action', value: 'ajax_upload_handleupload'},
					{name: '--field', value: $(self).attr('data-xf-field')},
					{name: '-table', value: $(self).attr('data-xf-table')},
					{name: '--record-id', value: recordId},
                                        {name: '--lang', value : window.XF_LANG}
						
				];
                        if ( !window.XF_LANG){
                            formData.pop();
                        }
                        
			var maxFileSize = parseInt($(self).attr('data-xf-max-file-size'));
			uploadDiv.fileupload({
				dropZone : wrapperDiv,
                                pasteZone : null,
				dataType: 'json',
				maxFileSize: maxFileSize,
				error: function(jqXHR, textStatus, errorThrown){
					if ( errorThrown === 'abort' ){
						progressDiv.hide();
					
					} else {
						alert("Upload failed: "+textStatus);
					}
					uploadInProgress = false;
					uploadDiv.show();
                    console.log("error log ", this);
				
				},
				url: DATAFACE_SITE_HREF+'?-action=ajax_upload_handleupload',
				done: function(e, data){
					uploadInProgress = false;
					progressDiv.hide();
					
					$.each(data.result, function(index, file){
                        uploadInProgress = false;
						if ( typeof(file.error) != 'undefined' && file.error ){
							alert(file.message);
        					
        					uploadDiv.show();
						} else {
							$(self).attr('data-xf-file-size', file.size);
							$(self).attr('data-xf-file-name', file.name);
							$(self).attr('data-xf-file-type', file.type);
							$(self).val('xftmpimg://'+file.id);
							update();
							$(self).trigger('change');
							
						}
					
						//$('<p/>').text(file.name).appendTo(document.body);
					});
				},
				beforeSend: function(event, data){


					for (var i=0; i<data.files.length; i++ ){
						if ( data.files[i].size > maxFileSize ){
							alert('This file is too big.  Max allowed size is '+maxFileSize+' bytes but this file is '+data.files[i].size+' bytes.');
							return false;
						}
						
						var allowedExtensions = $(self).attr('data-xf-allowed-extensions');
						if ( allowedExtensions ){
							allowedExtensions = allowedExtensions.replace(/ /, '').split(',');
							var match = false;
							//console.log(data.files[i]);
							$.each(allowedExtensions, function(k,val){
								var regex = new RegExp('\.'+val+'$', 'i');
								//console.log("Testing "+val+" against "+data.files[i].name);
								if ( regex.test(data.files[i].name) ){
									match = true;
								}
							});
							if ( !match ){
								alert('Upload failed.  Wrong file extension.  Allowed extensions include '+allowedExtensions.join(', ')+' only.');
								return false;
							}
							
						}
						
						var disallowedExtensions = $(self).attr('data-xf-disallowed-extensions');
						if ( disallowedExtensions ){
							disallowedExtensions = disallowedExtensions.replace(/ /, '').split(',');
							var match = false;
							//console.log(data.files[i]);
							$.each(disallowedExtensions, function(k,val){
								var regex = new RegExp('\.'+val+'$', 'i');
								
								if ( regex.test(data.files[i].name) ){
									match = true;
								}
							});
							if ( match ){
								alert('Upload failed.  Disallowed File Extension.');
								return false;
							}
							
						}
						
					}
					
					progressDiv.show();
					progressBar.progressbar({value: 0});
					jqXHR = event;	/// Keep the jqXHR method so the cancel button will work on it
					uploadInProgress = true;
					uploadDiv.hide();
				
				},
				formData: formData
			})
				.bind('fileuploadprogress', function(e, data){
					progressBar.progressbar({value: (parseInt(data.loaded / data.total * 100, 10))});
					//$('body').append('<div>Progress: '++'</div>');
					
				});
			
			;
			
			
			update();
			
			
		});
		
		
	
	});
})();
