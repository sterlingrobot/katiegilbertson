/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if ( typeof(Dataface) == 'undefined' ) Dataface = {};
if ( typeof(Dataface.modules) == 'undefined' ) Dataface.modules = {};
if ( typeof(Dataface.modules.DataGrid) == 'undefined' ) Dataface.modules.DataGrid = {};
if ( typeof(Dataface.Ext) == 'undefined' ) Dataface.Ext = {};
if ( typeof(Dataface.Ext.Valuelists ) == 'undefined' ) Dataface.Ext.Valuelists = {};
if ( typeof(Dataface.renderers) == 'undefined' ) Dataface.renderers = {};


Dataface.modules.DataGrid.init = function(){
	for (vname in Dataface.Valuelists){
		Dataface.Ext.Valuelists[vname] = [];
		for ( id in Dataface.Valuelists[vname] ){
			Dataface.Ext.Valuelists[vname][Dataface.Ext.Valuelists[vname].length] = [id, Dataface.Valuelists[vname][id]];
		}
	}
};

/**
 * The the fielddef definition is a definition as defined for a dataface field.
 * This function creates a javascript object that can be used in an Ext 
 * DataGrid column model.
 *
 */
Dataface.modules.DataGrid.buildColumn = function(fielddef, colName){
	if ( typeof(colName) == 'undefined' ) colName = fielddef.Field;
	if ( !fielddef.columnWidth ) fielddef.columnWidth = 150;
	var out = {
		id: colName,
		header: fielddef.widget.label,
		dataIndex: colName,
		width: fielddef.columnWidth,
		editor: this.getEditor(fielddef)
	};
	if ( fielddef.vocabulary ){
		out.renderer = Dataface.renderers[fielddef.vocabulary];
	}
	
	return out;
};

/**
 * Returns an ext compatible editor based on the given field
 * definition.
 */
Dataface.modules.DataGrid.getEditor = function(fielddef){
	var options = this.getEditorOptions(fielddef);
	switch (fielddef.widget.type){
		case 'text':
			if ( fielddef.Type.match(/int|float|double/) ){
				return new Ext.form.NumberField(options);
			} else {
				return new Ext.form.TextField(options);
			}
			
		case 'textarea':
			return new Ext.form.TextArea(options);
			
		case 'htmlarea':
			return new Ext.form.HtmlEditor(options);
			
		case 'hidden':
			return new Ext.form.Hidden(options);
			
		default:
			
			if ( fielddef.Type.match(/date|timestamp/) ){
				return new Ext.form.DateField(options);
			} else if ( fielddef.Type.match(/time/) ){
				return new Ext.form.TimeField(options);
			} else if ( fielddef.vocabulary && !fielddef.repeat ){
				return new Ext.form.ComboBox(options);
			} else {
				return new Ext.form.TextField(options);
			}
			
		 
	}
};

/**
 * Returns options object that can be used as a parameter
 * in the editor constructors.
 *
 * @param object fielddef
 */
Dataface.modules.DataGrid.getEditorOptions = function(fielddef){
	var options = {};
	if ( fielddef.validators.required ) options.allowBlank = false;
	if ( fielddef.validators.regex ) options.regex = fielddef.validators.regex;
	
	if ( fielddef.vocabulary ){
		//options.transform = 'Dataface_modules_DataGrid_vocabulary_'+fielddef.vocabulary;
		options.store = new Ext.data.SimpleStore({
			fields: ['key', 'value'],
			data: Dataface.Ext.Valuelists[fielddef.vocabulary]
		});
		options.displayField = 'value';
		options.valueField = 'key';
		options.mode = 'local';
		options.typeAhead = true;
		options.triggerAction = 'all';
		//options.lazyRender = true;
		options.listClass = 'x-combo-list-small';
		
	}
	if ( fielddef.Type.match(/datetime|timestamp/) ){
		options.format = 'Y-m-d H:i:s';
	} else if (fielddef.Type.match(/date/) ){
		options.format = 'Y-m-d';
	} else if ( fielddef.Type.match(/time/) ){
		options.format = 'H:i:s';
	}
	return options;
};


