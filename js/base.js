(function($){
	
	
	/**
	 * The doms are ready
	 */
	$(function(){
		
		/**
		 * Place validation to 
		 * forms
		 */
		 $.fn.bindValidation = function(){
			 $.validationEngine.defaults.scroll = false;
			 $('form[validate=validate]:not(.val_binded)').validationEngine({promptPosition : "topRight", autoPositionUpdate : true,
					onValidationComplete: function(form, status){
					if(status===true){form.validationEngine('hideAll');return true;}
				}
			 }).addClass('val_binded');
		 }
		 $.fn.bindValidation();
		
		
		/**
		 * The ioverlay alert funciton
		 */
	$.fn.overlay=function(flag,islow,duration){
		var t=$(this);
		if(t.find('.submitprocess').length==0 && flag==1){	
			t.prepend('<div class="submitprocess">Please wait while processing</div>');
			t.find('.submitprocess').css({'margin-top':(t.height()-t.find('.submitprocess').height())*0.5,'margin-left':(t.width()-t.find('.submitprocess').width())*0.5}).fadeIn('fast');
		}else if(t.find('.submitprocess').length>0 && flag==0){
			var speed='';
			if(islow==undefined){
				speed='slow';
			}else{
				speed=3000;
			}
			if(duration!=undefined){
				speed=duration;
			}
			t.find('.submitprocess').fadeOut(speed,function(){
				$(this).remove();	
			});
		}else if(t.find('.submitprocess').length>0 && flag.length>1){	
			t.find('.submitprocess').html(flag);
		}
	};
		
		/**
		 * Invoke the chat script
		 */
		 if(user_role != 0){
			$.ajax({
		 	url : base_path + main_container+ '/invoke?a=messaging_app&f=ajaxInit',
			success: function(r){
				var headID = document.getElementsByTagName("head")[0];         
				var newScript = document.createElement('script');
				newScript.type = 'text/javascript';
				newScript.src = r+'?v=1.08';
				headID.appendChild(newScript);
			}
			
		 })	
		 }
		 
	});
	
})(jQuery)