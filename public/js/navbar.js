// JS untuk Navbar
"use strict";

function removeContent(element) {
    $(element).remove();
}

function alertError(message) {
    alert(message)
}

function toggle(selectorTarget) {
    $(selectorTarget).toggle();
}

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

    // Search Buku dan Author ( ada di Navbar )
    $('.keywords').on('keyup', function () {
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
                    'search_value': $('.keywords').val()
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
                    'keywords'     : $('.keywords').val(),
                },
                success : function (response) {
                    $('#book-search').html(response.books);
                    $('#search-text').html($('.keywords').val());

                    for (const key in response.bookCategory) {
                        if (response.bookCategory.hasOwnProperty.call(response.bookCategory, key)) {
                            const value = response.bookCategory[key];
                            $('#book-categories').html(`<div class="c-p">${key} (${value})</div>`);
                        }
                    }

                    // Merubah parameter URL tanpa reload
                    history.pushState({}, null,
                      `http://smartperpus.com/search/books?keywords=${$('.keywords').val()}&page=${$('.p-active').text()}`);
                },
            });

        }
    });

    $('.keywords').on('blur', function() {
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

    $('#click-to-the-top').on('click', function (e) {
        e.stopPropagation();
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
                        errorLogin += `<strong>${response.message}</strong>`;
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
                $('.book').css('width', '22.52%');
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
            `http://smartperpus.com/search/books?keywords=${$('.keywords').val()}&page=${parseInt(activePage.text()) + 1}`
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
            `http://smartperpus.com/search/books?keywords=${$('.keywords').val()}&page=${parseInt(activePage.text()) - 1}`
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

    // Book Buy - Event Change Provinsi
    $('.provinsi').on('change', function() {
        let form        = $(this).parents('form')[0];
        let formData    = new FormData(form);

        $.ajax({
            type       : "POST",
            url        : "/ajax/request/change-province",
            data       : formData,
            dataType   : "JSON",
            processData: false,
            cache      : false,
            contentType: false,
            success    : function (response) {
                let citiesAndDistrictsHtml = '';
                let districtsHtml          = '';

                response.cities.forEach(city => {
                    citiesAndDistrictsHtml += `<option value="${city.id}">${city.name}</option>`;
                });

                $('.kota-atau-kabupaten').html(citiesAndDistrictsHtml);

                response.districts.forEach(district => {
                    districtsHtml += `<option value="${district.id}">${district.name}</option>`;
                });

                $('.kecamatan').html(districtsHtml);
            },
        });
    })

    // Book Buy - Event Change City
    $('.kota-atau-kabupaten').on('change', function() {
        let form        = $(this).parents('form')[0];
        let formData    = new FormData(form);

        $.ajax({
            type       : "POST",
            url        : "/ajax/request/change-city",
            data       : formData,
            dataType   : "JSON",
            processData: false,
            cache      : false,
            contentType: false,
            success    : function (response) {
                let districtsHtml = '';

                response.districts.forEach(district => {
                    districtsHtml += `<option value="${district.id}">${district.name}</option>`;
                });

                $('.kecamatan').html(districtsHtml);
            },
        });

    })

    // Show All Synopsis
    $('#book-synopsis-toggle-button').on('click', function() {
        let synopsis = $('#synopsis > p');

        $('#book-synopsis-show').toggle();
        $(this).appendTo(synopsis);
        toggleText(this , 'Lihat Semua....', 'Sembunyikan....');
    });

    // End of Book Buy

    // Tulis Catatan
    $('#btn-write-notes').on('click', function() {
        $(this).hide();
        $('#input-write-notes').removeClass('d-none');

        $('#write-notes-cancel').on('click', function() {
            $('#btn-write-notes').show();
            $('#input-write-notes').addClass('d-none');
        });
    });

    $('#write-notes-cancel').on('click', function() {
        $('#write-note').val('');
    });

    // Cek API Rajaongkir
    $('.courier-choise').on('click', function() {

        let dataCustomerLength = $('.data-customer').length;

        if (dataCustomerLength != 0) {
            // Loading Couriers - Membuat loading saat layanan kurir belum muncul
            let courierServices = $('.courier-choise-service').length;
            let spinnerHtml     = `<div class="mr-4 pr-3 py-4 d-flex justify-content-center">`;
                spinnerHtml    += `<div class="spin"></div>`;
                spinnerHtml    += `</div>`;

            $('#courier-choise-name').html('');
            $('#courier-choise-result').html(spinnerHtml);
            // End of Loading Couriers

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
                            courierChoiseHtml += `<span class="courier-service-name tbold">${description}</span>`;
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
                            courierServicePriceHtml += `<div id="shipping-cost">${rupiahFormat(shippingCost)}</div>`;
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
                        $('#book-total-payment').val(totalPayment);
                    });
                },
            });
        }
        else {
            let errorMsg = `<span class="tred" role="alert"><strong>Harap tambah alamat</strong></span>`;

            scrollToElement('#alamat-pengiriman', 500);
            $('#alamat-pengiriman > span').remove();
            $('#alamat-pengiriman').append(errorMsg);
        }
    });

    $('#book-payment-form').on('submit', function(e) {
        e.preventDefault();
        let bookId             = $(this).data('id');
        let courierServiceCost = $('.inp-courier-choise-service:checked').val()
        let courierServiceName = $('.inp-courier-choise-service:checked').next().text();
        let paymentMethod      = $('.inp-payment-method:checked').val();
        let address            = $('.customer-address:checked').val();
        let uniqueCode         = randomIntFromInterval(100, 999);

        if (courierServiceCost === undefined || paymentMethod === undefined || address === undefined) {
        }

        $('#book-amount').val($('#jumlah-barang').text());
        $('#book-courier-name').val($('#courier-choise-name').text());
        $('#book-courier-service').val(courierServiceName);
        $('#book-shipping-cost').val(courierServiceCost);
        $('#book-note').val($('#write-note').val());
        $('#book-insurance').val($('#shipping-insurance:checked').val());
        $('#book-unique-code').val(uniqueCode);
        $('#book-payment-method').val(paymentMethod);
        $('#book-customer-address').val($('.customer-address:checked').val());

        ajaxForm('POST', this, `/book-purchases/${bookId}`, function(response) {
            if (response.errors) {
                let errorMsgs = '';

                for (const key in response.errors) {
                    if (response.errors.hasOwnProperty.call(response.errors, key)) {
                        const error = response.errors[key][0];

                        errorMsgs += `<div><strong>${error}</strong></div>`;
                    }
                }

                $('#click-to-the-top').trigger('click');
                $('#pesan').removeClass('d-none');
                $('#pesan').html(errorMsgs);
            }
            else {
                window.location.href = response.url;
            }
        });

    });

    // Book Payment
    let pathName      = window.location.pathname;
    let regexMatch    = /\/book-purchases\/[0-9]{1}/;
    let regexPathName = pathName.match(regexMatch);

    let userId        = $('#payment-limit-date').data('id');
    let months        = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let myDays        = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
    let date          = new Date();
    let i             = 59;

    ajaxBookPurchaseDeadlineDestroy();

    if (regexPathName) {
        getPaymentDeadlineText(userId);
    }

    let paymentLimitText = `${myDays[date.getDay() + 1] ?? myDays[0]}, ${date.getDate() + 1} ${months[date.getMonth()]} ${date.getFullYear()}`;
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

    // Chat dengan admin
    let btnChatclickedCount = 0;
    $('#btn-chat').on('click', function() {
        btnChatclickedCount++;

        $('.chat-content').show();
        $('.chat-with-admin').show();
        $(this).hide();

        if (btnChatclickedCount == 1) {
            $('.chattings').scrollTop($(document).height());
        }

        $('.type-message-input').trigger('focus');
    });

    $('#btn-chat-exit').on('click', function() {
        $('.chat-content').hide();
        $('.chat-with-admin').hide();
        $('#btn-chat').show();

    });

    $('#dropdown-navbar').on('click', function() {
        $('.responsive-navbar').slideToggle('fast');
    })

    // Chat
    const responseChats = (response) => {
        $('.chattings > div').append(response.chat);
        $('.type-message-input').val('');
        $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
    }

    // Chat dengan user - AdminChat Store
    $('#admin-chats-store-form').on('submit', function(e) {
        e.preventDefault();

        let userClickedId   = $('.user-chat-clicked').data('id');
        let inputValue      = $('.type-message-input').val();

        if (inputValue != '' && userClickedId !== undefined) {
            $.ajax({
                type: "POST",
                url: "/admin-chats",
                data: {
                    _token : csrfToken,
                    message: inputValue,
                    userId : userClickedId,
                },
                dataType: "JSON",
                success: function (response) {
                    responseChats(response);
                },
                error : function(response) {
                    console.log(response.responseJSON);
                }
            });
        }
    });

    // Chat dengan admin - UserChat Store
    $('#user-chats-store-form').on('submit', function(e) {
        e.preventDefault();

        let inputValue      = $('.type-message-input').val();

        if (inputValue != '') {
            ajaxForm('POST', '#user-chats-store-form', '/user-chats', function(response) {
                responseChats(response);
            });
        }
    });

    // Chat dengan user - AdminChat Store
    $('.user-chat').on('click', function() {
        let dataId = $(this).data('id');

        $(this).prevAll().removeClass('user-chat-clicked');
        $(this).nextAll().removeClass('user-chat-clicked');
        $(this).addClass('user-chat-clicked');
        $('.type-message-input').trigger('focus');

        $.ajax({
            type    : "GET",
            url     : `/user-chats/${dataId}`,
            data    : { userId : dataId },
            dataType: "JSON",
            success : function (response) {
                console.log(response);

                $('.chattings > div').html(response.userChatsHtml);
                $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
            },
            error : function(response) {
                console.log(response.responseJSON);
            }
        });
    });
    // End of Chat

    // Book Show
    $('#book-show-delete').on('click', function(e) {
        e.preventDefault();
        confirm('Apakah anda yakin ingin menghapus semua data pada buku ini ?') ? $(this).parent().trigger('submit') : console.log(false);
    });

    // User Index - Daftar karyawan
    $('.user-block').on('click', function(e) {

        let thisButton = $(this);

        e.preventDefault();

        if ($(this).text() == 'Blokir') {
            if (confirm('Apakah anda yakin ingin menblok user tersebut ?')) {
                $.ajax({
                    type: "POST",
                    url: `/users/${$(this).parent().data('id')}/block`,
                    data: {
                        '_token'     : csrfToken,
                        'userBlock' : true,
                    },
                    dataType: "JSON",
                    success: function (response) {
                        $('#pesan').show().removeClass('d-none').addClass('d-block').children('strong').text(response.pesan);
                        $(thisButton).text('Lepas Blokir');
                        $(thisButton).parent().parent().parent().addClass('text-grey tbold');
                    }
                });
            }
        }
        else {
            if (confirm('Apakah anda yakin ingin melepas blok user tersebut ?')) {
                $.ajax({
                    type: "POST",
                    url: `/users/${$(this).parent().data('id')}/restore`,
                    data: {
                        '_token'           : csrfToken,
                        'userRestoreBlock' : true,
                    },
                    dataType: "JSON",
                    success: function (response) {
                        $('#pesan').show().removeClass('d-none').addClass('d-block').children('strong').text(response.pesan);
                        $(thisButton).text('Blokir');
                        $(thisButton).parent().parent().parent().removeClass('text-grey tbold');
                    }
                });
            }
        }


    });

    // User Delete / Destroy
    $('.user-delete').on('click', function(e) {
        e.preventDefault();

        if (confirm('Apakah anda yakin ingin menghapus user tersebut secara permanen ?')) {
            $(this).parent().parent().parent().remove();

            $.ajax({
                type: "POST",
                url: `/users/${$(this).parent().data('id')}`,
                data: {
                    '_token'     : csrfToken,
                    '_method'    : 'DELETE',
                    'userDelete' : true,
                },
                dataType: "JSON",
                success: function (response) {
                    $('#pesan').show().removeClass('d-none').addClass('d-block').children('strong').text(response.pesan);
                }
            });
        }
    });

    // User Edit / Update
    $('#user-edit-form').on('submit', function(e) {
        e.preventDefault();
        $('#click-to-the-top').trigger('click');

        let form        = $(this)[0];
        let formData    = new FormData(form);

        let confirmText = 'Apakah anda yakin ingin men-update user tersebut ?';

        if (confirm(confirmText)) {
            $.ajax({
                type       : "POST",
                url        : `/users/${$(this).data('id')}`,
                data       : formData,
                processData: false,
                cache      : false,
                contentType: false,
                dataType   : "JSON",
                success    : function (response) {
                    $('#pesan').removeClass('d-none');


                    if (response.status === 'fail') {
                        let pesan = '';

                        for (const key in response.pesan) {
                            if (response.pesan.hasOwnProperty.call(response.pesan, key)) {
                                const element = response.pesan[key];

                                pesan += `<div><i class="fas fa-exclamation-triangle"></i> ${element[0]}</div>`;
                            }
                        }

                        $('#pesan strong').html(pesan);
                    }
                    else {
                        $('#pesan strong').text(response.pesan);
                    }
                }
            });
        }
    });

    // User Delete Photo Profile
    $('#user-destroy-photo-profile-form').on('submit', function(e) {
        e.preventDefault();

        let confirmText = 'Apakah anda yakin ingin mengapus foto profil anda ?';
        let userId      = $(this).data('id');
        let form        = $(this)[0];
        let formData    = new FormData(form);

        if (confirm(confirmText)) {
            $.ajax({
                type       : "POST",
                url        : `/users/${userId}/destroyPhotoProfile`,
                enctype    : 'multipart/form-data',
                data       : formData,
                processData: false,
                cache      : false,
                contentType: false,
                dataType   : "JSON",
                success    : function (response) {
                    console.log(response);
                },
                error    : function(errors) {
                    console.log(errors);
                }
            });
        }
    });

    // Book Edit - Stuck
    if ($('.book-edit-inp').val() !== '') {
        $('#book-edit-submit').addClass('active-login');
    }

    keyUpToggleFormButton('.book-edit-inp');

    $('#book-edit-submit').on('click', function(e) {
        e.preventDefault();

        $('#click-to-the-top').trigger('click');

        $.ajax({
            type: "POST",
            url: `/books/${$('#book-edit-form').data('id')}`,
            data: {
                '_token'              : csrfToken,
                '_method'             : 'PATCH',
                'isbn'                : $('#isbn').val(),
                'nama_penulis'        : $('#nama_penulis').val(),
                'judul_buku'          : $('#judul_buku').val(),
                'price'               : $('#price').val(),
                'tambah_diskon'       : $('#tambah_diskon').val(),
                'sinopsis'            : $('#sinopsis').val(),
                'jumlah_barang'       : $('#jumlah_barang').val(),
                'kategori'            : $('#kategori').val(),
                'tersedia_dalam_ebook': $('#tersedia_dalam_ebook').val(),
                'jumlah_halaman'      : $('#jumlah_halaman').val(),
                'tanggal_rilis'       : $('#tanggal_rilis').val(),
                'penerbit'            : $('#penerbit').val(),
                'subtitle'            : $('#subtitle').val(),
                'berat'               : $('#berat').val(),
                'lebar'               : $('#lebar').val(),
                'panjang'             : $('#panjang').val(),
                'gambar_sampul_buku'  : $('#gambar_sampul_buku').val(),
            },
            dataType: "JSON",
            success: function (response) {
                $('#pesan').removeClass('d-none');

                if (!response.status) {
                    let pesan = ``;

                    for (const key in response.pesan) {
                        if (response.pesan.hasOwnProperty.call(response.pesan, key)) {
                            const element = response.pesan[key];

                             pesan += `<div>${element[0]}</div>`;
                        }
                    }
                    $('#pesan').html(pesan);
                }
                else { // Update sukses
                    $('#pesan').html(response.pesan);
                }

            },
        });
    })

    // User Change Password
    $('#user-change-password-form').on('submit', function(e) {
        e.preventDefault();

        let userId      = $(this).data('id');
        let form        = $(this)[0];
        let formData    = new FormData(form);

        $.ajax({
            type: 'POST',
            url: `/users/${userId}/change-password`,
            data: formData,
            processData: false,
            cache      : false,
            contentType: false,
            dataType: "JSON",
            success: function (response) {
                $('#click-to-the-top').trigger('click');

                if (response.status === 'fail') {
                    let pesan = '';

                    $('#pesan').removeClass('d-none');

                    for (const key in response.pesan) {
                        if (response.pesan.hasOwnProperty.call(response.pesan, key)) {
                            const element = response.pesan[key];

                            pesan += `<div><i class="fas fa-exclamation-triangle"></i> ${element[0]}</div>`;
                        }
                    }

                    $('#pesan strong').html(pesan);
                }
                else {
                    sessionStorage.setItem('pesan', 'Berhasil mengubah password');
                    history.back();
                }

            }
        });
    });


    if (sessionStorage.getItem('pesan') !== null) {
        $('#pesan strong').text(sessionStorage.getItem('pesan'));
        $('#pesan').removeClass('d-none');
    }

    sessionStorage.clear();

    // Book Create
    // $('#book-create-submit').on('click', function(e) {
    //     e.preventDefault();

    //     $.ajax({
    //         type: "POST",
    //         url: `/books`,
    //         data: {
    //             '_token'            : csrfToken,
    //             'gambar_sampul_buku': $('#gambar_sampul_buku').val(),
    //         },
    //         dataType: "JSON",
    //         success: function (response) {
    //             console.log(response);
    //         }
    //     });
    // });

    // Customer Create
    $('#customer-store').on('submit', function(e) {
        e.preventDefault();

        let alamatWajibDisi = $('#customer-store-empty').length;

        $('.validate-error').text('');

        if (alamatWajibDisi == 1) {
            $('#customer-store-empty').hide();
        }

        ajaxForm('POST', '#customer-store', `/customers`, function(response) {

            if (response.status) {
                let dataCustomers = $('.data-customer').length + 1;

                $('.close').trigger('click');
                $('#data-customers').append(response.dataCustomer);

                if (dataCustomers == 5) {
                    $('#customer-store-modal-trigger').hide();
                    scrollToElement($('.data-customer').last(), 500);
                }
                else {
                    $('#alamat-pengiriman > span').remove();
                    scrollToElement($('.data-customer').last(), 500);
                }

                customerUpdate();
                customerDestroy();
            }
            else {
                for (const key in response.errorMsg) {
                    if (response.errorMsg.hasOwnProperty.call(response.errorMsg, key)) {
                        const element = response.errorMsg[key];

                        $('.error_' + key).text(element[0]);
                    }
                }
            }

        });
    });

    $('#customer-store-modal-trigger').on('click', function() {
        $('#customer-store').each(function(){
            $(this).find(':input[type=text]').val('');
            $(this).find(':input[type=number]').val('');
        });
    });

    // Customer Update
    customerUpdate();

    // Customer Destroy
    customerDestroy();

}); // End of onload Event
