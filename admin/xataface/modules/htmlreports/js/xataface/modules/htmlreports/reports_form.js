//require <jquery.packed.js>
//require <ckeditor.js>
(function(){
	var $ = jQuery;
	
	var tb = $.merge([],CKEDITOR.config.toolbar_Basic);
	tb.push(['insertmacro']);
	CKEDITOR.config.toolbar_YBasic = tb;
	
	var templateField = $('textarea[data-xf-field="template_html"]');
	var tablenameField = $('[data-xf-field="tablename"]');
	
	tablenameField.each(function(){
		updateTableName();
		$(this).change(function(){
			updateTableName();
		});
	});
	
	
	function updateTableName(){
		//alert("updateing table name "+tablenameField.val());
		templateField.attr('data-xf-htmlreports-tablename', tablenameField.val());
	}
	
	
	var cssField = $('[data-xf-field="template_css"]');
	
	cssField.each(function(){
		updateCss();
		$(this).change(function(){
			updateCss();
		});
	});
	
	
	function updateCss(){
		//alert("updateing table name "+tablenameField.val());
		templateField.attr('data-xf-htmlreports-css', cssField.val());
	}
	
	
	
	
	
	
	
	
})();