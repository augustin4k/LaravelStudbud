
$(document).ready(function () {

    function full_screen_chat() {
        if ($(window).width() < 992) {
            $(".content-under-header").removeClass("mt-3");
        } else {
            $(".content-under-header").addClass("mt-3");
        }
        var height_header = $("#navbar-authentificated-mood").height();
        var top = $(".chat-app").offset().top;
        var otstup = top - height_header
        window_height = $(window).height();
        chat_height = window_height - top - otstup;

        $(".chat-app").height(chat_height);
    };

    full_screen_chat();
    $(window).resize(function () {
        full_screen_chat();
    });

});
