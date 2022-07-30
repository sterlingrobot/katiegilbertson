//require <jquery.packed.js>
//require <jquery-ui.min.js>
//require-css <jquery-ui/jquery-ui.css>
//require <xataface/modules/ajax_upload/lib/jquery.iframe-transport.js>
//require <xataface/modules/ajax_upload/lib/jquery.fileupload.js>
//require <xataface/modules/ajax_upload/lib/jquery.lightbox-0.5.min.js>
//require-css <xataface/modules/ajax_upload/lib/jquery-lightbox/css/jquery.lightbox-0.5.css>

//require-css <xataface/modules/ajax_upload/ajax_upload.css>  

(function(){
        window.XF_LANG = window.XF_LANG || null;
	var $ = jQuery;
	var ajax_upload = XataJax.load('xataface.modules.ajax_upload');
	ajax_upload.UploadController = UploadController;
	
	UploadController.DEFAULT_THUMBNAIL_WIDTH = 50;
	UploadController.DEFAULT_THUMBNAIL_HEIGHT = 50;
	UploadController.DEFAULT_MAX_FILE_SIZE = 1024*1024;
	UploadController.DEFAULT_ALLOWED_EXTENSIONS = "jpg,jpeg,gif,png,pdf,doc,docx,xsl,xlsx,csv,mov,mp4,mp3";
	UploadController.DEFAULT_DISALLOWED_EXTENSIONS = "php,cgi,pl,php3,php4,php5,exe,rb,py,class,sh"
	
	function UploadController(o){
		if ( typeof(o) == 'undefined' ){
			o = {};
		}
		
		
		this.recordId = '';
		this.fieldName = null;
		this.tableName = null;
		this.thumbnailWidth = UploadController.DEFAULT_THUMBNAIL_WIDTH;
		this.thumbnailHeight = UploadController.DEFAULT_THUMBNAIL_HEIGHT;
		this.uploadInProgress = false;
		this.maxFileSize = UploadController.DEFAULT_MAX_FILE_SIZE;
		this.allowedExtensions = UploadController.DEFAULT_ALLOWED_EXTENSIONS.split(',');
		this.disallowedExtensions = UploadController.DEFAULT_DISALLOWED_EXTENSIONS.split(',');
		this.fileName = null;
		this.fileSize = null;
		this.fileType = null;
		this.previewUrl = null;
		
		$.extend(this,o);
		
		this.originalEl = this.el;
		
		this.fileInputEl = $('<input type="file" class="xf-file-input"/>').get(0);
		
		if ( typeof(this.el) == 'undefined' ){
			this.el = $('<input type="text" style="display:none" class="xf-ajax-upload"/>');
		} else if ( $(this.el).attr('type') == 'file' ){
			this.fileInputEl = $(this.el).get(0);
			this.el = $('<input type="hidden" style="display:none" class="xf-ajax-upload"/>').get(0);
			if ( $(this.fileInputEl).parent().length ){
				
				$(this.fileInputEl).replaceWith(this.el);
			}
		}
		
		
		this.el = $(this.el).get(0);
		$(this.el).hide();		
		
		
		this.deleteBtn = $('<button class="xf-ajax-file-upload-delete-button">Delete</button>').get(0);
		this.replaceBtn = $('<button class="xf-ajax-file-upload-replace-button">Replace</button>').get(0);
		this.cancelUploadBtn = $('<button class="xf-ajax-file-upload-cancel-upload-button">Cancel</button>').get(0);
		this.cancelReplaceBtn = $('<button class="xf-ajax-file-upload-cancel-replace-button">Cancel</button>').get(0);
		this.wrapperDiv = $('<div/>').addClass('xf-ajax-upload-wrapper').get(0);
		this.previewImg = $('<img src="#" class="xf-ajax-upload-thumbnail"/>').get(0);
		this.previewLink = $('<a href="#" target="_blank" class="xf-ajax-upload-thumbnail-preview"/>')
				.append(this.previewImg).get(0);
		this.previewDiv = $('<div/>')
				.addClass('xf-ajax-upload-file-preview').append(this.previewLink)
				.append($('<div class="xf-ajax-upload-filename">Filename</div>'))
				.append($('<div class="xf-ajax-upload-filesize">Filesize</div>'))
				.append(
					$('<div class="xf-ajax-upload-buttons"/>')
						.append(this.deleteBtn)
						.append(this.replaceBtn)
				).get(0)
				;
		$(this.wrapperDiv).append(this.previewDiv);
		this.progressBar = $('<div/>')
			.addClass('xf-ajax-upload-progress-bar')
			.progressbar()
			.get(0);
			
		this.progressDiv = $('<div/>')
			.addClass('xf-ajax-upload-progress')
			.append(this.progressBar)
			.append(this.cancelUploadBtn)
			.hide()
			.get(0);
				
		$(this.wrapperDiv).append(this.progressDiv);
		
		
		this.uploadDiv = $('<div/>')
				.addClass('xf-ajax-upload-filechooser')
				.append(
					this.fileInputEl
				)
				.append($(this.cancelReplaceBtn).hide())
				.get(0)
				;
		$(this.wrapperDiv).append(this.uploadDiv);
		
		this.install();
			
	}
	
	(function(){
		
		$.extend(UploadController.prototype, {
			update : update,
			install : install,
			uninstall : uninstall,
			getValue : getValue,
			setValue : setValue
		
		});
		
		
		function getValue(){
			return $(this.el).val();
		}
		
		function setValue(val){
			$(this.el).val(val);
		}
		
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
		
		
		function install(){
			var self = this;
			
			
			self.jqXHR = null;
			
			/**
			 * The delete button that appears on the preview field deletes the file 
			 * that is currently uploaded to this field.
			 *
			 * Clicking this button should delete the file, hide the preview 
			 * panel and show the upload button again.
			 */
			$(self.deleteBtn).click(function(){
			
				function deleteCallback(res){
					
					try {
						if ( res.code == 200 || res.code == 404 ){
							$(self.el).val('');
							self.update();
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
				if ( $(self.el).val().indexOf('xftmpimg://') === 0 ){
					//alert($(self).val().substr(11));
					deleteTempFile(
						self.tableName, 
						self.fieldName, 
						$(self.el).val().substr(11),
						deleteCallback
					);
					
				} else {
					deleteFile(
						self.tableName,
						self.fieldName,
						self.recordId,
						deleteCallback
					);
				}
				
				return false;
			});
			
			$(self.replaceBtn).click(function(){
			
				$(self.uploadDiv).show();
				$(self.cancelReplaceBtn).show();
				return false;
			});
			
			$(self.cancelReplaceBtn).click(function(){
			
				$(self.uploadDiv).hide();
				$(self.cancelReplaceBtn).hide();
				return false;
			});
			
			
			$(self.cancelUploadBtn).click(function(){
				try {
					self.jqXHR.abort();
				} catch (e){}
				return false;
			});
			
			$(self.wrapperDiv).insertAfter(self.el);
		
				
			
			
			
		
			
			
			
			
				
			
			
				
			var uploadForm = $(@@(xataface/modules/ajax_upload/upload_form.html)).insertAfter(self.uploadDiv);
			//console.log("About to send upload");
			//console.log(self);
			$(self.uploadDiv).fileupload({
				dataType: 'json',
				maxFileSize: self.maxFileSize,
				error: function(jqXHR, textStatus, errorThrown){
					if ( errorThrown === 'abort' ){
						$(self.progressDiv).hide();
					
					} else {
						alert("Upload failed: "+textStatus);
					}
					self.uploadInProgress = false;
					$(self.uploadDiv).show();
				
				},
				url: DATAFACE_SITE_HREF+'?-action=ajax_upload_handleupload',
				done: function(e, data){
					self.uploadInProgress = false;
					$(self.progressDiv).hide();
					
					$.each(data.result, function(index, file){
						if ( typeof(file.error) != 'undefined' && file.error ){
							alert(file.message);
						} else {
							self.fileSize = file.size;
							self.fileName = file.name;
							self.fileType = file.type;
							
							$(self.el).val('xftmpimg://'+file.id);
							self.update();
							$(self.el).trigger('change');
							$(self).trigger('fileChanged');
							
						}
					
						//$('<p/>').text(file.name).appendTo(document.body);
					});
				},
				beforeSend: function(event, data){
					
					for (var i=0; i<data.files.length; i++ ){
						if ( data.files[i].fileSize > self.maxFileSize ){
							alert('This file is too big.  Max allowed size is '+self.maxFileSize+' bytes but this file is '+data.files[i].fileSize+' bytes.');
							return false;
						}
						
						var allowedExtensions = self.allowedExtensions;
						if ( allowedExtensions ){
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
						
						var disallowedExtensions = self.disallowedExtensions;
						if ( disallowedExtensions ){
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
					
					$(self.progressDiv).show();
					$(self.progressBar).progressbar({value: 0});
					self.jqXHR = event;	/// Keep the jqXHR method so the cancel button will work on it
					self.uploadInProgress = true;
					$(self.uploadDiv).hide();
				
				},
				formData: function(){ var out =  [
					{name: '-action', value: 'ajax_upload_handleupload'},
					{name: '--field', value: self.fieldName},
					{name: '-table', value: self.tableName},
					{name: '--record-id', value: self.recordId},
                                        {name: '--lang', value : window.XF_LANG}
						
                                    ];
                                    if ( !window.XF_LANG ){
                                        out.pop();
                                    }
                                    return out;
                                }
			})
				.bind('fileuploadprogress', function(e, data){
					$(self.progressBar).progressbar({value: (parseInt(data.loaded / data.total * 100, 10))});
					//$('body').append('<div>Progress: '++'</div>');
					
				});
			
			;
			
			self.update();
		}
		
		function uninstall(){
			if ( self.originalEl && self.originalEl != self.el ){
				$(self.el).replaceWith(self.originalEl);
			}
			$(self.el).show();
			$(self.wrapperDiv).remove();
		}
		
		
		function update(){
			var self = this;
			if ( $(self.el).val() ){
				var val = $(self.el).val();
				
				self.thumbnailUrl = DATAFACE_SITE_HREF
					+'?-action=ajax_upload_get_thumbnail&--field='
					+encodeURIComponent(self.fieldName)
					+'&-table='+encodeURIComponent(self.tableName)
                                        +((window.XF_LANG)?('&--lang='+encodeURIComponent(window.XF_LANG)):'');
				if ( val.indexOf('xftmpimg://') == 0 ){
					self.thumbnailUrl += '&--tempfileid='+encodeURIComponent(val.substr(11));
				}
				if ( self.recordId ){
					self.thumbnailUrl += '&--recordId='+encodeURIComponent(self.recordId);
				}
				//console.log("updating upload thumbnail "+self.thumbnailUrl);
				self.previewUrl = self.thumbnailUrl;
				
				self.thumbnailUrl += '&--max_width='+encodeURIComponent(self.thumbnailWidth);
				self.thumbnailUrl += '&--max_height='+encodeURIComponent(self.thumbnailHeight);
				
				self.previewUrl += '&--max_width='+encodeURIComponent(Math.round($(window).width()*0.75));
				self.previewUrl += '&--max_height='+encodeURIComponent(Math.round($(window).height()*0.75));
                                self.previewUrl += ((window.XF_LANG)?('&--lang='+encodeURIComponent(window.XF_LANG)):'');
				
				if ( self.fileSize == null || 
					 self.fileType == null || 
					 !self.fileName || 
					 (self.fileName.indexOf('xftmpimg://') == 0) ){
					
					var q = {
					
						'-table': self.tableName,
						'--field': self.fieldName,
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
						q['--recordId'] = self.recordId;
					}
					
					//alert("about to make request for file "+val.substr(11)); 
					self.loadedDetails = false;
					self.fileName = 'Loading details...';
					self.fileSize = 0;
					self.fileType = '...';
					
					$(self.previewDiv).hide();
					$(self.uploadDiv).show();
					setTimeout(function(){
						if ( !self.loadedDetails ){
							self.fileName = 'File not found';
							self.fileSize = 0;
							self.fileType = '';
							
						}
					}, 500);
					
					
					$.get(DATAFACE_SITE_HREF, q, function(res){
						
						try {
							self.fileName = res.name;
							self.fileSize = res.size;
							self.fileType = res.type;
							
							if ( res.url ){
								self.previewUrl = res.url;
								
							}
							self.loadedDetails = true;
							self.update();
						} catch (e){
							self.fileName = 'File not found';
							self.fileSize = 0;
							self.fileType = 'none';
							self.previewUrl = '';
							
							self.update();
						}
						
					});
			
				
				} else {
				
					if ( !self.fileName ){
						self.fileName = val;
					}
					
					$('.xf-ajax-upload-filename', self.previewDiv).text(self.fileName);
					
					if ( self.fileSize ){
						$('.xf-ajax-upload-filesize', self.previewDiv).show().text(fileSizeStr(self.fileSize));
					} else {
						$('.xf-ajax-upload-filesize', self.previewDiv).hide();
					}
					$(self.previewLink).unbind('click');
					var fileType = self.fileType;
					if ( fileType.indexOf('image/') == 0 ){
						$(self.previewLink).lightBox();
						$(self.previewLink).attr('href', self.previewUrl);
					} else if ( self.previewUrl )  {
						$(self.previewLink).attr('href', self.previewUrl);
					} else {
						$(self.previewLink).click(function(){ return false;});
					}
					
					// The field is not empty.. we show the preview
					$('.xf-ajax-upload-thumbnail', self.previewDiv).attr('src', self.thumbnailUrl);
					
					
			
					$(self.previewDiv).show();
					$(self.uploadDiv).hide();
				}
				
				
			
			} else {
			
			
				// The field is empty.  We show the upload form.
				
				$(self.previewDiv).hide();
				$(self.uploadDiv).show();
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
				'--fileId': fileId
			};
			
			
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
					'--recordId': recordId
				};
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
	
	})();
	
	
})();