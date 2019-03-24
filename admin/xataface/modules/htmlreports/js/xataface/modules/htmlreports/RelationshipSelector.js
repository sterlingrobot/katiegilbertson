/*
 * Xataface htmlreports Module
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
//require <xatajax.core.js>
//require <xatajax.ui.tk/Component.js>
//require <xataface/modules/htmlreports/__init__.js>

(function(){
	var $ = jQuery;
	
	var Component = XataJax.ui.tk.Component;
	window.xataface.modules.htmlreports.RelationshipSelector = RelationshipSelector;
	
	function RelationshipSelector(o){
		XataJax.extend(this, new Component(o));
		XataJax.publicAPI(this, {
			setSelectedRelationship: setSelectedRelationship,
			getSelectedRelationship: getSelectedRelationship,
			update: update
		});
		
		this.table = o.table;
		this.el = document.createElement('div');
		
		
		init(this);
	
	}
	
	
	/**
	 * Initializes the tree for a schema browser.
	 *
	 * @param {SchemaBrowser} sb The schema browser for which the tree is
	 *  being initialized.
	 * @return {void}
	 */
	function init(sb){
		sb.select = document.createElement('select');
		var q = {
			'-table': sb.table,
			'-action': 'htmlreports_schemabrowser_getschema'
		
		};
		
		$.ajax({
			url: DATAFACE_SITE_HREF,
			data: q,
			success: function(res){
				try {
					if ( res.code == 200 ){
						$.each(res.schema, function(){
							if ( this['data-key'] == 'relationships' ){
								var rels = {};
								$.each(this.children, function(){
									rels[this.attr['xf-htmlreports-relationshipname']] = this.data;
								});
								fillSelect(sb, rels);
								sb.trigger('loaded', {
									relationshipSelector: sb
								});
							}
						});
					}
					else if ( res.message ) throw res.message;
					else throw 'Faild to load fields.  See server log for details.';
				} catch(e){
					alert(e);
				}
			}
				
		});
		var el = sb.el;
		$(el).append(sb.select);
		
		$(sb.select).change(function(){
			var sel = this;
			sb.trigger('relationshipSelected', {
				relationshipSelector: sb,
				relationshipName: $(sel).val(),
				relationshipLabel: $(sel.options[sel.selectedIndex]).text()
			});
		});
	
	}
	
	function fillSelect(sb, options){
		$(sb.select).empty();
		$(sb.select).append($('<option></option').text('Select Relationship'));
		$.each(options, function(key,val){
			$(sb.select).append($('<option></option>').attr('value',key).text(val));
		});	
	}
	
	
	function setSelectedRelationship(rel){
		$(this.select).val(rel);
	}
	
	function getSelectedRelationship(){
		return $(this.select).val();
	}
	
	function update(){
		
		this.getSuper(Component).update();
		var el = this.getElement();
		$(el).children().detach();
		$.each(this.getChildComponents(), function(){
			$(el).append(this.getElement());
		});
	
		$(el).append(this.el);
	}
	
	
	
	
	
})();