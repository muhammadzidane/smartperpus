// JS untuk Navbar
"use strict";


$(document).ready(function () {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#login').trigger('click');

    // Search Buku dan Author ( ada di Navbar )
    $('#search').on('keyup', function () {
        if ($(this).val() === '') {
            $('#search-values').hide();
        } else {
            $('#search-values').show();
            $.ajax({
                type: 'POST',
                url: '/ajax/request/store',
                data: {
                    '_token': csrfToken,
                    'search_value': $('#search').val()
                },
                success: function (response) {
                    response.books.length == 0 ? $('#search-books').prev().hide() : $('#search-books').prev().show();
                    response.authors.length == 0 ? $('#search-authors').prev().hide() : $('#search-authors').prev().show();

                    if (response.books.length == 0 && response.authors.length == 0) {
                        $('#search-values > div:first-child()').hide();
                    } else {
                        $('#search-values > div:first-child()').show();
                    }

                    $('#search-books').html('');
                    response.books.forEach(element => {
                        $('#search-books').append(
                            '<li><a href=\'#\' class="text-decoration-none text-body">' + element.name + '</a></li>'
                        );
                    });

                    $('#search-authors').html('');
                    response.authors.forEach(element => {
                        $('#search-authors').append('<li><a href=\'#\' class="text-decoration-none text-body">' + element.name + '</a></li>');
                    });
                }
            });
        }
    });

    // Modal Login
    $('#toggle-password').on('click', function () {
        if ($('#password').is('input[type=password]')) {
            $(this).html('<i class="fas fa-low-vision"></i>');
            $('#password').attr('type', 'text');
        } else {
            $(this).html('<i class="fas fa-eye"></i>');
            $('#password').attr('type', 'password');
        }
    });

    // Button (Gambar Arrow Up) untuk men-scroll ke bagian paling atas web
    $(document).on('scroll', function () {
        $(this).scrollTop() >= 40 ? $('.click-to-the-top').show() : $('.click-to-the-top').hide();
    });

    $('.click-to-the-top').on('click', function () {
        let scrollY = $(document).scrollTop();
        let scrollToTheTop = setInterval(function () {
            $(document).scrollTop() == 0 ? clearInterval(scrollToTheTop) : $(document).scrollTop(scrollY -= 25);
        });
    });

    // Validasi Email & Password
    $('#button-login').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('active-login') && $('#email').val() != '' && $('#password').val() != '') {
            $.ajax({
                type: "POST",
                url: "/ajax/request/check-login",
                data: {
                    '_token'  : csrfToken,
                    'email'   : $('#email').val(),
                    'password': $('#password').val(),
                },
                success: function (response) {
                    let errorLogin  = '<div class="error tred small mb-2" role="alert">';
                        errorLogin += '<strong>' + response.message + '</strong>';
                        errorLogin += '</div>';

                    if (!response.success) {
                        $('#error-login').first().html((errorLogin));
                    }
                    else {
                        $('#form-login').trigger('submit');
                    }
                }
            });
        }
    });


    $('.form-control-login').on('keyup', function () {
        if ($('input[type=email]').val() != '' && $('input[type=password]').val() != '') {
            $('#button-login').addClass('active-login');
        } else {
            $('#button-login').removeClass('active-login');
        }
    });

    // Error Login
    if ($('.error').length) {
        $('#login').trigger('click');
    }

    // Menghapus pesan error login, keluar dari modal login
    $('#login-exit').on('click', function () {
        $('.error').html('');
        $('#errorLogin').html('');
    });

    // Jika validasi form login bagian backend mulai bekerja, maka munculkan alert
    if (!$('.error-backend').is(':visible')) {
        $('.error-backend').trigger('click');
    }

});

function alertError(message) {
    alert(message)
}
