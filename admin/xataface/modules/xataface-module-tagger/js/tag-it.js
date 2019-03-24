(function($) {

	$.fn.tagit = function(options) {
		if ( typeof(options.onRemove) != 'function' ){
			options.onRemove = function(){};
		}
		if ( typeof(options.onAdd) != 'function' ){
			options.onAdd = function(){};
		}
		if ( typeof(options.beforeRemove) != 'function' ){
			options.beforeRemove = function(){};
		}
		
		
		
		
		return this.each(function(){	
			var el = $(this);
	
			var BACKSPACE		= 8;
			var ENTER			= 13;
			var SPACE			= 32;
			var COMMA			= 44;
	
			// add the tagit CSS class.
			el.addClass("tagit");
			
			el.children('li').each(function(){
				$(this).addClass('tagit-choice');
				var val = $(this).text();
				var span = '<span class="tagit-label">'+($(this).text())+'</span>';
				$(this).html(span+"<a class=\"close\">x</a>\n<input type=\"hidden\" style=\"display:none;\" value=\""+val+"\">\n");
			
			});
		
			// create the input field.
			var html_input_field = "<li class=\"tagit-new\"><input class=\"tagit-input\" type=\"text\" /></li>\n";
			el.append (html_input_field);
	
			var tag_input		= el.children(".tagit-new").children(".tagit-input");
	
			$(this).click(function(e){
				if (e.target.tagName == 'A') {
					// Removes a tag when the little 'x' is clicked.
					// Event is binded to the UL, otherwise a new tag (LI > A) wouldn't have this event attached to it.
					
					var removed = $(e.target).parent().children('input').val();
					try {
						options.beforeRemove(removed);
					} catch (e){
						// An exception signifies that we won't be removing this one afterall.
						return;
					}
					$(e.target).parent().remove();
					options.onRemove(removed, el, $(e.target).parent('li'));
				}
				else {
					// Sets the focus() to the input field, if the user clicks anywhere inside the UL.
					// This is needed because the input field needs to be of a small size.
					tag_input.focus();
				}
			});
	
			tag_input.keypress(function(event){
				if (event.which == BACKSPACE) {
					if (tag_input.val() == "") {
						// When backspace is pressed, the last tag is deleted.
						var removed = $(el).children(".tagit-choice:last input").val();
						try {
							options.beforeRemove(removed);
						} catch (e){
							// An exception signifies that we won't be removing this one afterall.
							return;
						}
						$(el).children(".tagit-choice:last").remove();
						options.onRemove(removed, el);
					}
				}
				// Comma/Space/Enter are all valid delimiters for new tags.
				else if (/*event.which == COMMA || event.which == SPACE ||*/ event.which == ENTER) {
					event.preventDefault();
	
					var typed = tag_input.val();
					typed = typed.replace(/,+$/,"");
					typed = typed.trim();
	
					if (typed != "") {
						if (is_new (typed)) {
							create_choice (typed);
						}
						// Cleaning the input.
						tag_input.val("");
					}
				}
			});
	
			tag_input.autocomplete({
				source: options.availableTags, 
				select: function(event,ui){
					if (is_new (ui.item.value)) {
						create_choice (ui.item.value);
					}
					// Cleaning the input.
					tag_input.val("");
	
					// Preventing the tag input to be update with the chosen value.
					return false;
				}
			});
	
			function is_new (value){
				var is_new = true;
				tag_input.parents("ul").children(".tagit-choice").each(function(i){
					n = $(this).children("input").val();
					if (value == n) {
						is_new = false;
					}
				})
				return is_new;
			}
			function create_choice (value){
				var li = document.createElement('li');
				$(li).addClass('tagit-choice');
				var el2 = "";
				
				el2 = '<span class="tagit-label">'+value+'</span>' + "\n";
				el2 += "<a class=\"close\">x</a>\n";
				el2 += "<input type=\"hidden\" style=\"display:none;\" value=\""+value+"\">\n";
				
				$(li).html(el2);
				//alert($(li).html());
				
				var li_search_tags = tag_input.parent();
				$(li).insertBefore (li_search_tags);
				tag_input.val("");
				//alert('adding '+value);
				options.onAdd(value, el, li);
			}
		});
	};

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	};

})(jQuery);
