(function($){
	
	
	/**
	 * The doms are ready
	 */
	$(function(){
		/**
		 * Load the first tab on page load
		 */
		 $('ul.tabs li.active').click();
		 
	 /**
	  * Toggle between create project and list projects
	  */
	  $(document).on('click', '.create_project', function(){
			$.fn.bindValidation();
			$('.project-List,.project_edit').fadeOut();
			$('.project_create').fadeIn('slow');
			$.fn.bindValidation();
	  });
	  
	  /**
	   * Sbmit project creaztion  through ajax
	   */
	  $(document).on('submit', 'form[name=create_project]', function(){
		  var frm = $(this);
			$.ajax({			
				url : base_path+'admin/createProject/',
				data: frm.serialize(),
				'type': 'post',
				beforeSend:function(){
					frm.overlay(1);
					frm.overlay("Please wait");
				}, 
				success: function(r){
					if(r !== '0' ){
						frm.overlay("Project has been successfully created");
						forceLoad = true;
						$('.projectlist').click();
					}else{
						frm.overlay("The project creation failed");	
					}
				},
				error: function(){
					frm.overlay("Internal server error, please try after some time");
				},
				complete: function(){
					frm.overlay(0, -1);
				}
	  
			});
		 
	  	return false;
	  });
	  
	  /**
	   * Project edit form open
	   */
	  $(document) .on('click', '.p_edit', function(){
			var id =  $(this).attr('rel');
			var dom = $(this).closest('ul');
			$.ajax({
				url : base_path+'admin/editProject/'+id,
				beforeSend:function(){
					dom.overlay('Please wait');
				},
				success:function(r){
					$('.tab_content1').fadeOut();
					$('.project_edit').html(r).fadeIn();
				},
				error: function(){
					dom.overlay("Internal server error, please try after some time");
				},
				complete: function(){
					dom.overlay(0,-1);
				}

			});
	  })
	   
	   /**
	    * UPdate project details
		*/
		$(document).on('submit', 'form[name=project_edit]', function(){
			var frm = $(this);
			$.ajax({
				url : base_path+'admin/updateProject/',
				type: 'post',
				data: frm.serialize(),
				beforeSend: function(){
					frm.overlay(1);
					frm.overlay("Please wait");
				},
				success: function(){
					frm.overlay('Successfully updated');
					forceLoad = true;
					$('li.active').click();
				},	
				error: function(){
					frm.overlay("Internal server error, please try again latter");					
				},
				complete: function(){
					frm.overlay(0,-1);
				}
			})
			
		return false;
		});
	  /**
	   * user edit
	   */
	   $(document).on('click', '.userdetails', function(){
		var dom = $(this).closest('ul');
		var user_id = $(this).closest('ul').attr('rel').replace('user-', '');
		$.ajax({
			url: base_path+'admin/getUserDetail/'+user_id,
			type: 'post',
			beforeSend:function(){
				dom.overlay(1);
				dom.overlay("Please wait");
			},
			success:function(r){
				$('div.edit_form').html(r).fadeIn();
				$('div.list').fadeOut();
			},
			error:function(){
				dom.overlay("Internal server error, please try after some time");
			},
			complete: function(){
				dom.overlay(0,-1);
			}
		});
		return false;
	   });
	   
	   /**
	    * Open new app creation form
		*/
	  $(document).on('click', '.create_app', function(){
			$('.tab_content_detila_back').hide();
			$('.create_form').fadeIn();
	  });
	  /**
	   * create apps
	   */
	 	$(document).on('submit', 'form[name=create_app]', function(){
			var frm = $(this);
			$.ajax({
				url : base_path+'admin/createApp',
				data: frm.serialize(),
				type: 'post',
				beforeSend:function(){
					frm.overlay(1);
					frm.overlay("Please wait");
				},
				success: function(r){
					forceLoad = true;
					$('li.active').click();
				},
				error: function(){
					frm.overlay("An error occured, Please try after some time");
				},
				complete: function(){
					frm.overlay(0, -1);
				}
			});
			return false;
		});	
		
		/**
		 * Open the app edit form
		 */
		$(document).on('click', '.edit-app', function(){
			var appid = $(this).closest('ul').attr('rel').replace('app-','');
			var dom = $(this).closest('ul');
			$.ajax({
				url : base_path+ 'admin/editAppForm/'+appid,
				type : 'POST',
				beforeSend: function(){
					dom.overlay(1);
					dom.overlay("Please wait");
				},
				success: function(r){
					$('.tab_content_detila_back').fadeOut();
					$('.edit_form').fadeIn().html(r);
				},
				complete: function(){
					dom.overlay(0 ,-1);
				},
				error: function(){
					dom.overlay(" Internal server error, please try after some time");
				}
			});
			
		});
		
		/**
		 * edit the app details
		 */
		$(document).on('submit', 'form[name=edit_app]', function(){
			var frm = dom = $(this);
			
			
			$.ajax({
				url : base_path + 'admin/editApp/',
				data: frm.serialize(),
				type: 'post',
				beforeSend: function(){
					frm.overlay(1);
					frm.overlay(" Please wait");
				},
				success: function(r){
					forceLoad = true;
					$('li.active').click();
				},
				complete: function(){
					frm.overlay(0 ,-1);
				},
				error: function(){
					frm.overlay(" Internal server error, please try after sometime");
				}
			});
			return false;
		});
		/***/
		$(document).on('click', '.for_admin_ajax', function(){
			var dom = $('.right_details_tab');
			var url = $(this).attr('href');
			$.ajax({
				url:url,
				beforeSend:function(){
					dom.overlay(1);
					dom.overlay("Please wait");
				},
				success:function(r){
					dom.overlay("Data fetched");
					if(r !== '-1'){
						dom.html(r);
					}
				},
				complete:function(){
					dom.overlay(0,-1);
				}
			});
			return false;
		});
		
		/**
		 * Adjus the admin panel li height
		 */
		 $('.right_details_tab').bind('DOMNodeInserted DOMNodeRemoved', function(event) {
			if (event.type == 'DOMNodeInserted') {
				$('ul.details').each(function(){
					var max_height = 0;
					$('li', $(this)).each(function(){
						if(max_height < $(this).height()) max_height = $(this).height();							
					});
					$('li', $(this)).each(function(){
						$(this).css('height', max_height+'px');
					});
				})
			} else {
				
			}
		 });
	});
	
})(jQuery)