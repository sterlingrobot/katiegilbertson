//require <ckeditor.js>
//require <jquery.packed.js>

(function(){
	var $ = jQuery;

	registerXatafaceDecorator(function(node){

		// Defines a toolbar with only one strip containing the "Source" button, a
		// separator and the "Bold" and "Italic" buttons.
		CKEDITOR.config.toolbar_Slim =
		[
		    [ 'Source', '-', 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
		    ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote']
		];
		CKEDITOR.config.toolbar = 'Slim';

		$('textarea.xf-ckeditor', node).each(function(){

			var customConfig = {};
			if ( $(this).attr('data-xf-ckeditor-config') ){
				var strconf = $(this).attr('data-xf-ckeditor-config');
				var newConf = {};
				try {
					eval('newConf='+strconf+';');

				} catch (e){}
				$.extend(customConfig, newConf);
			}
			CKEDITOR.replace(this, customConfig);
		});
	});

})();
