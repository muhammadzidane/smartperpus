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
                    'search_value': $('#keywords').val()
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

    $('.search-form button').on('click', function(e) {
        if (window.location.pathname == '/search/books') {
            e.preventDefault();

            $.ajax({
                type    : "POST",
                url     : "/ajax/request/search",
                data    : {
                    '_token'       : csrfToken,
                    'keywords'     : $('#keywords').val(),
                },
                success : function (response) {
                    // Merubah parameter URL tanpa reload

                    $('#book-search').html(response.books);
                    $('#search-text').html($('#keywords').val());

                    for (const key in response.bookCategory) {
                        if (response.bookCategory.hasOwnProperty.call(response.bookCategory, key)) {
                            const value = response.bookCategory[key];
                            $('#book-categories').html(`<div class="c-p">${key} (${value})</div>`);
                        }
                    }

                    // Merubah parameter URL tanpa reload
                    history.pushState({}, null, `http://smartperpus.com/search/books?keywords=${$('#keywords').val()}`);
                },
            });
        }
    });

    $('#keywords').on('blur', function() {
        $('#search-values').hide();
    });

    // Modal Login
    $('#toggle-password').on('click', function () {
        if ($('#password').is('input[type=password]')) {
            $(this).html('<i class="fas fa-low-vision"></i>');
            $('#password').attr('type', 'text');
        }
        else {
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

        let min_price_val        = $('#min-price').val();
        let max_price_val        = $('#max-price').val();

        if (min_price_val != '') {
            appendFilter('.filter-min-price',
                [`Min Rp ${(numberFormat(min_price_val, 0, 0, `.`))}`, min_price_val, 'filter-min-price']
            )
        }

        if (max_price_val != '') {
            appendFilter('.filter-max-price',
                [`Max Rp ${(numberFormat(max_price_val, 0, 0, `.`))}`, max_price_val, 'filter-max-price']
            )
        }

        $('.click-to-the-top').trigger('click');

        ajaxFilterDataBooks();
    });

    // Filter Rating
    $('.filter-star-search').on('click', function() {
        $('.click-to-the-top').trigger('click');

        appendFilter('.rating-4-plus',
            [`Bintang 4 Keatas`, 4, 'rating-4-plus']
        )

        ajaxFilterDataBooks();
    });

    // Book Search - Animasi transparan buku buku
    let count = 0;
    $('.book').css('opacity', '0');
    let bookOpacityLoad = setInterval(function()  {
        if (count >= 1) {
            clearInterval(bookOpacityLoad);
        }

        $('.book').css('opacity', count+=0.1);
    }, 60);

    // Sort buku
    $('#sort-books').on('change', function() {
        let min_price_val        = $('.filter-min-price').data('filter-value');
        let max_price_val        = $('.filter-max-price').data('filter-value');
        let sortBookVal          = $('#sort-books').val();
        let sortBookSelectedText = $('#sort-books option:checked').text();

        appendFilter('.filter-sort',
            [`${sortBookSelectedText}`, $('#sort-books option:checked').val() , 'filter-sort']
        )

        $.ajax({
            type: "POST",
            url : "/ajax/request/filter-search",
            data: {
                '_token'         : csrfToken,
                'min_price'      : min_price_val,
                'max_price'      : max_price_val,
                'star_value'     : $('.rating-4-plus').length == 0 ? null : $('.filter-star-search').data('filter-star'),
                'sort_book_value': sortBookVal,
                'keywords'       : getUrlParameter('keywords'),
            },
            success: function (response) {
                $('#book-search').html(response.books);
                exitFilters();
            }
        });
    });

    // Pagination
    $.ajax({
        type   : "POST",
        url    : "/ajax/request/pagination-data",
        data   : {
            '_token' : csrfToken,
        },
        success: function (response) {
            response.paginationHtml.forEach(element => {
                $('#pagination-number').append(element);

            });

            if (response.paginationHtml.length > 5) {
                $('#pagination-number').children().eq(3).text('...').css('pointer-events', 'none');
                $('#pagination-number').children().eq(4).nextAll().remove();
                $('#pagination-number').children().last().text(response.paginationHtml.length);
            }

            $('#pagination-number').children().first().addClass('p-active');
        }
    });

    $('#pagination-number').on('click', function(e) {
        let pageNumber = $(e.target).text();

        $.ajax({
            type: "POST",
            url : "/ajax/request/pagination",
            data: {
                '_token': csrfToken,
                'page'  : pageNumber,
            },
            success: function (response) {
                $(e.target).siblings().removeClass('p-active');
                $(e.target).addClass('p-active');
                $('.click-to-the-top').trigger('click');
                $('#book-search').html(response.books);
            }
        });

    });

    $('#pagination-next').on('click', function(e) {
        let activePage         = $('.p-active');
        let lastPaginationText = parseInt($('#pagination-number').children().last().text()) - 1

        activePage.next().trigger('click');

        if (activePage.text() == lastPaginationText) {
            e.preventDefault();
        }
        else if (activePage.text() >= 3) {
            activePage.prev().text(parseInt(activePage.prev().text()) + 1);
            activePage.prev().prev().text(parseInt(activePage.prev().text()) - 1);
            activePage.text(parseInt(activePage.prev().text()) + 1).trigger('click');
        }
    });

});


function alertError(message) {
    alert(message)
}