Dataface.modules.DataGrid.buildColumns = function(fielddefs){
	var columns = [];
	for ( i in fielddefs ){
		columns[ columns.length ] = this.buildColumn(fielddefs[i], i);
	}
	return columns;
};

Dataface.modules.DataGrid.getColumnModel = function(fielddefs){
	var defs = this.buildColumns(fielddefs);
	return new Ext.grid.ColumnModel(defs);
};

Dataface.modules.DataGrid.getRecord = function(fielddefs){
	var params = [];
	for ( i in fielddefs ){
		params[params.length] = this.getRecordColumn(fielddefs[i], i);
	}
	return Ext.data.Record.create(params);
};

Dataface.modules.DataGrid.getRecordColumn = function(fielddef, fieldID){
	if ( !fieldID ) fieldID = fielddef.Field;
	var out = {
		name: fieldID,
		type: this.getRecordColumnType(fielddef)
	};
	if ( fielddef.Type.match(/datetime|timestamp/) ){
		out.dateFormat = 'Y-m-d H:i:s';
	} else if ( fielddef.Type.match(/date/) ){
		out.dateFormat = 'Y-m-d';
	} else if ( fielddef.Type.match(/time/) ){
		out.dateFormat = 'H:i:s';
	}
	return out;
};

Dataface.modules.DataGrid.getRecordColumnType = function(fielddef){
	if ( fielddef.Type.match(/varchar|char|text|enum|set/) ){
		return 'string';
	} else if ( fielddef.Type.match(/date|time/) ){
		return 'date';
	} else if ( fielddef.Type.match(/boolean|tinyint/) ){
		return 'bool';
	} else if ( fielddef.Type.match(/int/) ){
		return 'int';
	} else if ( fielddef.Type.match(/float|double|decimal/) ){
		return 'float';
	} else {
		return 'string';
	}
};

Dataface.modules.DataGrid.toggleFullScreen = function(){
	var el = document.getElementById('editor-grid');
	if ( el.className == 'dataGrid-fullScreen' ){
		el.className = '';
		Dataface.modules.DataGrid.grid.setHeight(400);
		Dataface.modules.DataGrid.grid.setWidth(640);
		Dataface.modules.DataGrid.grid.syncSize();
	} else {
		el.className = 'dataGrid-fullScreen';
		var winSize = Dataface.modules.DataGrid.getWindowSize();
		Dataface.modules.DataGrid.grid.setHeight(winSize[1]);
		Dataface.modules.DataGrid.grid.syncSize();

	}
};


Dataface.modules.DataGrid.getWindowSize = function() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  return [myWidth,myHeight];
  


};


