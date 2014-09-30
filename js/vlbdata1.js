jQuery(document).ready(function(){ window.Lightbox = new jQuery().visualLightbox({autoPlay:true,borderSize:16,classNames:'vlightbox1',descSliding:true,enableRightClick:false,enableSlideshow:true,prefix:'vlb1',resizeSpeed:7,slideTime:4,startZoom:true}) });

function addLightBoxOnDynamicElement(){
	jQuery(document).ready(function(){ window.Lightbox = new jQuery().visualLightbox({autoPlay:true,borderSize:16,classNames:'vlightbox1_new',descSliding:true,enableRightClick:false,enableSlideshow:true,prefix:'vlb1',resizeSpeed:7,slideTime:4,startZoom:true}) });
	$('.vlightbox1_new').removeClass('vlightbox1_new');
}