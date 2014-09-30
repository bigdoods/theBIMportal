// JavaScript Document

$(function(){	
	$(document).on('keyup','.ch_user_search', function(){
		var search_term = $(this).val()
		var search_pattern = new RegExp(search_term+'.?', 'i');
		$('li .details_face_left h2').each(function(){
			if(!search_pattern.test( $(this).text() )){
				$(this).closest('li').hide();
			}else{
				$(this).closest('li').show();
			}
		});
	});
	
	// + search in chat list
	$(document).on('keyup','.ch_search', function(){
		var search_term = $(this).val();
		var search_pattern = new RegExp(search_term+'.?', 'i');
		$('.ch_user_list .online_back p.ch_user_name').each(function(){
			if(!search_pattern.test( $(this).text() )){
				$(this).closest('div.ch_user').hide();
			}else{
				$(this).closest('div.ch_user').show();
			}
		});
	});
	$(document).on( 'submit', '[name=instant_message]', function(){
		var recevier = $(this).find('[name="receiver"]').val();
		var message = $(this).find('.text_area_box').val();		
		var html2 = '<div id="message_x" class="portion_details ch_old_message"><div class="own"><h2><span></span>'+message+'</h2></div></div>';
		$('#ch_box-'+recevier).find('.ch_content_whole').append(html2);
		var chat_old_content = sessionStorage.getItem('chat_conent-'+$(this).find('[name="sender"]').val()+ '_' + $(this).find('[name="receiver"]').val());
		if(chat_old_content)
			chat_old_content += html2;
		else{
			chat_old_content = html2;
		}
		sessionStorage.setItem('chat_conent-'+$(this).find('[name="sender"]').val()+ '_' + $(this).find('[name="receiver"]').val(),chat_old_content );
		
		//return false;
	});
})

