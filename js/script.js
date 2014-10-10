(function($){
	
	
	/**
	 * The doms are ready
	 */
	$(function(){
		
		/**The sidebar slider*/

		$(".slider2").tinycarousel({
			axis   : "y",
			animationTime: 10,
			animation: true
		});

		/**
		 * Toggle between register and login
		 */
		$(document).on('click', '.toggle', function(){
			var toggleClass = $(this).attr('class').split(' ')[0];
			$('div.details_back:visible').hide();
			$('div.'+toggleClass).length;
			$('div.'+toggleClass).show();
			
		});
		
		/**
		 * Handle ajax submission of registration form
		 */
		 $('form.regform').ajaxForm({
			url : 'portal/do_register/',
			'type': 'post',
			beforeSubmit:function(arr, frm){
				frm.overlay(1);
				frm.overlay('Please wait');
			},
			success:function(r, status, xhr, frm){
				var response_arr = r.split('~!~');
				$('.reg_success').html(response_arr[0]).css({'display': 'block'});
				if(response_arr[1] == 1){
					frm.resetForm();
				}
				frm.overlay(0);
			},
			error:function(){
				frm.overlay('Some error occured, please try again');
				frm.overlay(0);
			}
		 })
		 .on('focus',function(){
				$('.reg_success').css('visibility', 'hidden');
		 } );
		 
		 /**
		 * Handle ajax submission of login form
		 */
		 $('form.login_form').ajaxForm({
			url : 'portal/do_login/',
			'type': 'post',
			'dataType': 'json',
			beforeSubmit:function(arr, frm){
				frm.overlay(1);
				frm.overlay('Please wait');
			},
			success:function(r, status, xhr, frm){
				if( ! $.isEmptyObject(r.error)){
					frm.overlay("Login failed");
					frm.overlay(0, -1);
				}else{
					window.location.href= r.response;
				}
			},
			error:function(obj,errorName,errorDetails, frm){
				frm.overlay('Some error occured, please try again');
				frm.overlay(0,-1);
			},			
		 })
		 .on('focus',function(){
				$('.reg_success').css('visibility', 'hidden');
		 } );
		 
		 /**
		  * Upload profile pic
		  */
		 
		  $('#prof_pic_upload,reupdate_left').html5Uploader({
				name: 'foo',
				
				postUrl : base_path+'portal/uploadprofilepic',
				
				onClientLoad: function(){
					$('.profile_back').overlay(1);
					$('.profile_back').overlay("Please wait while we uploading");
				},
				onClientError: function(){
							$('.profile_back').overlay("Browser fails to read the file");
							$('.profile_back').overlay(0,-1);
						},
						onServerError:function(){
							$('.profile_back').overlay("File upload fails,please try again");
							$('.profile_back').overlay(0,-1);
						},
				onServerProgress :function(e){},
				onSuccess:function(e, file, response){														
					$('.profile_back').overlay("Upload complete");
					var res = JSON.parse(response);
					if(res.error.length == 0){
						$('#prof_pic_img').attr('src',res.data);
					}else{
						$('.profile_back').overlay(res.error[0]);
						 $('.profile_back').val('');
					}
					$('.profile_back').overlay(0, -1);
				}
				
			 });
			 
			 /**
			  * Select project
			  */
			 $(document).on('click', 'div.project_tile', function(){
				var dom = $('.details_back');
			 	var pid = $(this).attr('id').replace('project-', '');
				$.ajax({
					url : base_path + 'portal/selcetProject/'+pid,
					beforeSend:function(){
						
						dom.overlay("Please wait");
					},
					success:function(r){
						if(r !== '-1'){
							window.location.href = r;
						}else{
							dom.overlay("Some error ocured, please try after some time");
						}
					},
					error:function(){},
					complete:function(){
						dom.overlay(0, -1);
					}
				});
			 });
	/**
	 Scroll on hover	 
	 */		 
	 		var timeOut = null;
		 $('.arrow1').hover( function(){
			 timeOut = setInterval(function(){
			 	$('a.next').click();
			 }, 1000);
			},function(){
				clearInterval( timeOut );
			 }
		 );
		 $('.arrow2').hover( function(){
			 timeOut = setInterval(function(){
			 	$('a.prev').click();
			 }, 1000);
			},function(){
				clearInterval( timeOut );
			 }
		 );
		 
		 /**
		  * The projct context set
		  */
		  $(document).on('click', '.set_project', function(){
			  var pid = $(this).attr('id').replace('pid-','');
				$.ajax({
				 url : base_path+'portal/selcetProject/'+pid,
				 async:false,
				 beforeSend: function(){
				 	
				 },
				 success: function(){
					
				 }
				});
		  	//return false;
		  });
		/**
		 * it is used to 
		 */

		$.getJSON(base_path+'portal/fetch_note/', function(data){
			$('#note').val(data.body);
		});
		$(document).on('change', '#note', function(){
			$.post(base_path+'portal/save_note/',{'note_body':$(this).val()});
		});

	});
	
})(jQuery)