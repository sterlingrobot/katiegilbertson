if ( typeof(Dataface) == 'undefined' ) Dataface = {};
if ( typeof(Dataface.modules) == 'undefined' ) Dataface.modules = {};
if ( typeof(Dataface.modules.DataGrid) == 'undefined' ) Dataface.modules.DataGrid = {};
if ( typeof(Dataface.modules.DataGrid.newForm) == 'undefined') Dataface.modules.DataGrid.newForm = {};

Dataface.modules.DataGrid.newForm.updateFieldsOptions = function(tablesSelect){
	var fieldsSelect = document.getElementById('__fields');
	var sfieldsSelect = document.getElementById('_fields');
	
	fieldsSelect.options.length = 0;
	var tColumns = availableColumns[tablesSelect.options[tablesSelect.selectedIndex].value];
	for ( field in tColumns){
		var fname = tColumns[field];
		var parts;
		if ( fname.indexOf('.') != -1 ){
			parts = fname.split('.');
		} else {
			parts = ['',fname];
		}
		fieldsSelect.options[fieldsSelect.options.length] = new Option(parts[1],fname);
	}
};
