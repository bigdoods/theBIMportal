	$(function(){
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
	});
