// JS untuk Navbar
"use strict";

$(document).ready(function () {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Search Buku dan Author ( ada di Navbar )
    $('#search').on('keyup', function() {
        if ($(this).val() === '') {
            $('#search-values').hide();
        }
        else {
            $('#search-values').show();
            $.ajax({
                type   : 'POST',
                url    : '/ajax/request/store',
                data   : { '_token' : csrfToken, 'search_value' : $('#search').val() },
                success: function(data) {
                    data.books.length == 0 ? $('#search-books').prev().hide() : $('#search-books').prev().show();
                    data.authors.length == 0 ? $('#search-authors').prev().hide() : $('#search-authors').prev().show();

                    if (data.books.length == 0 && data.authors.length == 0) {
                        $('#search-values > div:first-child()').hide();
                    }
                    else {
                        $('#search-values > div:first-child()').show();
                    }

                    $('#search-books').html('');
                    data.books.forEach(element => {
                        $('#search-books').append(
                            '<li><a href=\'#\' class="text-decoration-none text-body">' + element.name +'</a></li>'
                        );
                    });

                    $('#search-authors').html('');
                    data.authors.forEach(element => {
                        $('#search-authors').append('<li><a href=\'#\' class="text-decoration-none text-body">' + element.name +'</a></li>');
                    });
                }
            });
        }
    });

    // Modal Login
    $('#toggle-password').on('click', function() {
        if ($('#password').is('input[type=password]')) {
            $(this).html('<i class="fas fa-low-vision"></i>');
            $('#password').attr('type', 'text');
        }
        else {
            $(this).html('<i class="fas fa-eye"></i>');
            $('#password').attr('type', 'password');
        }
    });

    $('#login-exit').on('click', function() {
        // $('#email').val('');
        // $('#password').val('');
    });

    $(document).on('scroll', function() {
        $(this).scrollTop() >= 40 ? $('.click-to-the-top').show() : $('.click-to-the-top').hide();
    });

    $('.click-to-the-top').on('click', function() {
        let scrollY        = $(document).scrollTop();
        let scrollToTheTop = setInterval( function() {
            $(document).scrollTop() == 0 ? clearInterval(scrollToTheTop) : $(document).scrollTop(scrollY-=14);
        });
    });
});
