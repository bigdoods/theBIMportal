	$(function(){

		var model_viewer = $('#model-viewer');

		// find a query string parameter (or add it) and change it's value
		function update_url_param(url, name, value){
			var url_parts = url.split('?'),
				query_string_parts = (url_parts[1] != undefined ? url_parts[1].split('&') : []),
				name_regexp = new RegExp('^'+name),
				found = false;


			$.each(query_string_parts, function(i,e){
				if(name_regexp.test(e)){
					query_string_parts[i] = name + '=' + value;
					found=true;
				}
			});

			if(!found)
				query_string_parts.push(name + '=' + value);

			url_parts[1] = query_string_parts.join('&');

			return url_parts.join('?');
		}


		// search the url for a query string parameter and set it to null
		// then rebuild the url and return it
		function remove_url_param(url, name){
			var url_parts = url.split('?'),
				query_string_parts = (url_parts[1] != undefined ? url_parts[1].split('&') : []),
				name_regexp = new RegExp('^'+name);


			$.each(query_string_parts, function(i,e){
				if(name_regexp.test(e)){
					query_string_parts[i] = null;
				}
			});

			url_parts[1] = query_string_parts.join('&');

			return url_parts.join('?');
		}

		// attach event to model selector dropdown
		var project_model_selector = $('select#project-model');
		if(project_model_selector.size() >0){
			project_model_selector.change(function(e){
				var new_url = update_url_param(window.location.href, 'model', $(this).val());
				new_url = remove_url_param(new_url, 'revision');
				
				window.location.href = new_url;
			});
		}

		// attach event to revision selector dropdown
		var model_revision_selector = $('select#model-revision');
		if(model_revision_selector.size() >0){
			model_revision_selector.change(function(e){
				window.location.href = update_url_param(window.location.href, 'revision', $(this).val());
			});
		}

		// show all objects for the current model
		var show_button = $('#viewer-show');
		if(show_button.size() >0){
			show_button.click(function(e){
				e.preventDefault();
				model_viewer.viewer('showall');
				return false;
			});
		}

		// hide the currently selected object(s)
		var hide_button = $('#viewer-hide');
		if(hide_button.size() >0){
			hide_button.click(function(e){
				e.preventDefault();
				model_viewer.viewer('eachselected', function(id){
					$(this).viewer('hide', id);
				});
				return false;
			});
		}

		// listen for the right mouse button and load the info window for the object under the curser
/*		model_viewer.bind('viewer.contextmenu', function(event, selected){
			if(selected != undefined){
				var current_url = window.location.href;
				current_url = update_url_param(current_url, 'action', 'object_info');
				current_url = update_url_param(current_url, 'object', selected);
				
			    // load info
			    $('#viewer-info-box').html('<h4>Loading</h4>');
			    $('#viewer-info-box').load(current_url);
			    $('#viewer-info-box').show();
			}else{
				$('#viewer-info-box').hide();
			}
		});*/

		model_viewer.bind('viewer.select', function(event, selected){
			if(selected != undefined && selected.length >0){
				var current_url = window.location.href;
				current_url = update_url_param(current_url, 'action', 'object_info');
				current_url = update_url_param(current_url, 'object', selected);

				//$('#component-list a[data-object-id='+ selected.shift() +']');
			    
			    // load info
			    $('#viewer-info-box').html('<h4>Loading</h4>');
			    $('#viewer-info-box').load(current_url);
			    $('#viewer-info-box').show();
			}else{
				$('#viewer-info-box').hide();
			}
		});

		var current_url = window.location.href;
		current_url = update_url_param(current_url, 'action', 'component_tree');
		
	    // load info
	    $('#component-list').html('<h4>Loading</h4>');
	    $('#component-list').load(current_url);
	    $('#component-list').show();

	    $('#component-list').on('click', 'a', function(e){
	    	e.preventDefault();
	    	model_viewer.viewer('select', $(this).attr('data-object-id'));
	    	return false;
	    });

	    // get height of window minus header and footer
	    var infoBoxHeight = $(window).height() - 168;
	    var componentListHeight = infoBoxHeight - 43;

	    // get width of window minus app menu
	    var modelViewerWidth = $('body').innerWidth() - 220;

	    //apply to info box, component list and model viewer
	    $("#viewer-info-box").css('height', infoBoxHeight);
		$("#component-list").css('height', componentListHeight);
		model_viewer.css('height', componentListHeight);
		model_viewer.css('width', modelViewerWidth);

		// update values on window resize
	    $(window).bind('resize', function(e)
		{
			infoBoxHeight = $(window).height() - 168;
			componentListHeight = infoBoxHeight - 43;
			modelViewerWidth = $('body').innerWidth() - 220;

			$("#viewer-info-box").css('height', infoBoxHeight);
			$("#component-list").css('height', componentListHeight);
			model_viewer.css('height', componentListHeight);
			model_viewer.css('width', modelViewerWidth);
		});




	});
