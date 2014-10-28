$(document).ready(function() {

    $('#request-qto').hide();

    var appWindowHeight = $(window).height() - 180;

    $('.qto-left').css('height', appWindowHeight);

    $(window).bind('resize', function(e) {
        appWindowHeight = $(window).height() - 180;
        $('.qto-left').css('height', appWindowHeight);
    });

    $('input[name=qto]').click(function() {

        var qtoFolder = $(this).val();

        $('#request-qto').show();

        if($('#folder-name').length) {
            $('#folder-name').remove();
            $('input[name=description]').before('<p id="folder-name">' + qtoFolder + '</p>');
        } else {
            $('input[name=description]').before('<p id="folder-name">' + qtoFolder + '</p>');
        }

        $('input[name=description]').attr("value", qtoFolder);

    });

});