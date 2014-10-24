$(document).ready(function() {

    var appWindowHeight = $(window).height() - 180;

    $('.qto-left').css('height', appWindowHeight);

    $(window).bind('resize', function(e) {
        appWindowHeight = $(window).height() - 180;
        $('.qto-left').css('height', appWindowHeight);
    });

});