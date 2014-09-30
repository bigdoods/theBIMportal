// JavaScript Document
/**
 * The url is specifi to the bim project if needed 
 * It can be edited here
 *
 */
 var app_url = base_path + main_container+ '/invoke?a=messaging_app';
  var chat_app_obj = null;
 (function($){
 	$.ajax({
		url : app_url+'&f=createContainer' ,
		success: function(r){
			if(r !== '-1' )
				r= $(r);
				$('body').append(r);
				
				$.fn.prepareChatContext();
		}
	})

	 $.fn.prepareChatContext = function(){
		var user_id = $('.ch_main_c').data('curent_user');		
		/**
		 * Creating the main chat app
		 */
		chat_app_obj = new chatApp($('.ch_main_c').data('curent_user'), $('.ch_main_c').data('project_context'), 'ch_user_list');
		chat_app_obj.restoreStatus();
		/**
		  * Save a the chatapp status
		  */
		  setInterval(function(){
			chat_app_obj.save();
		  }, 2000);
		
		/**
		 * Update user online offline status
		 */
		 setInterval(function(){
			chat_app_obj.update();
		  }, 5000*1);
		
		/* restore the chat box to the previous state*/
		$.each(chat_app_obj.chatBoxArr, function(){
			if(this instanceof chatBox){				
				this.restore();
				this.prepare();
			}
		});
	 	$('#ch_main_wrapper').show();
	 }
	 
 })(jQuery)
 
 /**
  * Main chat container class
  */
 chatApp = function( user_id, project_context){
	var self = this;
 	this.dom = $('.chat_wrapper');
	this.rest_dom = this.dom.find('.chat_details_back');
	this.u_id = user_id;
	this.p_context = project_context;
	this.chatBoxArr = new Array();
	this.activeChatBoxObjArr = new Array();
	this.hiddenChatBoxObjArr = new Array();
	this.chatbox_wrapper = this.dom.find('.ch_chat_box_container');
	this.ss_active = 'chat_app-active'+this.u_id;
	this.ss_hidden = 'chat_app-hidden'+this.u_id;
	
	/**
	 defien various chat componetn
	 */
	this.trigger_dom = this.dom.find('.trigger');
	this.user_list_dom = this.dom.find('.ch_user_list');
	/**
	 * get the open status of the chat app
	 */
	this.openStatus = sessionStorage.getItem('chat_app_open_status') === '1' ? 0 : 1 ;	
	/**
	 * Bind click event on trigger
	 */
	 
	this.trigger_dom.on('click',self,this.triggerClick)<!---->
	/**
	 * Creating the chat box app
	 */
	this.user_list_dom.find('.ch_user').each(function(i){
		self.chatBoxArr[$(this).data('user_id')] = new chatBox(self.u_id, $(this).data('user_id'));
	});
	/**
	 * Open a chat box
	 */			
	 $('.ch_user').on('click',function(){
	 	var user_id  = $(this).data('user_id');
		self.chatBoxArr[user_id].maximize();
	 });
	 
	 
 }
/**
 * UPdate himself
 */
	chatApp.prototype.update = function(){
		var obj = this;
		$.ajax({
			url : app_url+'&f=updateUserStatus',
			data :{me: obj.u_id},
			type : 'post',
			dataType: 'json',
			success: function(r){
				
				if( !$.isEmptyObject(r)){
					$.each(r, function(i,j){
						var status = j.activity_status;
						var class_should = '';
						if(status == 0){
							class_should = 'red';
						}else if(status == 1){
							class_should = 'green';
						}else{
							class_should = 'yellow';
						}
					/**
					 * Make the status change of the url
					 */
					
					 chat_app_obj.rest_dom.find('.ch_user_id-'+i+' .ch_user_status').removeClass('red green yellow').addClass(class_should);
					 $.each(chat_app_obj.chatBoxArr, function(k,l){						
					 	if(l instanceof chatBox && l.receiver == i){
							l.online = status;
							
							l.synchronize();
						}
					 });
					});
				}
			}
		})
	}
/**
 * 
 */
