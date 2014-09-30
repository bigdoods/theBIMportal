    // ++++++++============================================            Group chat =====================++++++++++++++++++++++
  ChatBoxCg = function(sender, receiver){
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
	
	this.ss_content = 'cg_conent-'+sender+'_'+receiver;
	this.content = sessionStorage.getItem(this.ss_content);
	
	this.ss_status =  'cg_status-'+sender+'_'+receiver;
	this.status =  sessionStorage.getItem(this.ss_status);	
	
	this.dom = $('#cg_box-'+receiver);	
	this.textarea = this.dom.find('.cg_send_content');
	
	this.ss_last_loaded = 'cg_last_loaded-'+sender+'_'+receiver;
	this.last_loaded = 0;
	this.ss_no_more_message = 'no_more_message-'+sender+'_'+receiver;
	this.no_more_message = 0;
	//this.status = 0;
	this.new_msg_count = 0;
	this.loading_old = false;
	this.parent_rel_dom = $('.cg_group_id-'+receiver);// in the user list dom it is related to whom
	
	/**
	 * Online status
	 */
	this.online =  this.dom.data('online_status');
	//this.prepare();
	
	
 } 
 ChatBoxCg.prototype.prepare = function(e){ 	
	/**
	 Prepare for minimize/maximize
	 */
	var self = this;
	this.dom.find('.cg_header').on('click', this, this.toggle);	
	 
	/**
	 * Prepare for close
	 */
	this.dom.find('.cross_fb').on('click', this, this.close) 
	/**
	 *send content
	 */
	this.dom.find('.cg_send_content').on('keyup', this, this.send);
	
	/**
	 * Load old message
	*/
	this.dom.find('.cg_details_inner').on( 'scroll', this, this.loadOld);
	
	 //self.searchNew();
	 var searcg_interval = setInterval(function(){
		self.searchNew();
     },1000*3); 

	setInterval(function(){
		self.save();
	},1000*2);
	
	/**
	 * Chat box group create option
	 */
	 
	 this.dom.find('.cg_create_').on('click', this, this.opnGrpCrtFrm);
	 
	 /**
	  * Goup create
	  */
	  this.dom.find('.cg_create_button').on('click', this, this.createGrp);
 }
 /**
  * Create the group with the selected user
  */
  ChatBoxCg.prototype.createGrp = function(e){
  	var obj = null;
	if(e.data){
		obj =  e.data;
	}else{
		obj = this;
	}
	var userids = obj.dom.find('select').val();
	userids.push(obj.receiver);
	$.ajax({
		url : app_url+'&f=createGroup' ,
		type : 'post',
		data : {usid: userids.join(',')},
		success: function(r){
			if(r == 1){
				alert('Grop crated successfully group creation popup need to be opened');
			}
		}
	});
  }
 
 /**
  * OPen grp chat create frm
  */
  ChatBoxCg.prototype.opnGrpCrtFrm = function(e){
	var obj = null;
	if(e.data){
		obj =  e.data;
	}else{
		obj = this;
	}
	 e.stopImmediatePropagation(); 
    obj.dom.find(".grop_chat_back_details").slideToggle("slow");
	return false;
  
  }
 
 
 
 /**
  * Restore the previous standard
  */
 ChatBoxCg.prototype.restore = function(){
	var obj = this;
	/**
	 * restore the content
	 */
	obj.last_loaded = sessionStorage.getItem(obj.ss_last_loaded);
	obj.last_loaded = obj.last_loaded ? obj.last_loaded : 0;
	obj.content = sessionStorage.getItem(obj.ss_content);
	obj.dom.find('.cg_content_whole').html(obj.content);
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
 ChatBoxCg.prototype.save = function(e){
	 //return;
	 var obj = null;
	if(this instanceof chatBox) {
		obj = this;
	}else{
		obj = e.data;
	}
	
	sessionStorage.setItem(obj.ss_status, obj.status);
	sessionStorage.setItem(obj.ss_content, obj.dom.find('.cg_content_whole').html());
	sessionStorage.setItem(obj.ss_last_loaded, obj.last_loaded);
	sessionStorage.setItem(obj.ss_no_more_message, obj.no_more_message);
	
	//;
	//;
 }
 /**
  * load old message on scroll top 0
  */
 ChatBoxCg.prototype.loadOld = function(e){
 	var obj = e.data;
	//var height = obj.dom.find('.cg_details_inner').height();
	//;
	if( obj.dom.find('.cg_details_inner').scrollTop() == 0){
		obj.loadMessage();
	}else{
		//;
	}
 } 
 
 /**
  * Listen for new message
  */
 ChatBoxCg.prototype.searchNew = function(){	
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
			 	obj.dom.find('.cg_content_whole').append(response[0]);
				obj.moveScrollerDown();
			 }
			/**
			 * if chat box is minimized or hidden minimized then add notification
			 * to the header
			 */
			 if(parseInt(response[1]) > 0 && obj.status == 2) {
			 	obj.dom.find('.cg_new_msg_count').text(obj.new_msg_count).css({'padding': '2px', 'display': 'block'});
			 }
			 
			 /**
			 * if chat box is outsideview port then add notification to the
			 * parent_dom
			 */
			 if(parseInt(response[1]) > 0 && (obj.status == 0 || obj.status == 3)) {
			 	obj.parent_rel_dom.find('.cg_inread_cnt').text('('+obj.new_msg_count + ')').css({'padding': '2px'});
			 }
			 
			 /**
			  * if new 
			  */
			  if(parseInt(response[1]) > 0) {
			 	chat_app_obj.dom.find('.cg_total_inrd_cnt').text('('+obj.new_msg_count + ')').css({'padding': '2px'});
			 	}
		}
		
	})
 } 
 /**
  * Load old message
  */
 ChatBoxCg.prototype.loadMessage = function(){
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
			obj.dom.find('.cg_content_whole').prepend($html);
			$html.fadeIn('slow');
		}
	})
 }
 
 /**
  * The minimize function
  */
 ChatBoxCg.prototype.toggle = function(e){
	//e.stopImmediatePropagation();	
	var obj = e.data;
	if(obj.dom.find('.cg_header').hasClass('status_1')){// minimize
		obj.dom.find('.cg_header.status_1').removeClass('status_1').addClass('status_2');
  		obj.dom.find('.chat_details_back2').slideUp();
		obj.status = 2;
		// + remove notification		
	}else{// maximize
		obj.dom.find('.cg_header.status_2').removeClass('status_2').addClass('status_1');
  		obj.dom.find('.chat_details_back2').slideDown();
		obj.status = 1;
		obj.dom.find('.cg_new_msg_count').fadeOut().text('');
	}
	
	sessionStorage.setItem(obj.ss_status, obj.status);
	return false;
  }
  
	/**
	* Close functionality
	*/
	ChatBoxCg.prototype.close = function(e){
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
	
	ChatBoxCg.prototype.maximize = function(e){
		// if it is already open then return
		if(this.status == 1){
			//return;
		}
		//this.dom.css({right : '309px' });
		this.dom.find('.cg_header').addClass('status_1');	 
		this.dom.css('display', 'inherit');
		this.dom.find('.chat_details_back2').slideDown();				
		this.parent_rel_dom.find('.cg_inread_cnt').text('');
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
	ChatBoxCg.prototype.hide = function(){
		
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
	ChatBoxCg.prototype.send = function(e){	
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
				obj.dom.find('.cg_content_whole').append(html);
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
	ChatBoxCg.prototype.moveScrollerDown = function(){
	var obj = null;
	if(this instanceof chatBox){
		obj = this;
	}else{
		obj = e.data;
	}
	
	var percentageToScroll = 100;
	var height = obj.dom.find('.cg_details_inner')[0].scrollHeight;
	var scrollAmount = height;
	 obj.dom.find('.cg_details_inner').animate({scrollTop: scrollAmount}, 900);
	}
	
	/**
	 * synchronize offline online status
	 */
	 ChatBoxCg.prototype.synchronize = function(){
	 	var obj = this;
		var class_should = '';
		if(obj.online == 1){
			class_should = 'green';
		}else if(obj.online == 0){
			class_should = 'red';
		}else{
			class_should = 'yellow';
		}
		
		obj.dom.find('.cg_box_stat').removeClass('red green yellow').addClass(class_should);
	 }

  // --------============================================            Group chat =====================----------------------