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

	  $(document).on('change', '#selected-project-id', function(){

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

		$(document).on('change', '#selected-project-id', function(){
			var dom = $('.right_details_tab');

			$.ajax({
				url: base_path + 'admin/getNewUserDetails/1?project_id=' + $(this).val(),
				beforeSend:function(){
					dom.overlay(1);
					dom.overlay("Please wait");
				},
				success:function(r, status, jqXHR){
					dom.overlay("prcessing complete");
					dom.html(r);
				},
				error:function( jqXHR,  textStatus, errorThrown){
					dom.overlay("An error occured, please try after sometime");
				},
				complete:function(jqXHR, textStatus){
					dom.overlay(0,-1);
					$.fn.bindValidation();
				}
			});

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
            
			setTimeout(function(){
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
			 }
			},1000);
			 
		 });
		 /**
		  * Message history 
		  */
		  //$(document).on('change', '#user1,#user2', function(){
		  $(document).on('click', '#view', function(){
		  	var user1 = $('#user1').val();
			var user2 = $('#user2').val();
			var dom = $('ul.right_face');
			dom.overlay(1);
			if(user1 && user2){
				if(user1 == user2){
					dom.overlay("Please select two different user");
				}else{
					$.ajax({
						url : base_path+'admin/invoke?a=Messaging_app&f=getoldHistory',
						type: 'post',
						data:{uid:user1, ouid:user2,last_id:0 },
						beforeSend: function(){
							dom.overlay(0);
							dom.overlay(1);
							dom.overlay("Please wait");
						},
						success:function(r){
							if(r){
								dom.html(r);
								$('.mesage_history_all')[0].scrollTop=0;
								console.info($('.mesage_history_all').scrollTop(0));
								addScrollbar();
							}else{
								dom.overlay("No message found");
								dom.html('<li>No message found</li>')
							}
						},
						error: function(){
							dom.overlay("An error occured, please try after some time");
						},
						complete:function(){
							dom.overlay(0,-1);
						}
					})
				}
			
			}else{
				dom.overlay("Select two user");
			
			}
			dom.overlay(0,-1);
		  });
		  /**
		   * PLace the scroll for all
		   */
		  addScrollbar = function(){			
			 $('.mesage_history_all').on ('scroll',   function(){			 
				
					if( (parseInt($('.mesage_history_all').scrollTop()) + parseInt($('.mesage_history_all').height())) == $('.mesage_history_all')[0].scrollHeight){
					loadConetnDynamically();	
					}
			 });
		  };
		  
		  loadConetnDynamically = function(){
							$.ajax({
									url: base_path+'admin/invoke?a=Messaging_app&f=getoldHistory',
									data:{last_id:$('ul li[data-messageid]:last').data('messageid'), uid:$('#user1').val(), ouid: $('#user2').val()},
									type : 'post',
									beforeSend: function(){},
									success: function(r){
										if(r){
											$("#content_4 ul.right_face").append(r);
											$("#content_4").mCustomScrollbar('update');
										}
									}
								})
					}
	
	/**
	 * The delete funciton compatability
	 * it detects user want to delete which type 
	 * And present that form and ask to delete
	 * we will handle the actual delete on somewhere else
	 */
	 $(document).on('click', '[data-delete_type]', function(){
		var $this = $(this);
		
		$('[data-delete_type]').removeData('selected');$this.data('selected', 'selected');
	 	var dom = $('.tab_content_detila_back');
		$.ajax({
			url : base_path+'admin/prepareDeleteFrom',
			data: {type: $this.data('delete_type')},
			type: 'post',
			beforeSend: function(){
				dom.overlay(1);
				dom.overlay("Please wait");
			},
			success: function(r){				
				$('.output').html(r);
			},
			complete: function(){
				dom.overlay(0,-1);
			},
			error: function(){}
		});
		return false;
	 });
	 
	 /**
	  * Handle the delte of
	  * various entities
	  */
	  $(document).on('submit', '#deleteEntity', function(e){
		var dom = $(this);
		if(!confirm("Are you sure you want to delete?"))
			return false;
		$.ajax({
			url: base_path+'admin/performDelete',
			data: dom.serialize(),
			type : 'post',
			beforeSend: function(){				
				dom.overlay("Please wait");
			},
			success: function(r){
				if(r==1){
					dom.overlay("Deleted successfully");
					$('[data-delete_type]').filter(function(){
						if($(this).data('selected'))
							return true;
						return false;
					}).click();
				}else
					dom.overlay("Data can't be deleted");				
			},
			complete: function(){
				dom.overlay(0,-1);
			},
			error: function(){
				dom.overlay("Internal server error, please try after sometime");
			}
			
		});
	  	return false;
	  });
	 
	 
	 // + link for issue edit
	 $(document).on('click','.issue_edit_link', function(){
		var t = $(this);
		var dom = $(this);
		var issue_id = t.attr('id').replace('issue_edit-','');
		$.ajax({
			url : base_path+'admin/invoke?a=issueviewer_app&f=displayEditForm&id='+issue_id,
			beforeSend:function(){
				dom.overlay(1);
				dom.overlay("Please wait");
			},
			success:function(r){
				$('.tab_content_detila_back').html(r);
			},
			error:function(){},
			complete:function(){}
		});
	 });
	 
	  // + submit the create issue form
		 $(document).on('submit', '#create_new_issue', function(){
			 var t = $(this);
			 var dom = $(this);
			 $.ajax({
			 	url : base_path+'admin/invoke?a=issueviewer_app&f=createIssue',
				data : t.serialize(),
				type: 'post',
				beforeSend: function(){
					dom.overlay(1);
					dom.overlay("Please wait");
				},
				success: function(r){
					if(r==1){
						$('li.posrel.active').click();
					}
				},
				error: function(){},
				complete: function(){
					dom.overlay(0, -1);
				}
			 });
		 	return false;
		 });

		// + document update issue
		$(document).on('submit', '#edit_issue', function(){
							var t = $(this);
							 var dom = $(this);
							 $.ajax({
							 	url : base_path+'admin/invoke?a=issueviewer_app&f=updateIssue',
								data : t.serialize(),
								type: 'post',
								beforeSend: function(){
									dom.overlay(1);
									dom.overlay("Please wait");
								},
								success: function(r){
									if(r==1){
										$('li.posrel.active').click();
									}
								},
								error: function(){},
								complete: function(){
									dom.overlay(0, -1);
								}
							 });
						 	return false;
		});
/*
		$(document).on('load', 'table.dataTable th[data-default-sort]', function(e){
			console.log($(this).attr('data-default-sort'), $(this).hasClass('sorting_desc'));
			if($(this).attr('data-default-sort') == 'desc' && !$(this).hasClass('sorting_desc'))
				$(this).trigger('click');
		});*/
	 
	});
	

})(jQuery)
// + issue upload
function bindHtmlUploaderIssue(){
		$('#file_issue').html5Uploader({
						name: 'foo',
						
						postUrl : base_path+'admin/invoke?a=issueviewer_app&f=upload&old_file=',
						
						onClientLoad: function(){
							$('.universal_form_back').overlay(1);
							$('.universal_form_back').overlay("Knock Knock...");
						},
						onClientError: function(){
									$('.universal_form_back').overlay("Browser fails to read the file");
									$('.universal_form_back').overlay(0,-1);
								},
								onServerError:function(){
									$('.universal_form_back').overlay("File upload fails,please try again");
									$('.universal_form_back').overlay(0,-1);
								},
						onServerProgress :function(e){},
						onSuccess:function(e, file, response){														
							$('.universal_form_back').overlay("Upload complete");
							var res = JSON.parse(response);
							if(res.error.length == 0){
								var res_details = res.data.split('~!~');
								$('#path').val(res_details[0]);
								$('#original_filename').val(res_details[1]);
							}else{
								$('.universal_form_back').overlay(res.error[0]);
								 $('#file_issue').val('');
							}
							$('.universal_form_back').overlay(0, -1);
						},
						dynamicUrl: function(){
							return base_path+'admin/invoke?a=issueviewer_app&f=upload&old_file='+$('[name=path]').val();
						}
						
					 }).addClass('alreadybinded');
	 }
	 