chatApp.prototype.save = function(){
	sessionStorage.setItem(this.ss_active, JSON.stringify(this.activeChatBoxObjArr));
	sessionStorage.setItem(this.ss_hidden, JSON.stringify(this.hiddenChatBoxObjArr));
}	
 /**
  * handle the click operation on chat head
  */	
 chatApp.prototype.triggerClick = function(e){
	var obj = e.data;
	obj.openStatus = obj.openStatus ==  1 ? 0 : 1 ;
	sessionStorage.setItem('chat_app_open_status', obj.openStatus);
	obj.rest_dom.slideToggle('slow');
	obj.dom.find('.ch_total_inrd_cnt').text('');
 }
 
 /**
  * Restore the chat operation
  */
  chatApp.prototype.restoreStatus = function(status){
 	if(this.openStatus == 1){		
		this.rest_dom.slideUp('slow');
	}else{
		this.rest_dom.slideDown('fast');
	}
	this.dom.find('.trigger').click();
	
	if(sessionStorage.getItem(this.ss_active)){
		var temp1 = new Array();
		var index_arr = new Array();
		this.activeChatBoxObjArr = JSON.parse(sessionStorage.getItem(this.ss_active));
		
		$.each(this.activeChatBoxObjArr, function(i, j){
			if($.inArray(j[0], index_arr)){
				return;
			}else{
				temp1.push(j);
				index_arr.push(j[0]);
			}
		});
		
		this.activeChatBoxObjArr = temp1;
		this.hiddenChatBoxObjArr =JSON.parse(sessionStorage.getItem(this.ss_hidden));		
		var temp2 = new Array();
		var index_arr = new Array();
		$.each(this.hiddenChatBoxObjArr, function(i, j){
			if($.inArray(j[0], index_arr) != -1){
				return;
			}else{
				temp2.push(j);
				index_arr.push(j[0]);
			}
		});
		this.hiddenChatBoxObjArr = temp2;
		}
	
 }
 
 
 
 /**
  * Individual chatbox app
  */
 chatBox = function(sender, receiver){
	/**
	 0 = totaly invisible
	 1 = visiblem
	 2 = minimize
	 3 = outside viewport
	 4 = outsideviewport but minimze
	 */
	this.status_arr = [0,1,2,3];
 	this.sender = sender; // sender current logged id
	this.receiver = receiver;	 // to id
	
	this.ss_content = 'chat_conent-'+sender+'_'+receiver;
	this.content = sessionStorage.getItem(this.ss_content);
	
	this.ss_status =  'chat_status-'+sender+'_'+receiver;
	this.status =  sessionStorage.getItem(this.ss_status);	
	
	this.dom = $('#ch_box-'+receiver);	
	this.textarea = this.dom.find('.ch_send_content');
	
	this.ss_last_loaded = 'last_loaded-'+sender+'_'+receiver;
	this.last_loaded = 0;
	this.ss_no_more_message = 'no_more_message-'+sender+'_'+receiver;
	this.no_more_message = 0;
	//this.status = 0;
	this.new_msg_count = 0;
	this.loading_old = false;
	this.parent_rel_dom = $('.ch_user_id-'+receiver);// in the user list dom it is related to whom
	
	/**
	 * Online status
	 */
	this.online =  this.dom.data('online_status');
	//this.prepare();
	
	
 } 
 
 chatBox.prototype.prepare = function(e){ 	
	/**
	 Prepare for minimize/maximize
	 */
	var self = this;
	this.dom.find('.ch_header').on('click', this, this.toggle);	
	 
	/**
	 * Prepare for close
	 */
	this.dom.find('.cross_fb').on('click', this, this.close) 
	/**
	 *send content
	 */
	this.dom.find('.ch_send_content').on('keyup', this, this.send);
	
	/**
	 * Load old message
	*/
	this.dom.find('.ch_details_inner').on( 'scroll', this, this.loadOld);
	
	 //self.searchNew();
	 var search_interval = setInterval(function(){
		self.searchNew();
     },1000*3); 

	setInterval(function(){
		self.save();
	},1000*2);
	
 }
 /**
  * Restore the previous standard
  */
 chatBox.prototype.restore = function(){
	var obj = this;
	/**
	 * restore the content
	 */
	obj.last_loaded = sessionStorage.getItem(obj.ss_last_loaded);
	obj.last_loaded = obj.last_loaded ? obj.last_loaded : 0;
	obj.content = sessionStorage.getItem(obj.ss_content);
	obj.dom.find('.ch_content_whole').html(obj.content);
	/**
	 * restore no more content
	 */
	 
	 obj.no_more_message = sessionStorage.getItem(obj.ss_no_more_message);
	 obj.no_more_message = obj.no_more_message ?  obj.no_more_message : 0;
	
	/**
	 * restore the status
	 */	
	obj.status = sessionStorage.getItem(obj.ss_status) ? sessionStorage.getItem(obj.ss_status) : 0;
	obj.status = parseInt(obj.status);
	/**
	 * make the hidde and active arr content with the correct one
	 */
	 $.each(chat_app_obj.activeChatBoxObjArr, function(i,j){
	 	if(j[0] == obj.receiver){
			j[1] = obj;
		}
	 });
	 
	 $.each(chat_app_obj.hiddenChatBoxObjArr, function(i,j){
	 	if(j[0] == obj.receiver){
			j[1] = obj;
		}
	 })
	 
 	switch( obj.status ){
		case 1:
			// do maximize
			obj.status = 0
			obj.maximize();
			break;
		case 2:
			obj.maximize();
			var e = {data:this}; // custom event
			obj.toggle(e);
			break;
		case 3:
			// make hidden
			break;
	}
	
 }
 /**
  * Save status
  */
 chatBox.prototype.save = function(e){
	 //return;
	 var obj = null;
	if(this instanceof chatBox) {
		obj = this;
	}else{
		obj = e.data;
	}
	
	sessionStorage.setItem(obj.ss_status, obj.status);
	sessionStorage.setItem(obj.ss_content, obj.dom.find('.ch_content_whole').html());
	sessionStorage.setItem(obj.ss_last_loaded, obj.last_loaded);
	sessionStorage.setItem(obj.ss_no_more_message, obj.no_more_message);
	
	//;
	//;
 }
 /**
  * load old message on scroll top 0
  */
 chatBox.prototype.loadOld = function(e){
 	var obj = e.data;
	//var height = obj.dom.find('.ch_details_inner').height();
	//;
	if( obj.dom.find('.ch_details_inner').scrollTop() == 0){
		obj.loadMessage();
	}else{
		//;
	}
 } 
 
 /**
  * Listen for new message
  */
 chatBox.prototype.searchNew = function(){	
	var obj = this;
	
	/**
	 * If the user is offline then there is no meaning to
	 * Search message from him
	 */
	if(obj.online == 0)
		return;
	if(obj.loading_old)
		return;
	$.ajax({
		url : app_url+'&f=newMessage',
		type : 'post',
		data : {receiver: this.sender, cb_status: this.status, sender: this.receiver},// this.sender = currentuser and this.receiver = remoteuser
		success: function(r){
			var response = r.split('~!^!~');
			obj.new_msg_count = parseInt(obj.new_msg_count)+ parseInt(response[1]);
			if( parseInt(response[1]) === 0){
				//obj.new_msg_count = 0;
			}
			/**
			 * append the new message
			 */
			 if(response[0] != ''){
			 	obj.dom.find('.ch_content_whole').append(response[0]);
				obj.moveScrollerDown();
			 }
			/**
			 * if chat box is minimized or hidden minimized then add notification
			 * to the header
			 */
			 if(parseInt(response[1]) > 0 && obj.status == 2) {
			 	obj.dom.find('.ch_new_msg_count').text(obj.new_msg_count).css({'padding': '2px', 'display': 'block'});
			 }
			 
			 /**
			 * if chat box is outsideview port then add notification to the
			 * parent_dom
			 */
			 if(parseInt(response[1]) > 0 && (obj.status == 0 || obj.status == 3)) {
			 	obj.parent_rel_dom.find('.ch_inread_cnt').text('('+obj.new_msg_count + ')').css({'padding': '2px'});
			 }
			 
			 /**
			  * if new 
			  */
			  if(parseInt(response[1]) > 0) {
			 	chat_app_obj.dom.find('.ch_total_inrd_cnt').text('('+obj.new_msg_count + ')').css({'padding': '2px'});
			 	}
		}
		
	})
 } 
 /**
  * Load old message
  */
 chatBox.prototype.loadMessage = function(){
	// return;//test;	 
	 var obj = this;
	 if(obj.status == 0)// we dont load messages chat box are not visible
	 	return;
	 if (obj.status != 0 && obj.no_more_message == 1 )
	 	return; 	
 	$.ajax({
		url : app_url + '&f=load',
		type: 'post',
		data:{sender:this.sender, receiver:this.receiver, last_id : this.last_loaded},
		beforeSend: function(){
			obj.loading_old = true;
		},
		success: function(r){
			obj.loading_old = false;
			var response_arr = r.split('~!^!~');
			obj.last_loaded = response_arr[1];
			obj.no_more_message = response_arr[2];
			var html = response_arr[0];
			var $html = $(html).fadeOut();
			if(html != '')
			obj.dom.find('.ch_content_whole').prepend($html);
			$html.fadeIn('slow');
		}
	})
 }
 
 /**
  * The minimize function
  */
 chatBox.prototype.toggle = function(e){
	//e.stopImmediatePropagation();	
	var obj = e.data;
	if(obj.dom.find('.ch_header').hasClass('status_1')){// minimize
		obj.dom.find('.ch_header.status_1').removeClass('status_1').addClass('status_2');
  		obj.dom.find('.chat_details_back2').slideUp();
		obj.status = 2;
		// + remove notification		
	}else{// maximize
		obj.dom.find('.ch_header.status_2').removeClass('status_2').addClass('status_1');
  		obj.dom.find('.chat_details_back2').slideDown();
		obj.status = 1;
		obj.dom.find('.ch_new_msg_count').fadeOut().text('');
	}
	
	sessionStorage.setItem(obj.ss_status, obj.status);
	return false;
  }
  
	/**
	* Close functionality
	*/
	chatBox.prototype.close = function(e){
		e.stopImmediatePropagation();
		var obj = e.data
		
		obj.dom.css('display', 'none');
		obj.status = 0;
		sessionStorage.setItem(obj.ss_status, obj.status);
		
		//+ remove from active array		
		$.each(chat_app_obj.activeChatBoxObjArr, function(i, j){						
			if(j[0] == obj.receiver){
				chat_app_obj.activeChatBoxObjArr.splice(i,1);
				return false;
			}
		});

		/**
		 * pick one from hiden array
		 */
		 if(chat_app_obj.hiddenChatBoxObjArr.length > 0){
		 	var newone = chat_app_obj.hiddenChatBoxObjArr.shift();			
			 if(newone.length > 0){// insert him into active
				 chat_app_obj.activeChatBoxObjArr.push([newone[1].receiver, newone[1]]);
			 }	
		 }
		 /**
		  * Makwe the position of all of them correct
		  */
		  var total_count = chat_app_obj.activeChatBoxObjArr.length;
		  $.each(chat_app_obj.activeChatBoxObjArr, function(i, j){
		  	var c = i+1;
			var obj = j[1];
			obj.dom.css('right', (309* c )+ 'px');
			
		  })
		 
		return false;
	}
	
	/**
	* Open chat box and maximize
	*/
	
	chatBox.prototype.maximize = function(e){
		// if it is already open then return
		if(this.status == 1){
			//return;
		}
		//this.dom.css({right : '309px' });
		this.dom.find('.ch_header').addClass('status_1');	 
		this.dom.css('display', 'inherit');
		this.dom.find('.chat_details_back2').slideDown();				
		this.parent_rel_dom.find('.ch_inread_cnt').text('');
		this.moveScrollerDown();
		this.new_msg_count = 0;
		/**
		 if the object was in hidden state then remove that from there
		 */
		 var self =this;
		if(this.status == 1){
			return; // when any dom wants to be restore then just restore the css value,other things are ok
		}
		this.status = 1;
		this.loadMessage();
		// + remove from hidden app
		$.each(chat_app_obj.hiddenChatBoxObjArr, function(i, j){
			if(j){
				if(j[0] == self.receiver){
					chat_app_obj.hiddenChatBoxObjArr.splice(i,1);
					//return false;
				}
			}
			
		})
		/**
		 * Remove from active status if exists
		 */		 
		 $.each(chat_app_obj.activeChatBoxObjArr, function(i, j){
			if(j[0] instanceof chatBox) {
				if(j[0] == self.receiver){
					chat_app_obj.activeChatBoxObjArr.splice(i,1);
					//return false;
				}
			}
		})
		
		/**
		 * max view element is reached then hide one
		 */
		 if(this instanceof chatBox){			 
			chat_app_obj.activeChatBoxObjArr.unshift([this.receiver, this]);
			;
			;
		 }

		 if(chat_app_obj.activeChatBoxObjArr.length >= 4){// the view port is full need to hide one		 	
			//$.each(chat_app_obj.activeChatBoxObjArr, function(i,j){
				var shouldHide = chat_app_obj.activeChatBoxObjArr.pop();
				if(shouldHide[1] instanceof chatBox){
					
					;
					;
					shouldHide[1].hide();
					//return false;
				}
				
			//})
		 }
		 /** make all active chat box right perfect*/
		 $.each(chat_app_obj.activeChatBoxObjArr, function(i,j){			 
			var present_right = j[1].dom.css('right');
			present_right = parseInt(present_right);
			
			j[1].dom.css('right', present_right+305+'px');
		 });
		 // insert this new one at first		 
		 this.dom.css({right : '309px' });
		 
		 
	}
	
	/**
	 * Make chat box hidden
	 */
	chatBox.prototype.hide = function(){
		
		var self = this;
		chat_app_obj.hiddenChatBoxObjArr.push([this.receiver, this]);
		this.dom.css({'right': '-9999px'});
		if(this.status == 2){
			this.status = 4;
		}else if(this.status == 1){
			this.status = 3;
		}
		/**
		 * remove him from active
		 */
		 ;
		  ;
		 $.each(chat_app_obj.activeChatBoxObjArr, function(i, j){
			if(!j) 
				return false;
		 	if(j[0] == self.receiver){
				chat_app_obj.activeChatBoxObjArr.splice(i,1);
				return false;
			}
		 })
	}
	
	/**
	* Send message
	*/
	chatBox.prototype.send = function(e){	
	var obj = null;
	if(this instanceof chatBox){
		obj = this;
	}else{
		obj = e.data;
	}
	/**
	 * If it is not chift key then send the data
	 */
	//;
	 if(e.keyCode === 13 && e.shiftKey !== true){
		$.ajax({
			url : app_url+'&f=send',
			type : 'post',
			data : { message: obj.textarea.val(), sender:obj.sender, receiver: obj.receiver},
			beforeSend: function(){
				var text = obj.textarea.val();
				obj.textarea.val('');
				this.mid = Math.random();
				var html = '<div class="portion_details message_content" id="message_'+this.mid+'">\
							<div class="own">\
								<h2><span></span>'+text+'</h2>\
							</div>\
						   </div>';
				obj.dom.find('.ch_content_whole').append(html);
				obj.moveScrollerDown();
			},
			success:function(){
				$('.message_content').css({opacity:1});
				$('#message_'+this.mid).css({opacity:1});// it should work but not working
			}
		})
	 }
	}
	
	/**
	* move the scroler
	*/
	chatBox.prototype.moveScrollerDown = function(){
	var obj = null;
	if(this instanceof chatBox){
		obj = this;
	}else{
		obj = e.data;
	}
	
	var percentageToScroll = 100;
	var height = obj.dom.find('.ch_details_inner')[0].scrollHeight;
	var scrollAmount = height;
	 obj.dom.find('.ch_details_inner').animate({scrollTop: scrollAmount}, 900);
	}
	
	/**
	 * synchronize offline online status
	 */
	 chatBox.prototype.synchronize = function(){
	 	var obj = this;
		var class_should = '';
		if(obj.online == 1){
			class_should = 'green';
		}else if(obj.online == 0){
			class_should = 'red';
		}else{
			class_should = 'yellow';
		}
		
		obj.dom.find('.ch_box_stat').removeClass('red green yellow').addClass(class_should);
	 }
  
			 var headID = document.getElementsByTagName("head")[0];         
				var newScript = document.createElement('script');
				newScript.type = 'text/javascript';
				newScript.src = base_path+'js/chat_sub.js';
				headID.appendChild(newScript);
  
  