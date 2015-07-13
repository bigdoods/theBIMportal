var tour = new Tour({
	debug: true,
	storage: false,
	onStart: function() {
		$('.back_container .main .left').css('z-index', 0);
	},
	onEnd: function() {
		$('.back_container .main .left').css('z-index', 2);
	},
  steps: [
  {
    element: "#pro-map",
    title: "Navigating the ARC",
    content: "Use this area of the portal to view the Process Maps for the Anglia Route Collaboration.",
    placement: "bottom",
    backdrop: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	}
  },
  {
    element: "#pro-map .grip:nth-of-type(1)",
    title: "GRIPs",
    content: "From this view you can see the outputs of a GRIP which are inputted into the next GRIP on the timeline by hovering over the blue dots on the side.",
    placement: "right",
    backdrop: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	    $("#pm-timeline .grip span").css('z-index', '1200');
	    $('.popover[class*="tour-"]').css('z-index', '1201');
	},
	onNext: function() {
		$("#pm-timeline .grip span").css('z-index', '20');
	}
  },
  {
    element: "#pro-map .grip:nth-of-type(1)",
    title: "GRIPs",
    content: "Click on the first GRIP to view its Process Map.",
    placement: "right",
    backdrop: true,
    reflex: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	},
	onNext: function() {
		$('#pm-timeline a.active').removeClass('active');
	 	$("#pro-map .grip:nth-of-type(1)").addClass('active');

	 	var frameHeight = $('#content_2').height() - $('#pm-timeline').height() - 260;

	 	if($('#pro-map #chart-frame iframe').length) $('#pro-map #chart-frame iframe').remove();
	 	$('#pro-map #chart-frame').append('<iframe allowfullscreen frameborder="0" style="width:100%; height: '+frameHeight+'px;" src="https://www.lucidchart.com/documents/embeddedchart/9cb81feb-136d-4880-8dff-61ef0690cbc1" id="X-eUPgYC8fSL"></iframe>');
	 	$('#pro-map #chart-frame').animate({'height': frameHeight}, 200);
	}
  },
  {
    element: "#pro-map #chart-frame",
    title: "Viewing the Process Map",
    content: "This is where you can view and navigate around the Process Map for each GRIP by clicking and dragging to pan across. If you move your mouse to the bottom right corner of this area you will see the zoom and fullscreen controls. You can also double-click anywhere to zoom in.<br><br>Click 'Next' when you're ready.",
    placement: "top",
    backdrop: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	}
  },
  {
    element: "#pro-map #chart-frame #key",
    title: "Abbreviations Key",
    content: "There are many abbreviations throughout these process maps, click this button to view a list of the abbreviations and their full versions.",
    placement: "top",
    backdrop: true,
    reflex: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	    $("#pro-map #chart-frame #key").css('z-index', '1101');
	}
  },
  {
    element: "#pro-map #chart-frame #key",
    title: "Abbreviations Key",
    content: "Click the cross in the top right corner to close the Key.",
    placement: "top",
    backdrop: true,
    reflex: true,
    onShown: function() {
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	    if($('#pro-map #key').hasClass('show') == false) {
	    	$('#pro-map #key').addClass('show');
			$('#pro-map #key').animate({'height': $('#pro-map #key ul').outerHeight()}, 200);
	    }
	}
  },
  {
    element: "#pro-map #chart-frame .close-frame",
    title: "Close the Process Map",
    content: "Click here to close the Process Map.",
    placement: "left",
    backdrop: true,
    reflex: true,
    onShown: function() {
    	$("#pro-map #chart-frame #key").css('z-index', '21');
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	    $("#pro-map #chart-frame .close-frame").css('z-index', '1101');
	    $('#pro-map #key').removeClass('show');
		$('#pro-map #key').animate({'height': 30}, 200);
	}
  },
  {
    element: "#pm-timeline #extra",
    title: "Extra Resources",
    content: "You can view these extra resources through the same method by clicking on them.<br><br><strong>Click 'End Tour' to exit this guide.</strong>",
    placement: "bottom",
    backdrop: true,
    onShown: function() {
    	$("#pro-map #chart-frame .close-frame").css('z-index', '20');
	    $(".tour-backdrop").prependTo(".content_main");
	    $(".tour-step-background").prependTo(".content_main");
	}
  }
]});

$(document).on('click', '#start-tutorial', function() {

	tour.init();
	tour.start();

});