Dataface.modules.DataGrid.create = function(params){
	if ( params.id ) this.id = params.id;
	if ( !this.id ){
		var parts = window.location.search.split('&');
		for ( var i=0; i<parts.length; i++){
			var keyval = parts[i].split('=');
			if (keyval[0] == '-gridid'){
				this.id = keyval[1];
				break;
			}
		}
	}
	var fielddefs = params.fielddefs;
	
	if ( !params.storeURL ) params.storeURL = this.getDataStoreURL();
	
	// shorthand alias
    var fm = Ext.form;

    var cm = this.getColumnModel(fielddefs);
    cm.defaultSortable = true;
    params.cm = cm;

    var RowRecord = this.getRecord(fielddefs);
    this.RowRecord = RowRecord;
    var store = this.getDataStore(params.storeURL, RowRecord);
	params.store = store;
    if ( !params.height ) params.height = 400;
    //params.autoHeight=true;
    //if ( !params.width ) params.width = '100%';
    if ( !params.renderTo ) params.renderTo = 'editor-grid';
	//if ( !params.title ) params.title = 'Edit Records';
	if ( typeof(params.frame) == 'undefined' ) params.frame = true;
	if( typeof(params.stripeRows) == 'undefined' ) params.stripeRows = true;
	if( typeof(params.clicksToEdit) == 'undefined' ) params.clicksToEdit = 2;
	
	if ( typeof(params.tbar)  == 'undefined' ){
		params.tbar =  [{
			text: 'Add Row',
			handler : function(){
				var p = new RowRecord({});
				for (var i in fielddefs){
					if ( fielddefs[i].Default != null ) p.data[i] = fielddefs[i].Default;
					else p.data[i] = '';
				}
				grid.stopEditing();
				store.insert(0, [p]);
				grid.startEditing(0, 0);
			
			}	
		},{
			text: 'Toggle Full Screen',
			handler: Dataface.modules.DataGrid.toggleFullScreen
		},{
			text: 'Export CSV',
			handler: function(){
				var url = Dataface.modules.DataGrid.getDataStoreURL()+'&--format=csv';
				window.location.href=url;
				
			}
		}];
	}
	
	params.sm = new Ext.grid.RowSelectionModel({
		singleSelect: true,
		moveEditorOnEnter: true
		});
     // create the editor grid

    var grid = new Ext.grid.EditorGridPanel(params);
    store.load();
    
    grid.addListener('beforeedit', function(o){
    	Dataface.modules.DataGrid.currentlyEditedRecord = o.record;
    });
    
    grid.addListener('afteredit', function(o){
    	Dataface.modules.DataGrid.currentlyEditedRecord = null;
    });
    this.grid = grid;
    return grid;
    
    
};
Dataface.modules.DataGrid.currentlyEditedRecord=null;
Dataface.modules.DataGrid.updates = {};
Dataface.modules.DataGrid.pendingUpdates = {};
Dataface.modules.DataGrid.addUpdate = function(recordID, vals){
	if ( this.updates[recordID] ){
		for ( key in vals ){
			this.updates[recordID][key] = vals[key];
		}
	} else {
		this.updates[recordID] =vals;
	}
};
Dataface.modules.DataGrid.updatesWaiting = function(){
	var count = 0;
	for ( var i in this.updates ) count++;
	return (count>0);
}


