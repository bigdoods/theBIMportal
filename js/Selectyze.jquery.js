/************************************************************************
*************************************************************************
@Name :       	Selectyze - jQuery Plugin
@Revison :    	1.1
@Date : 		25/01/2011
@Author:     	Mickael SURREL - ALPIXEL Agency - (www.myjqueryplugins.com - www.alpixel.fr) 
@License :		 Open Source - MIT License : http://www.opensource.org/licenses/mit-license.php
 
**************************************************************************
*************************************************************************/
(function($) {
	$.fn.Selectyze = function(opt) {
		var defaults = {
			theme:'css3',
			effectOpen : 'slide',
			effectClose : 'slide'
		}; 
		
		if(this.length)
		return this.each(function() {
			
			/** vars **/
			var 
				opts = $.extend(defaults, opt),
				$this = $(this),
				optionselected = $this.find('option').filter(':selected'),
				DivSelect = $('<div>', {'class' : 'DivSelectyze '+opts.theme+''}),
				UlSelect = $('<ul>',{'class':'UlSelectize'}),
				liHtml = '';
			
			zIndex = 9999;

			/** DOM construction && manipulation **/
			constructList($this);
			$this.hide();
			$this.after(DivSelect);
			DivSelect.html('<a href="#" rel="'+optionselected.val()+'" class="selectyzeValue">'+optionselected.text()+'</a>');

			UlSelect.appendTo(DivSelect).html(liHtml);
			$('.DivSelectyze').each(function(i,el){
				$(this).css('z-index',zIndex);
				zIndex -= 10;
			});

			/** Actions **/
			n=false;
			DivSelect.mouseenter(function() {n =false;}).mouseleave(function() {n = true;});
			
			DivSelect.find('a.selectyzeValue').click(function(e){
				e.preventDefault();
				closeList($('ul.UlSelectize').not($(this).next()));
				openList($(this).next('ul.UlSelectize'));
			});
			
			UlSelect.find('a').click(function(e){
				e.preventDefault();
				DivSelect.find('a.selectyzeValue').text($(this).text());
				$this.val($(this).attr('rel'));           
				$this.trigger('change');         
				closeList($this.next().find('.UlSelectize'));
			});
			
			$(document).click(function(e){if(n) closeList($('.UlSelectize').not(':hidden'));});

			/** Construct HTML list ul/li **/
			function constructList(el){
				/** Creat list content **/
				if(el.has('optgroup').length)
				{
					el.find('optgroup').each(function(i,el){
						liHtml += '<li><span class="optgroupTitle">'+$(this).attr('label')+'</span><ul>';
						$(this).children().each(function(i,el){
							liHtml += '<li><a rel="'+$(this).val()+'" href="#">'+$(this).text()+'</a></li>';
						});
						liHtml += '</ul></li>';
					});
				}
				else
				{
					el.find('option').each(function(i,el){
						liHtml += '<li><a rel="'+$(this).val()+'" href="#">'+$(this).text()+'</a></li>';
					});
				}
			}

			/** Effect Open list **/
			function openList(el) {
				switch (opts.effectOpen) {
					case 'slide' :
						if(!el.is(':hidden')) el.stop(true,true).slideUp('fast');	
						else el.stop(true,true).slideDown('fast');	
					break;
					case 'fade':
						if(!el.is(':hidden')) el.stop(true,true).fadeOut('fast');	
						else el.stop(true,true).fadeIn('fast');	
					break;
					default :
						if(!el.is(':hidden')) el.stop(true,true).hide();	
						else el.stop(true,true).show();	
				}
			}
			
			/** Effect Close list **/
			function closeList(el) {
				switch (opts.effectClose) {
					case 'slide' :
						el.stop(true,true).slideUp('fast');
					break;
					case 'fade':
						el.stop(true,true).fadeOut('fast');
					break;
					default :
						el.hide();	
				}
			}
			
		});
	}
})(jQuery);