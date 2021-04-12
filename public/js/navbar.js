// JS untuk Navbar
"use strict";

$(document).ready(function () {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#login').trigger('click');

    // Search Buku dan Author ( ada di Navbar )
    $('#keywords').on('keyup', function () {
        if ($(this).val() === '') {
            $('#search-values').hide();
        }
        else {
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

    keyUpToggleFormButton('.login-form');

    // Validasi Email & Password
    $('#button-submit').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('active-login') && checkFormRequiredInputs('.login-form')) {
            $.ajax({
                type: "POST",
                url : "/ajax/request/check-login",
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

    // Error Login
    if ($('.error').length) {
        $('#login').trigger('click');
    }

    // Menghapus pesan error dan input value login, saat men-click tombol exit pada modal login
    $('#login-exit').on('click', function () {
        $('.error').html('');
        $('#errorLogin').html('');
        $('#email').val('');
        $('#password').val('');
    });

    // Menghapus input value login, saat menekan tombol keyboard 'ESC'
    $(document).on('keyup', function(e) {
        if (e.code == 'Escape') {
            $('#email').val('');
            $('#password').val('');
        }
    });

    // Jika validasi form login bagian backend mulai bekerja, maka munculkan alert
    if (!$('.error-backend').is(':visible')) {
        $('.error-backend').trigger('click');
    }

    // Modul ~ Search Book
    // Filter minimum / maksimum harga
    $('#min-max-value').on('click', function(e) {
        e.preventDefault();

        $('.click-to-the-top').trigger('click');

        let min_price_val = $('#min_price').val();
        let max_price_val = $('#max_price').val();
        let filter_vals   = [];


        let filter_html = function(text, value , filterName) {
            let val  = `<div class="search-filter" id="${filterName}" data-filter-value="${value}">`;
                val += `<span class="search-filter-value">${text}</span>`;
                val += `<i class="exit-filter fa fa-times text-grey ml-2" aria-hidden="true"></i>`;
                val += `</div>`;

            return val;
        };

        $.ajax({
            type: "POST",
            url : "/ajax/request/min-max-price",
            data: {
                '_token'   : csrfToken,
                'min_price': min_price_val,
                'max_price': max_price_val,
                'keywords' : getUrlParameter('keywords'),
            },
            success: function (response) {
                $('#book-search').html(response.books);

                if (min_price_val != '') {
                    filter_vals.push(filter_html(`Min Rp ${(numberFormat(min_price_val, 0, 0, `.`))}`, min_price_val, 'filter_min_price'));
                }

                if (max_price_val != '') {
                    filter_vals.push(filter_html(`Max Rp${(numberFormat(max_price_val, 0, 0, `.`))}`, max_price_val, 'filter_max_price'));
                }

                $('#search-filters').html('');

                filter_vals.forEach( function(value) {
                    $('#search-filters').append(value);
                });

                $('.exit-filter').on('click', function() {
                    $(this).parent().remove();

                    $.ajax({
                        type: "POST",
                        url : "/ajax/request/min-max-price",
                        data: {
                            '_token'   : csrfToken,
                            'min_price': $('#filter_min_price').data('filter-value') ?? 0,
                            'max_price': $('#filter_max_price').data('filter-value') ?? 999999999,
                            'keywords' : getUrlParameter('keywords'),
                        },
                        success: function (response) {
                            $('#book-search').html(response.books);
                        }
                    });

                });

            }
        });
    });

    // if ($('#filter_min_price').is(':empty')) {
    // }

    // Book Search - Animasi transparan buku buku
    let count = 0;
    $('.book').css('opacity', '0');
    let bookOpacityLoad = setInterval(function()  {
        if (count >= 1) {
            clearInterval(bookOpacityLoad);
        }

        $('.book').css('opacity', count+=0.1);
    }, 60);


    $('.exit-filter').on('click', function() {
        console.log(true);
    });
});

function alertError(message) {
    alert(message)
}