Dataface.modules.DataGrid.saving = false;
Dataface.modules.DataGrid.save = function(showAlert){
	if ( !this.updatesWaiting() ) return;
	if ( typeof(showAlert) == 'undefined' ) showAlert = false;
	if ( this.saving && showAlert ){
		Ext.MessageBox.alert("Another save attempt is already in progress");
	}
	
	if ( this.saving ) return false;
	this.saving = true;
	this.pendingUpdates = this.updates;
	
	// Clear the updates queue
	this.updates = {};
	
	// We don't want to save the currently edited record
	// we'll wait until we're done editing it.
	
	if ( this.currentlyEditedRecord ){
		for ( var i in this.pendingUpdates ){
			if ( i == this.currentlyEditedRecord.id ){
			
				// Place back in the updates queue so that it will eventually 
				// be saved.
				this.updates[i] = this.pendingUpdates[i];
				delete this.pendingUpdates[i];
				
				break;
			}
		}
	}
	
	// Let's check to see if there are still any updates to do
	var stillUpdates = false;
	for ( var i in this.pendingUpdates ){
		stillUpdates = true;
		break;
	}
	if ( !stillUpdates ){
		this.saving=false;
		return;
	}
	
	
	Ext.Ajax.request({
		waitMsg: 'Saving changes ...',
		url: window.location.pathname,
		method: 'POST',
		params: {
			'-action': 'DataGrid_datasource_update',
			'-data': Ext.util.JSON.encode(this.pendingUpdates),
			'-gridid': this.id
		},
		failure: function(response,options){
			Ext.MessageBox.alert("Failed to save changes to the server...");
			var dg = Dataface.modules.DataGrid;
			dg.saving = false;
			for ( recordID in dg.pendingUpdates ){
				dg.addUpdate(recordID, dg.pendingUpdates[recordID]);
			}
			dg.pendingUpdates = {};
		},
		success: function(response,options){
			var dg = Dataface.modules.DataGrid;
			
			var responseData = Ext.util.JSON.decode(response.responseText);
			
			if ( !responseData ){
				Ext.MessageBox.alert('Error','An error occurred while attempting to save the record');
				for ( recordID in dg.pendingUpdates ){
					dg.addUpdate(recordID, dg.pendingUpdates[recordID]);
				}
			
			} else if ( responseData.errors ){
			
				var errorMsgs = [];
				for ( recordID in responseData.errors ){
					errorMsgs[errorMsgs.length] = 'Problem saving record '+recordID+': '+responseData.errors[recordID];
					
				}
				Ext.MessageBox.alert('Errors occurred', errorMsgs.join('<br />'));
				for ( recordID in dg.pendingUpdates ){
					
					
					if ( typeof(responseData.errors[recordID]) == 'undefined' ){
						var rec = dg.store.getById(recordID);
						rec.reject();
						dg.addUpdate(recordID, dg.pendingUpdates[recordID]);
					}
				}
			}
			
			// Now we'll update all of the records that were changed
			// given the data we received back from the server
			if ( typeof(responseData.updated_rows) != 'undefined' ){
				for ( var i in responseData.updated_rows ){
					var rec = dg.store.getById(i);	// The current record in the store
					var index = dg.store.indexOf(rec);	// The row index of the current record
					var recCopy;	// Placeholder to copy the record

					if ( i != responseData.updated_rows[i]['__recordID__'] ){
						// Evidently the record was inserted new, because the record id
						// does not match the one we had in the store.
						// We need to delete the old record and replace it with a new
						// one with the proper id.
						recCopy = rec.copy(responseData.updated_rows[i]['__recordID__']);
						recCopy.reject();
						dg.store.remove(rec);
						//recCopy.id = responseData.updated_rows[i]['__recordID__'];
						//alert(recCopy.id);
						for ( var j in responseData.updated_rows[i] ){
							// We set the values of all valid attributes for this record
							// skipping __recordiD__
							if ( !j.match(/^__/) ) recCopy.set(j, responseData.updated_rows[i][j]);
						}
						// Insert this new record into the store at the same place
						// as the old record was
						dg.store.insert(index, [recCopy]);
						
						// Commit the changes to the store.
						recCopy.commit();
					} else {
					
						// This is an existing record - but we will still go through and 
						// update the values according to what the server sent us.
						// in case some triggers changed other values due to our save.
						for ( var j in responseData.updated_rows[i] ){
							if ( !j.match(/^__/) ) rec.set(j, responseData.updated_rows[i][j]);
						}
						
						// Commit our changes to the store.
						rec.commit();
					}

					delete dg.updates[i];
				}
			}
			
			dg.pendingUpdates = {};
			dg.saving=false;
			//dg.store.commitChanges();
			
			
			// Go through the updates queue and re-apply the changes
			for (var thisrecid in dg.updates){
				if ( dg.updates[thisrecid] == null ) continue;
				var rec = dg.store.getById(thisrecid);
				for ( var fld in dg.updates[thisrecid]){
					rec.set(fld,dg.updates[thisrecid][fld]);
				}
			}	
			
			
		}
	});
			
};


Dataface.modules.DataGrid.getDataStore = function(baseURL, record){
	if ( typeof(this.store) == 'undefined' ){
		var store = new Ext.data.Store({
			url: this.getDataStoreURL(baseURL),
			reader: new Ext.data.XmlReader({
					record: 'row',
					id: '@id'
				}, record)
		});
		
		store.addListener('update', function(store, record, operation){
			//alert('Record ID' + record.id);
			if ( operation != 'edit' ) return;
			var dg = Dataface.modules.DataGrid;
			dg.addUpdate(record.id, record.getChanges());
			
		});
		this.store = store;
	}
	return this.store;
		
};

Dataface.modules.DataGrid.getDataStoreURL = function(baseURL){
	if ( !baseURL ) baseURL = window.location.href;
	if ( !baseURL.match(/[&?]-gridid=[^&]+/) ){
		baseURL += '&-gridid='+this.id;
	}
	if ( baseURL.match(/[&?]-action=[^&]+/)){
		baseURL = baseURL.replace(/([&?])-action=[^&]+/, '$1-action=DataGrid_datastore_xml');
	} else {
		if ( baseURL.indexOf('?') == -1 ) baseURL += '?';
		baseURL += '-action=DataGrid_datastore_xml';
	}
	return baseURL;
};



