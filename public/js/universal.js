$(document).ready(function () {

    // JAVASCRIPT STOCK, PENTRU BOOTSTRAP
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    jQuery('[data-toggle="tooltip"]').tooltip();

    // JAVASCRIPT CUSTOM
    // javascript pentru css
    // $('.group-btns').children().each(function (index, element) {
    //     $(this).addClass('mb-1');
    // });
    $(".card").each(function (index, element) {
        $(this).addClass("shadow-sm");
    });
    $(".navbar").each(function (index, element) {
        $(this).addClass("shadow-sm");
    });
    $(".accordion").each(function (index, element) {
        $(this).addClass("shadow-sm");
    });
    $(".dropdown-menu").each(function (index, element) {
        $(this).addClass("shadow");
        $(this).addClass("p-2");
    });
    // 
    // CSS CUSTOM
    // pentru offcanvas
    $('.offcanvas-header').addClass('rounded');
    $('.offcanvas-footer').addClass('rounded');
    // pentru colorarea texturilor din label
    $(".form-floating").each(function (index, element) {
        $(this).find($("label")).each(function (index, element) {
            $(this).addClass("text-secondary");
        });

    });
    // pentru unele stiluri ce depinde de url la pagina
    let check_url_page = $(location).attr("href");

    if (check_url_page.includes('/about')) {
        $("#app").removeClass("gap-5");
        $("#about").addClass("mb-3");
    }

    // pentru fixarea navbar-ului
    function fix_navbar() {
        if ($(window).width() < 992) {
            $(".content-under-header").css("padding-top", 0);
            $("#navbar-authentificated-mood").removeClass('position-fixed');
        }
        else {
            $("#navbar-authentificated-mood").addClass('position-fixed');
            let nav_height = $("#navbar-authentificated-mood").height();
            $(".content-under-header").css("padding-top", nav_height);
        }
    }

    var token = $("#token_access").val();
    if (token != null)
        localStorage.setItem('token', token)
    var token1 = localStorage.getItem('token');

    setTimeout(
        function () {
            fix_navbar();
        }, 1000); // Wait 5 seconds or 5,000 milliseconds
    $(window).resize(function () {
        fix_navbar();
    });

});