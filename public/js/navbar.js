// JS untuk Navbar
"use strict";

$(document).ready(function () {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Jika /search/books
    // Modul Search Books - First Load
    if (window.location.pathname == '/search/books') {
        $.ajax({
            type: "POST",
            url : "/ajax/request/first-load",
            data: {
                '_token': csrfToken,
                'page'  : getUrlParameter('page'),
            },
            success: function (response) {
                $('#book-search').html(response.books);
                $('.book').css('width', '22.52%');
            },
        });
    }

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
                    $('#book-search').html(response.books);
                    $('#search-text').html($('#keywords').val());

                    for (const key in response.bookCategory) {
                        if (response.bookCategory.hasOwnProperty.call(response.bookCategory, key)) {
                            const value = response.bookCategory[key];
                            $('#book-categories').html(`<div class="c-p">${key} (${value})</div>`);
                        }
                    }

                    // Merubah parameter URL tanpa reload
                    history.pushState({}, null,
                      `http://smartperpus.com/search/books?keywords=${$('#keywords').val()}&page=${$('.p-active').text()}`);
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
    $('.min-max-value').on('click', function(e) {
        e.preventDefault();

        let min_price_val = $('#modal-filter').hasClass('show') ? $('.min-price').last().val() : $('.min-price').val();
        let max_price_val = $('#modal-filter').hasClass('show') ? $('.max-price').last().val() : $('.max-price').val();

        $('#modal-filter').modal('hide');

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

        $('#modal-filter').modal('hide');

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
                $('#pagination-number').children().eq(5).text('...').css('pointer-events', 'none');
                $('#pagination-number').children().eq(6).nextAll().remove();
                $('#pagination-number').children().last().text(response.paginationHtml.length);
            }

            $('#pagination-number').children().first().addClass('p-active');
            $('#pagination-prev').hide();


            let paginationLength = $('#pagination-number').children().length;

            for (let i = 1; i <= paginationLength; i++) {
                if (getUrlParameter('page') == i) {
                    $('.p-active').removeClass();
                    $(`#page-${i}`).addClass('p-active');
                }
            }
        }
    });

    $('#pagination-number').on('click', function(e) {
        $('#book-search').html('');
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
                ajaxFilterDataBooks();
                console.log(pageNumber);
            }
        });

        if (pageNumber == 1) {
            $('.p-hide').show();
            $('#pagination-prev').hide();

            for (let i = 2; i <= 5; i++) {
                $(`#pagination-number div:nth-child(${i})`).text(i);
            }
        }
        else if (pageNumber >= 2) {
            $('#pagination-prev').show();
        }
    });

    // Pagination Next
    $('#pagination-next').on('click', function() {
        let activePage         = $('.p-active');
        let paginationNth1     = $('#pagination-number div:nth-child(1)');
        let paginationNth2     = $('#pagination-number div:nth-child(2)');
        let paginationNth3     = $('#pagination-number div:nth-child(3)');
        let paginationNth4     = $('#pagination-number div:nth-child(4)');
        let lastPagination     = $('#pagination-number').children().last();

        if (activePage.text() >= 5 && activePage.text() != lastPagination.text()) {
            paginationNth2.text('...');
            paginationNth1.text(1);

            if ((paginationNth3.hasClass('p-active') || paginationNth4.hasClass('p-active')) && paginationNth2.text() == '...') {
                activePage.next().trigger('click');
            }
            else {
                activePage.text(parseInt(activePage.text()) + 1).trigger('click');
                paginationNth4.text(activePage.text() - 1);
                paginationNth3.text(activePage.text() - 2);
            }

            if (activePage.text() == lastPagination.text()) {
                lastPagination.prev().addClass('p-hide').hide();
                lastPagination.addClass('p-hide').hide();
            }
        }
        else {
            activePage.next().trigger('click');
        }

        window.history.pushState({}, null,
            `http://smartperpus.com/search/books?keywords=${$('#keywords').val()}&page=${parseInt(activePage.text()) + 1}`
        );
    });

    $('#pagination-prev').on('click', function() {
        let activePage     = $('.p-active');
        let paginationNth2 = $('#pagination-number div:nth-child(2)');
        let paginationNth3 = $('#pagination-number div:nth-child(3)');
        let paginationNth4 = $('#pagination-number div:nth-child(4)');
        let paginationNth5 = $('#pagination-number div:nth-child(5)');

        if (paginationNth4.hasClass('p-active') || paginationNth5.hasClass('p-active')) {
            activePage.prev().trigger('click');
        }

        if (paginationNth3.hasClass('p-active') && paginationNth2.text() == '...') {
            paginationNth3.text(parseInt(activePage.text() -  1)).trigger('click');
            paginationNth4.text(parseInt(paginationNth4.text()) -  1);
            paginationNth5.text(parseInt(paginationNth5.text()) -  1);
        }

        if (paginationNth3.text() == 2 && paginationNth2.text() == '...') {
            paginationNth2.text(2);
            paginationNth3.text(3);
            paginationNth4.text(4);
            paginationNth5.text(5);
        }

        if (activePage.text() <= 3) {
            activePage.prev().trigger('click');
        }

        window.history.pushState({}, null,
            `http://smartperpus.com/search/books?keywords=${$('#keywords').val()}&page=${parseInt(activePage.text()) - 1}`
        );

    });

    // Book Buy
    $('#plus-one-book').on('click', function(e) {
        if ($('#total-book').text() == 0) {
            e.preventDefault();
        }
        else {
            $('#book-needed').text(parseInt($('#book-needed').text()) + 1);
            $('#jumlah-barang').text(parseInt($('#jumlah-barang').text()) + 1);
            $('#total-book').text(parseInt($('#total-book').text()) - 1);
        }

        checkShippingInsurance();
    });

    $('#sub-one-book').on('click', function(e) {

        if ($('#book-needed').text() == 1 ) {
            e.preventDefault();
        }
        else {
            $('#jumlah-barang').text(parseInt($('#jumlah-barang').text()) - 1);
            $('#book-needed').text(parseInt($('#book-needed').text()) - 1);
            $('#total-book').text(parseInt($('#total-book').text()) + 1);
        }

        checkShippingInsurance();
    });

    $('#shipping-insurance').on('click', function() {
        checkShippingInsurance();
    });


    // Tulis Catatan
    $('#btn-write-notes').on('click', function() {
        $(this).hide();
        $('#input-write-notes').removeClass('d-none');

        $('#write-notes-cancel').on('click', function() {
            $('#btn-write-notes').show();
            $('#input-write-notes').addClass('d-none');
        });
    });

    // Cek API Rajaongkir
    $('.courier-choise').on('click', function() {
        $('input[name=courier-choise]').removeAttr('checked');
        $(this).children('input').attr('checked', 'checked');
        $('.courier-choise').removeClass('cc-active');
        $(this).addClass('cc-active');

        let originSubdistictId       = $('#origin').data('subdistrict-id');
        let originType               = $('#origin').data('origin-type');
        let destinationSubdistrictId = $('#destination').data('subdistrict-id');
        let destinationType          = $('#destination').data('destination-type');
        let weight                   = $('#weight').data('weight');
        let courierChoise            = $('input[name=courier-choise]:checked').val();

        $.ajax({
            type: "POST",
            url : "/ajax/request/cek-ongkir",
            data: {
                '_token'          : csrfToken,
                'origin_id'       : originSubdistictId,
                'origin_type'     : originType,
                'destination_id'  : destinationSubdistrictId,
                'destination_type': destinationType,
                'weight'          : weight,
                'courier'         : courierChoise,
            },
            dataType: "JSON",
            success : function (response) {
                let result = response.rajaongkir.results[0];

                $('#courier-choise-name').text(result.name);
                $('#courier-choise-result').html('');

                let courierChoiseHtml  = function(description,etd ,costPrice) {

                    // Jika tidak ada string Hari pada etd, maka tambahkan string 'Hari'
                    let path        = /(HARI|JAM)/i;
                    let regexResult = etd.match(path) ?? '';
                    etd         = regexResult ? etd.replace(path, ucwords(regexResult[0].toLowerCase())) : etd = etd + ' Hari';

                    let courierChoiseHtml  = `<div>`;
                        courierChoiseHtml += `<div class="courier-choise-service">`;
                        courierChoiseHtml += `<input value="${costPrice}" type="radio" name="courier-service" class="inp-courier-choise-service">`;
                        courierChoiseHtml += `<span class="tbold">${description}</span>`;
                        courierChoiseHtml += `<span> - </span>`;
                        courierChoiseHtml += `<span class="text-grey">${etd}</span>`;
                        courierChoiseHtml += `<div class="ml-4">`;
                        courierChoiseHtml += `<span>${rupiahFormat(costPrice)}</span>`;
                        courierChoiseHtml += `</div>`;
                        courierChoiseHtml += `</div>`;
                        courierChoiseHtml += `</div>`;

                    return courierChoiseHtml;
                }

                result.costs.forEach(element => {
                    element.cost.forEach(costElement => {
                        $('#courier-choise-result').append(courierChoiseHtml(element.description, costElement.etd, costElement.value));
                    });
                });

                $('.courier-choise-service').on('click', function() {
                    $('.inp-courier-choise-service').removeAttr('checked');
                    $(this).children('input').attr('checked', 'checked');

                    let bookPrice                = $('#book-price').data('book-price');
                    let shippingCost             = $(this).children('input:checked').val() ?? 0;
                    let bookNeeded               = parseInt($('#book-needed').text());
                    let shippingInsurance        = $('#shipping-insurance').is(':checked') ? 1000 : 0;
                    let courierServicePriceHtml  = `<div class="d-flex justify-content-between">`;
                        courierServicePriceHtml += `<div>Ongkos Kirim</div>`;
                        courierServicePriceHtml += `<div id="shipping-cost">${shippingCost}</div>`;
                        courierServicePriceHtml += `</div>`;

                        if ($('#shipping-cost').length <= 0) {
                            $('#book-payment').append(courierServicePriceHtml);
                        }
                        else {
                            $('#shipping-cost').text(shippingCost);
                        }

                        let totalPayment = (bookPrice * bookNeeded) + parseInt(shippingCost) + shippingInsurance;

                        $('#total-payment').attr('data-total-payment', totalPayment);
                        $('#total-payment').text(rupiahFormat(totalPayment));
                });
            },
        });

    });

    $('#payment-button').on('click', function(e) {

    });

    // Book Payment
    let months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
	let myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
    let date = new Date();
    let i    = 59;

    setInterval(() => {

        date.setHours(23);
        date.setMinutes(59);
        date.setSeconds(i--);

        $('#payment-limit-time').text(`${date.getHours()} : ${date.getMinutes()} : ${date.getSeconds()}`);

        if (date.toLocaleTimeString() == '00.00.00') {
            // Pembayaran Gagal
        }

    }, 1000);

    let paymentLimitText = `${myDays[date.getDay() + 1] ?? myDays[0]}, ${date.getDate()} ${months[date.getMonth() - 1]} ${date.getFullYear()}`;

    $('#payment-limit-date').text(paymentLimitText);

    // Petunjuk Pembayaran
    $('.payment-instructions > div:last-child').hide();
    $('.payment-instructions').on('click', function() {
        let caretRight = $(this).children('div:first-child').children().children();

        let briMobileBankingPaymentInstructions  = `<div class="mt-4 px-15">`;
            briMobileBankingPaymentInstructions += `<div>`;
            briMobileBankingPaymentInstructions += `<ul class="text-grey">`;
            briMobileBankingPaymentInstructions += `<li>1. Log in BRI Mobile Banking (unduh versi terbaru)</li>`;
            briMobileBankingPaymentInstructions += `<li>2. Pilih menu “PEMBAYARAN”</li>`;
            briMobileBankingPaymentInstructions += `<li>3. Pilih “BRIVA”</li>`;
            briMobileBankingPaymentInstructions += `<li>4. Masukan nomor BRI Virtual Account Anda dan jumlah pembayaran</li>`;
            briMobileBankingPaymentInstructions += `<li>5. Masukan Nomor Pin Anda</li>`;
            briMobileBankingPaymentInstructions += `<li>6. Tekan “OK” untuk melanjutkan transaksi Anda</li>`;
            briMobileBankingPaymentInstructions += `<li>7. Transaksi berhasil</li>`;
            briMobileBankingPaymentInstructions += `<li>8. Konfirmasi sms akan masuk ke nomor telepon Anda</li>`;
            briMobileBankingPaymentInstructions += '</div>';
            briMobileBankingPaymentInstructions += `</div>`;

        // if ($(this).data('bank') == 'bri-mobile-banking') {
        //     $(this).append(briMobileBankingPaymentInstructions);
        // }
        // $(this).children().last().toggle();
        $(this).children().first().toggleClass('bg-grey');
        $(this).children().last().slideToggle('slow');
        caretRight.removeClass('fa-caret-right').addClass('fa-caret-down');
    });

    // Buku
    $('.book').on('click', function() {
        location.href = `http://smartperpus.com/books/${$(this).data('id')}`;
    });

    // Add to wishlist
    $('.add-to-wishlist').on('click', function(e) {
        e.stopPropagation();

        if ($(this).children('i').hasClass('far')) {
            $(this).children('i').removeClass('far').addClass('fas')
        }
        else {
            $(this).children('i').removeClass('fas').addClass('far');
        }
    });
}); // End of onload Event

function alertError(message) {
    alert(message)
}
