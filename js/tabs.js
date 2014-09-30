$(document).ready(function() {
	forceLoad = false;
	//When page loads...
	//$(".tab_content").hide(); //Hide all content
	//$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	//$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
	
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var data_url = $(this).attr('data-url');
		/**
		 * Load content from ajax
		 */
		 var dom = $('.right_details_tab');
		 var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		 /**
		  * Here we use a global variable
		  * to force fully load a tab
		  */
		if(data_url && ($(activeTab).length == 0 || forceLoad)){
			$.ajax({
				url: data_url,
				beforeSend:function(){
					dom.overlay(1);
					dom.overlay("Please wait");
				},
				success:function(r, status, jqXHR){
					dom.overlay("prcessing complete");
					dom.html(r);
				},
				error:function( jqXHR,  textStatus, errorThrown){
					dom.overlay("Some error occured, please try after sometime");
				},
				complete:function(jqXHR, textStatus){
					dom.overlay(0,-1);
					$.fn.bindValidation();
				}
				
			});
		}
		
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
		
	});
	
	
	
});