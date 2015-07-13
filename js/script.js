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
				frm.overlay('An error occured, please try again');
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
				frm.overlay('An error occured, please try again');
				frm.overlay(0,-1);
			},			
		 })
		 .on('focus',function(){
				$('.reg_success').css('visibility', 'hidden');
		 } );
		 
		 /**
		  * Forgot password		 
		  */
		  $('form.forgotpass_form').ajaxForm({
			url : 'portal/forgotpass/',
			'type': 'post',
			'dataType': 'json',
			beforeSubmit:function(arr, frm){
				frm.overlay(1);
				frm.overlay('Please wait');
			},
			success:function(r, status, xhr, frm){
				if( ! $.isEmptyObject(r.error)){
					frm.overlay(r.error[0]);
					frm.overlay(0, -1);
					$('.forgotpass_error').html(r.error[0]).css('display', 'block');
				}else{
					$('.forgotpass_error').html(r.data).css('display', 'block');
					frm.overlay(0, -1);
				}
			},
			error:function(obj,errorName,errorDetails, frm){
				frm.overlay('An error occured, please try again');
				frm.overlay(0,-1);
			},			
		 }).on('focus', function(){ $('.forgotpass_error').css('visibility', 'hidden')});
		
		  $('form.changepass_form').ajaxForm({
			url : base_path+'portal/dochange/',
			'type': 'post',
			'dataType': 'json',
			beforeSubmit:function(arr, frm){
				frm.overlay(1);
				frm.overlay('Please wait');
			},
			success:function(r, status, xhr, frm){
				if( ! $.isEmptyObject(r.error)){
					frm.overlay(r.error[0]);
					frm.overlay(0, -1);
					$('.log_error').html(r.error[0]).css('visibility', 'block');
				}else{
					$('.log_error').html("DONE! Please wait while we redirecting").css('visibility', 'visible');
					frm.overlay(0, -1);
					setTimeout(function(){
						window.location.href = r.data;
					});
				}
			},
			error:function(obj,errorName,errorDetails, frm){
				frm.overlay('An error occured, please try again');
				frm.overlay(0,-1);
			},			
		 });
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
					url : base_path + 'portal/selectProject/'+pid,
					beforeSend:function(){
						
						dom.overlay("Please wait");
					},
					success:function(r){
						if(r !== '-1'){
							window.location.href = r;
						}else{
							dom.overlay("An error occured, please try after some time");
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
				 url : base_path+'portal/selectProject/'+pid,
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

		/**
		 Process Map	 
		 */	

		 var embedIdList = [
		 	'9cb81feb-136d-4880-8dff-61ef0690cbc1', // GRIP 1
		 	'ae9b000d-33ac-4a6e-aeae-25d08f79cae7', // GRIP 2
		 	'f53d2769-f8a9-4b14-860c-5ef9a9286aa7', // GRIP 3
		 	'd0e0d011-2255-4e23-bda1-dc8c3fe8a399', // GRIP 4
		 	'5396c498-96b6-4f1c-865a-c7a96b548c47', // GRIP 5
		 	'7b64da8a-543a-49be-8a1c-bf70aedd5c33', // GRIP 6
		 	'1853e375-0134-4942-b82f-75537c0357b4', // GRIP 7
		 	'923bfd02-b7f4-4823-bfb1-6ac88a034f1d', // GRIP 8
		 	'64f449ef-b561-4255-90b7-9f09c89ffdcf', // VFL Tender
		 	'27d3e04f-27a9-4675-8144-c07df596383c', // VFL Tender Docs
		 	'a3c66785-13eb-41e4-8518-99296776ea16', // VFL Contract Award
		 	'0338cb65-a626-4fd2-85ee-d7a0945fde2d', // Design Tender / Contract Award
		 	'cd3f58ab-54a6-4c30-b939-1f5a573093df' // VFL Sub-Contract Placement
		 ];

		 $(document).on('click', '#pm-timeline a', function() {

		 	$('#pm-timeline a.active').removeClass('active');
		 	$(this).addClass('active');

		 	if($(window).width() > 1240) {
		 		var frameHeight = $('#content_2').height() - $('#pm-timeline').height() - 260;
		 	} else {
		 		var frameHeight = 600;
		 	}

		 	var embedId = embedIdList[$(this).data('grip') - 1];

		 	if($('#pro-map #chart-frame iframe').length) $('#pro-map #chart-frame iframe').remove();
		 	$('#pro-map #chart-frame').append('<iframe allowfullscreen frameborder="0" style="width:100%; height: '+frameHeight+'px;" src="https://www.lucidchart.com/documents/embeddedchart/'+embedId+'" id="X-eUPgYC8fSL"></iframe>');
		 	$('#pro-map #chart-frame').animate({'height': frameHeight}, 200);

		 });

		 $(document).on('click', '#pro-map .close-frame', function() {

		 	$('#pm-timeline a.active').removeClass('active');

		 	$('#pro-map #chart-frame').animate({'height': 0}, 200);

		 });

		 $(document).on('click', '#pro-map #key h3', function() {

		 	if($('#pro-map #key').hasClass('show')) {
		 		$('#pro-map #key').removeClass('show');
		 		$('#pro-map #key').animate({'height': 30}, 200);
		 	} else {
		 		$('#pro-map #key').addClass('show');
		 		$('#pro-map #key').animate({'height': $('#pro-map #key ul').outerHeight()}, 200);
		 	}

		 });

		 if($('#pm-timeline').length) {
		 	var extraWidth = 0;
		 	$('#pm-timeline #extra .other').each(function() {
		 		extraWidth = extraWidth + $(this).outerWidth(true);
		 	});
		 	$('#pm-timeline #extra div').css('width', extraWidth + 20);
		 }

	});
	
})(jQuery)