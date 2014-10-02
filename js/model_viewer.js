	$(function(){

		var model_viewer = $('#model-viewer');

		// attach event to model selector dropdown
		var project_model_selector = $('select#project-model');
		if(project_model_selector.size() >0){
			project_model_selector.change(function(e){
				var current_url = window.location.href;
				current_url = current_url.replace(/model=[^&]+/, '');

				if(current_url.indexOf('?') <0)
					current_url += '?';

				window.location.href = current_url + 'model='+$(this).val();
			});
		}

		// attach event to revision selector dropdown
		var model_revision_selector = $('select#model-revision');
		if(model_revision_selector.size() >0){
			model_revision_selector.change(function(e){
				var current_url = window.location.href;
				current_url = current_url.replace(/revision=[^&]+/, '');

				if(current_url.indexOf('?') <0)
					current_url += '?';

				window.location.href = current_url + 'revision='+$(this).val();
			});
		}

		var show_button = $('#viewer-show');
		if(show_button.size() >0){
			show_button.click(function(e){
				model_viewer.viewer('showall');
			});
		}

		var hide_button = $('#viewer-hide');
		if(hide_button.size() >0){
			hide_button.click(function(e){
				model_viewer.viewer('eachselected', function(id){
					$(this).viewer('hide', id);
				});
			});
		}

		model_viewer.bind('viewer.contextmenu', function(event, selected) {
		    
		});
	});
