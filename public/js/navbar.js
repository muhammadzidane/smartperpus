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
            url: "/ajax/request/first-load",
            data: {
                '_token': csrfToken,
                'page': getUrlParameter('page'),
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
        } else {
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

    $('.search-form button').on('click', function (e) {
        if (window.location.pathname == '/search/books') {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/ajax/request/search",
                data: {
                    '_token': csrfToken,
                    'keywords': $('.keywords').val(),
                },
                success: function (response) {
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

    $('.keywords').on('blur', function () {
        $('#search-values').hide();
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
                url: "/ajax/request/check-login",
                data: {
                    '_token': csrfToken,
                    'email': $('#email').val(),
                    'password': $('#password').val(),
                },
                success: function (response) {
                    let errorLogin = '<div class="error tred small mb-2" role="alert">';
                    errorLogin += `<strong>${response.message}</strong>`;
                    errorLogin += '</div>';

                    if (!response.success) {
                        $('#error-login').first().html((errorLogin));
                    } else {
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
    $(document).on('keyup', function (e) {
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
    $('.min-max-value').on('click', function (e) {
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
    $('.filter-star-search').on('click', function () {
        $('.click-to-the-top').trigger('click');

        $('#modal-filter').modal('hide');

        appendFilter('.rating-4-plus',
            [`Bintang 4 Keatas`, 4, 'rating-4-plus']
        )

        ajaxFilterDataBooks();
    });

    // Sort buku
    $('#sort-books').on('change', function () {
        let min_price_val = $('.filter-min-price').data('filter-value');
        let max_price_val = $('.filter-max-price').data('filter-value');
        let sortBookVal = $('#sort-books').val();
        let sortBookSelectedText = $('#sort-books option:checked').text();

        appendFilter('.filter-sort',
            [`${sortBookSelectedText}`, $('#sort-books option:checked').val(), 'filter-sort']
        )

        $.ajax({
            type: "POST",
            url: "/ajax/request/filter-search",
            data: {
                '_token': csrfToken,
                'min_price': min_price_val,
                'max_price': max_price_val,
                'star_value': $('.rating-4-plus').length == 0 ? null : $('.filter-star-search').data('filter-star'),
                'sort_book_value': sortBookVal,
                'keywords': getUrlParameter('keywords'),
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
        type: "POST",
        url: "/ajax/request/pagination-data",
        data: {
            '_token': csrfToken,
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

    $('#pagination-number').on('click', function (e) {
        $('#book-search').html('');
        let pageNumber = $(e.target).text();


        $.ajax({
            type: "POST",
            url: "/ajax/request/pagination",
            data: {
                '_token': csrfToken,
                'page': pageNumber,
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
        } else if (pageNumber >= 2) {
            $('#pagination-prev').show();
        }
    });

    // Pagination Next
    $('#pagination-next').on('click', function () {
        let activePage = $('.p-active');
        let paginationNth1 = $('#pagination-number div:nth-child(1)');
        let paginationNth2 = $('#pagination-number div:nth-child(2)');
        let paginationNth3 = $('#pagination-number div:nth-child(3)');
        let paginationNth4 = $('#pagination-number div:nth-child(4)');
        let lastPagination = $('#pagination-number').children().last();

        if (activePage.text() >= 5 && activePage.text() != lastPagination.text()) {
            paginationNth2.text('...');
            paginationNth1.text(1);

            if ((paginationNth3.hasClass('p-active') || paginationNth4.hasClass('p-active')) && paginationNth2.text() == '...') {
                activePage.next().trigger('click');
            } else {
                activePage.text(parseInt(activePage.text()) + 1).trigger('click');
                paginationNth4.text(activePage.text() - 1);
                paginationNth3.text(activePage.text() - 2);
            }

            if (activePage.text() == lastPagination.text()) {
                lastPagination.prev().addClass('p-hide').hide();
                lastPagination.addClass('p-hide').hide();
            }
        } else {
            activePage.next().trigger('click');
        }

        window.history.pushState({}, null,
            `http://smartperpus.com/search/books?keywords=${$('.keywords').val()}&page=${parseInt(activePage.text()) + 1}`
        );

    });

    $('#pagination-prev').on('click', function () {
        let activePage = $('.p-active');
        let paginationNth2 = $('#pagination-number div:nth-child(2)');
        let paginationNth3 = $('#pagination-number div:nth-child(3)');
        let paginationNth4 = $('#pagination-number div:nth-child(4)');
        let paginationNth5 = $('#pagination-number div:nth-child(5)');

        if (paginationNth4.hasClass('p-active') || paginationNth5.hasClass('p-active')) {
            activePage.prev().trigger('click');
        }

        if (paginationNth3.hasClass('p-active') && paginationNth2.text() == '...') {
            paginationNth3.text(parseInt(activePage.text() - 1)).trigger('click');
            paginationNth4.text(parseInt(paginationNth4.text()) - 1);
            paginationNth5.text(parseInt(paginationNth5.text()) - 1);
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
    $('#plus-one-book').on('click', function (e) {
        if ($('#total-book').text() == 0) {
            e.preventDefault();
        } else {
            $('#book-needed').text(parseInt($('#book-needed').text()) + 1);
            $('#jumlah-barang').text(parseInt($('#jumlah-barang').text()) + 1);
            $('#total-book').text(parseInt($('#total-book').text()) - 1);
        }

        checkShippingInsurance();
    });

    $('#sub-one-book').on('click', function (e) {

        if ($('#book-needed').text() == 1) {
            e.preventDefault();
        } else {
            $('#jumlah-barang').text(parseInt($('#jumlah-barang').text()) - 1);
            $('#book-needed').text(parseInt($('#book-needed').text()) - 1);
            $('#total-book').text(parseInt($('#total-book').text()) + 1);
        }

        checkShippingInsurance();
    });

    $('#shipping-insurance').on('click', function () {
        checkShippingInsurance();
    });

    // Book Buy - Event Change Provinsi
    $('.provinsi').on('change', function () {
        let form = $(this).parents('form')[0];
        let formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: "/ajax/request/change-province",
            data: formData,
            dataType: "JSON",
            processData: false,
            cache: false,
            contentType: false,
            success: function (response) {
                let citiesAndDistrictsHtml = '';
                let districtsHtml = '';

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
    $('.kota-atau-kabupaten').on('change', function () {
        let form = $(this).parents('form')[0];
        let formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: "/ajax/request/change-city",
            data: formData,
            dataType: "JSON",
            processData: false,
            cache: false,
            contentType: false,
            success: function (response) {
                let districtsHtml = '';

                response.districts.forEach(district => {
                    districtsHtml += `<option value="${district.id}">${district.name}</option>`;
                });

                $('.kecamatan').html(districtsHtml);
            },
        });

    })

    // Show All Synopsis
    $('#book-synopsis-toggle-button').on('click', function () {
        let synopsis = $('#synopsis > p');

        $('#book-synopsis-show').toggle();
        $(this).appendTo(synopsis);
        toggleText(this, 'Lihat Semua....', 'Sembunyikan....');
    });

    // End of Book Buy

    // Tulis Catatan
    $('#btn-write-notes').on('click', function () {
        $(this).hide();
        $('#input-write-notes').removeClass('d-none');

        $('#write-notes-cancel').on('click', function () {
            $('#btn-write-notes').show();
            $('#input-write-notes').addClass('d-none');
        });
    });

    $('#write-notes-cancel').on('click', function () {
        $('#write-note').val('');
    });

    // Cek API Rajaongkir
    $('.courier-choise').on('click', function () {

        let dataCustomerLength = $('.data-customer').length;

        if (dataCustomerLength != 0) {
            // Loading Couriers - Membuat loading saat layanan kurir belum muncul
            let courierServices = $('.courier-choise-service').length;
            let spinnerHtml = `<div class="mr-4 pr-3 py-4 d-flex justify-content-center">`;
            spinnerHtml += `<div class="spin"></div>`;
            spinnerHtml += `</div>`;

            $('#courier-choise-name').html('');
            $('#courier-choise-result').html(spinnerHtml);
            // End of Loading Couriers

            $('input[name=courier-choise]').removeAttr('checked');
            $(this).children('input').attr('checked', 'checked');
            $('.courier-choise').removeClass('cc-active');
            $(this).addClass('cc-active');

            let originSubdistictId = $('#origin').data('subdistrict-id');
            let originType = $('#origin').data('origin-type');
            let destinationSubdistrictId = $('#destination').data('subdistrict-id');
            let destinationType = $('#destination').data('destination-type');
            let weight = $('#weight').data('weight');
            let courierChoise = $('input[name=courier-choise]:checked').val();

            $.ajax({
                type: "POST",
                url: "/ajax/request/cek-ongkir",
                data: {
                    '_token': csrfToken,
                    'origin_id': originSubdistictId,
                    'origin_type': originType,
                    'destination_id': destinationSubdistrictId,
                    'destination_type': destinationType,
                    'weight': weight,
                    'courier': courierChoise,
                },
                dataType: "JSON",
                success: function (response) {
                    let result = response.rajaongkir.results[0];

                    $('#courier-choise-name').text(result.name);
                    $('#courier-choise-result').html('');

                    let courierChoiseHtml = function (description, etd, costPrice) {

                        // Jika tidak ada string Hari pada etd, maka tambahkan string 'Hari'
                        let path = /(HARI|JAM)/i;
                        let regexResult = etd.match(path) ?? '';
                        etd = regexResult ? etd.replace(path, ucwords(regexResult[0].toLowerCase())) : etd = etd + ' Hari';

                        let courierChoiseHtml = `<div>`;
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

                    $('.courier-choise-service').on('click', function () {
                        $('.inp-courier-choise-service').removeAttr('checked');
                        $(this).children('input').attr('checked', 'checked');

                        let bookPrice = $('#book-price').data('book-price');
                        let shippingCost = $(this).children('input:checked').val() ?? 0;
                        let bookNeeded = parseInt($('#book-needed').text());
                        let shippingInsurance = $('#shipping-insurance').is(':checked') ? 1000 : 0;
                        let courierServicePriceHtml = `<div class="d-flex justify-content-between">`;
                        courierServicePriceHtml += `<div>Ongkos Kirim</div>`;
                        courierServicePriceHtml += `<div id="shipping-cost">${rupiahFormat(shippingCost)}</div>`;
                        courierServicePriceHtml += `</div>`;

                        if ($('#shipping-cost').length <= 0) {
                            $('#book-payment').append(courierServicePriceHtml);
                        } else {
                            $('#shipping-cost').text(shippingCost);
                        }

                        let totalPayment = (bookPrice * bookNeeded) + parseInt(shippingCost) + shippingInsurance;

                        $('#total-payment').attr('data-total-payment', totalPayment);
                        $('#total-payment').text(rupiahFormat(totalPayment));
                        $('#book-total-payment').val(totalPayment);
                    });
                },
            });
        } else {
            let errorMsg = `<span class="tred" role="alert"><strong>Harap tambah alamat</strong></span>`;

            scrollToElement('#alamat-pengiriman', 500);
            $('#alamat-pengiriman > span').remove();
            $('#alamat-pengiriman').append(errorMsg);
        }
    });

    $('#book-payment-form').on('submit', function (e) {
        e.preventDefault();
        let bookId = $(this).data('id');
        let courierServiceCost = $('.inp-courier-choise-service:checked').val()
        let courierServiceName = $('.inp-courier-choise-service:checked').next().text();
        let paymentMethod = $('.inp-payment-method:checked').val();
        let address = $('.customer-address:checked').val();
        let uniqueCode = randomIntFromInterval(100, 999);

        if (courierServiceCost === undefined || paymentMethod === undefined || address === undefined) {}

        $('#book-amount').val($('#jumlah-barang').text());
        $('#book-courier-name').val($('#courier-choise-name').text());
        $('#book-courier-service').val(courierServiceName);
        $('#book-shipping-cost').val(courierServiceCost);
        $('#book-note').val($('#write-note').val());
        $('#book-insurance').val($('#shipping-insurance:checked').val());
        $('#book-unique-code').val(uniqueCode);
        $('#book-payment-method').val(paymentMethod);
        $('#book-customer-address').val($('.customer-address:checked').val());

        ajaxForm('POST', this, `/book-purchases/${bookId}`, function (response) {
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
            } else {
                window.location.href = response.url;
            }
        });

    });

    // Book Payment
    let pathName = window.location.pathname;
    let regexMatch = /\/book-purchases\/[0-9]{1}/;
    let regexPathName = pathName.match(regexMatch);

    let userId = $('#payment-limit-date').data('id');
    let months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
    let date = new Date();
    let i = 59;

    ajaxBookPurchaseDeadlineDestroy();

    if (regexPathName) {
        getPaymentDeadlineText(userId);
    }

    let paymentLimitText = `${myDays[date.getDay() + 1] ?? myDays[0]}, ${date.getDate() + 1} ${months[date.getMonth()]} ${date.getFullYear()}`;
    $('#payment-limit-date').text(paymentLimitText);

    // Petunjuk Pembayaran
    $('.payment-instructions > div:last-child').hide();
    $('.payment-instructions').on('click', function () {
        let caretRight = $(this).children('div:first-child').children().children();

        let briMobileBankingPaymentInstructions = `<div class="mt-4 px-15">`;
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
    $('.book').on('click', function () {
        window.location.href = $(this).find('.book-show-link').attr('href');
    });

    //  Wishlist Store - Add to wishlist
    $('.add-to-my-wishlist').on('click', function (event) {
        event.stopPropagation();
        let wishlists = $(document).find('.add-to-my-wishlist');
        let wishlistTargets    = [];

        for (const key in wishlists) {
            if (wishlists.hasOwnProperty.call(wishlists, key)) {
                const element = wishlists[key];
                let wishlist = $(element);

                if (wishlist) {
                    wishlistTargets.push(wishlist);
                }
            }
        }

        let clickedDataId = $(this).data('id') ?? 0;

        wishlistTargets = wishlistTargets.filter((wishlist) => {
            return wishlist.data('id') == clickedDataId;
        });

        let dataId  = $(this).parents('.book').data('id');
        let datas   = {
            _token: csrfToken,
            bookId: dataId,
        };

        if ($(this).hasClass('far')) {
            if (wishlistTargets.length != 0) {
                wishlistTargets.forEach(element => {
                    element.removeClass('far');
                    element.addClass('fas');
                })
            }
            console.log(wishlistTargets.length);

            ajaxJson('POST', '/wishlist', datas);
        } else {
            if (wishlistTargets.length != 0) {
                wishlistTargets.forEach(element => {
                    element.removeClass('fas');
                    element.addClass('far');
                })
            }
            datas['_method'] = 'DELETE';

            ajaxJson('POST', `/wishlist/${dataId}`, datas);
        }
    });

    // Chat dengan admin
    let btnChatclickedCount = 0;
    $('#btn-chat').on('click', function () {
        btnChatclickedCount++;

        $('.chat-content').show();
        $('.chat-with-admin').show();
        $('.click-to-the-top').addClass('d-none');
        $(this).hide();

        if (btnChatclickedCount == 1) {
            $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
        }

        $('.type-message-input').trigger('focus');
    });

    $('#btn-chat-exit').on('click', function () {
        $('.chat-content').hide();
        $('.chat-with-admin').hide();
        $('#btn-chat').show();
        $('.click-to-the-top').removeClass('d-none');
    });

    $('#dropdown-navbar').on('click', function () {
        $('.responsive-navbar').slideToggle('fast');
    })

    // Chat
    // Success Response AJAX
    const responseChats = (response) => {
        $('.type-message-input').val('');
        $('#user-chat-send-photo').val('');
        $('#user-send-img-cancel').trigger('click');
        $('#menu-chattings').append(response.chat);
        $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
    }

    // Chat dengan user - AdminChat Store
    $('#admin-chats-store-form').on('submit', e => {
        e.preventDefault();

        let photoValue       = $('#user-chat-send-photo').val()
        let inputValue       = $('.type-message-input').val()
        let imageValueOrText = $('.type-message-input').val() == '' ? 'Mengirim gambar...' : $('.type-message-input').val();
        let userClickedId    = $('.user-chat-active').length;
        let userChatActiveId = $('.user-chat-active').data('id');
        let datas            = [
            {
                userId: userChatActiveId,
            }
        ];

        if (inputValue != '' && userClickedId != 0 || photoValue != "") {
            ajaxForm('POST', '#admin-chats-store-form', '/user-chats', response => {
                responseChats(response);
                $('.user-chat-active').children().last().find('span').text(imageValueOrText);
                $('.user-chats-text').text(imageValueOrText);
            }, datas);
        }
    });

    // Chat dengan admin - UserChat Store
    $('#user-chats-store-form').on('submit', e => {
        e.preventDefault();

        let inputValue = $('.type-message-input').val();
        let photo = $('#user-chat-send-photo').val();

        const userChatStore = () => {
            ajaxForm('POST', '#user-chats-store-form', '/user-chats', response => {
                responseChats(response);
            });
        }

        if (inputValue !== '' || photo != "") {
            userChatStore();
        }
    });

    // User Show - Admin melihat chat milik users
    const userChatClick = () => {
        $('.user-chat').on('click', function () {
            let dataId = $(this).data('id');
            let notifications = $(this).children('div').last().find($('.user-chat-notifications'));
            $(this).prevAll().removeClass('user-chat-active');
            $(this).nextAll().removeClass('user-chat-active');
            $(this).addClass('user-chat-active');
            $('.type-message-input').trigger('focus');

            if (window.matchMedia('(max-width: 768px)').matches) {
                $('#chat-back').addClass('d-block');
                $('#user-chats').hide();
            }

            $.ajax({
                type: "GET",
                url: `/user-chats/${dataId}`,
                data: {
                    userId: dataId,
                    adminClickShow: true
                },
                dataType: "JSON",
                success: response => {
                    let btnChatUnread = $('.btn-chat-unread');
                    let text = btnChatUnread.text() - notifications.children().first().text();

                    $('#menu-chattings').html(response.userChatsHtml);
                    btnChatUnread.text(text);
                    notifications.remove();

                    if (btnChatUnread.text() == 0) {
                        btnChatUnread.remove();
                    }

                    setTimeout(() => {
                        $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
                    }, 10);
                },
            });
        });
    }

    userChatClick();

    // Search user
    $('#chat-search-user').on('keyup', function () {
        let searchVal = $(this).val();
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (searchVal == '') {
            $('.chattings > div').html('');
        }

        ajaxJson('POST', `/user-chats/search`, {
            _token: csrfToken,
            searchVal: searchVal
        }, (response) => {
            $('.user-chattings').html(response.userChatHtml);
            userChatClick();
        });
    });

    // Mengirim foto pada chat
    $('#user-chat-send-photo').on('click', (e) => {

        // Jika user tidak di klik, maka tidak bisa kirim gambar
        let authRole = $('.chat').data('role');

        if ($('.user-chat-active').length == 0 && authRole !== 'guest') {
            e.preventDefault();
        } else {
            $('#user-chat-send-photo').on('change', () => {
                let ext = $('#user-chat-send-photo').val().split('.').pop().toLowerCase();


                if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                    $('#user-send-img-cancel').trigger('click');
                    $('#user-chat-send-photo').val('');

                    showMessageChatting();
                } else {
                    $('#user-chat-send-img').addClass('d-block');
                    $('#user-chat-send-img-input').addClass('d-flex');
                    $('#menu-chattings').hide();
                    $('.chats-store-form').parent().hide();
                    $('.user-chat-img-information').trigger('focus');
                    $('.user-chat-img-information').val('');

                    let preview = document.getElementById('user-chat-img');
                    let file = document.getElementById('user-chat-send-photo').files[0];
                    let reader = new FileReader();

                    reader.onloadend = () => {
                        preview.src = reader.result;
                    }

                    if (file) {
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = "";
                    }
                }
            });
        }
    });

    // Batal mengirim foto
    $('#user-send-img-cancel').on('click', () => {
        $("#user-chat-send-photo").val(""); // Clear image value
        $('#user-chat-send-img').removeClass('d-block');
        $('#user-chat-send-img-input').removeClass('d-flex');
        $('#menu-chattings').show();
        $('.chattings').scrollTop($('.chattings')[0].scrollHeight);
        $('.chats-store-form').parent().show();
    });

    // Mengirim pesan Foto pada database
    $('#user-chat-store-send-img').on('click', () => {
        let message = $('.user-chat-img-information').val();
        $('.type-message-input').val(message);
        $('.chats-store-form').trigger('submit');
    });

    // User Chat Update - Mengupdate status notifikasi chat menjadi terbaca
    $('.btn-chat-guest').on('click', e => {
        let userId = $('.chattings').data('id');

        $('.btn-chat-unread').remove();

        ajaxJson('POST', `/user-chats/${userId}`, {
            _token: csrfToken,
            _method: 'PATCH',
            userId: userId
        }, () => {});
    });

    $('#chat-back').on('click', () => {
        $('#user-chats').show();
        $('.user-chat').removeClass('user-chat-active');
        $('#chat-back').removeClass('d-block');
    });

    // Menghapus pesan
    $('#chat-delete-button').on('click', () => $('.chat-delete-action').toggleClass('d-block'));
    $('#chat-delete-form').on('submit', e => {
        e.preventDefault();

        let userClickedLength = $('.user-chat-active').length;
        let userId = userClickedLength === 0 ? $('.chattings').data('id') : $('.user-chat-active').data('id');
        let text = 'Apakan anda yakin ingin menghapus semua pesan ?';

        if (confirm(text)) {
            ajaxForm('POST', '#chat-delete-form', `/user-chats/${userId}`, () => {
                let text = 'Berhasil menghapus semua pesan';

                $('#user-chats-error-image').text(text);
                showMessageChatting();
                $('#menu-chattings').children().remove();
                $('.user-chat-active').remove();
            });
        }

        $('#chat-delete-button').trigger('click');
    });
    // End Chat

    // Book Show
    $('#book-delete-modal').on('click', function(e) {
        e.preventDefault();
        let confirmText = 'Apakah anda yakin ingin menghapus semua data pada buku ini ?';

        modalConfirm(this, confirmText, (accepted) => {
            if (accepted) {
                $(this).parent().trigger('submit');
            }
        });
    });

    $('#book-show-wishlist').on('click', function() {
        let unClickedWishlist = $(this).find('i').hasClass('far');
        let dataId            = $(this).parents('#book-show').data('id');
        let datas             = {
            _token: csrfToken,
            bookId: dataId,
        };

        if (unClickedWishlist) {
            $(this).find('i').removeClass('far');
            $(this).find('i').addClass('fas');

            ajaxJson('POST', '/wishlist', datas);
        } else {
            $(this).find('i').removeClass('fas');
            $(this).find('i').addClass('far');
            datas['_method'] = 'DELETE';

            ajaxJson('POST', `/wishlist/${dataId}`, datas);
        }
    });

    // End Book Show


    //#region Book add stock - Tambah stok buku
    $('#book-add-stock').on('click', function() {
        let html =
        `<div class="modal fade" id="book-add-stock-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered p-5" role="document">
                <div class="modal-content modal-content-login">
                    <div class="px-3 mb-4 d-flex justify-content-between">
                        <h5 class="modal-title tred login-header">Tambah Stok</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
                        <form id="book-add-stock-form" method="post">
                            <input id="book-add-stock-input" class="form-control-custom"
                            type="number" name="stock" min="1" placeholder="Jumlah" autocomplete="off">
                            <div class="text-right mt-4">
                                <button class="btn-none tred-bold" data-dismiss="modal" aria-label="Close">Batal</button>
                                <button class="btn btn-red">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>`;

        let modalLength = $('#book-add-stock-modal').length;

        if (modalLength == 0) {
            $(this).after(html);
        }

        let validations = [
            {
                input: '#book-add-stock-input',
                inputName: 'Jumlah',
                rules: 'required,numeric',
            },
        ];

        $('#book-add-stock-input').on('keyup', () => validator(validations));
        $('#book-add-stock-form').on('submit', function(event) {
            event.stopImmediatePropagation();
            event.preventDefault();

            validator(validations, success => {
                if (success) {
                    let datas = [{
                        _token: csrfToken,
                        _method: 'PATCH',
                    }];
                    let dataId = $('#book-show').data('id');

                    ajaxForm('POST', '#book-add-stock-form', `/books/${dataId}/add-stock`, response => {
                        if (response.errors) {
                            let errors = response.errors.stock;

                            alert(errors);
                        } else {
                            $('.close').trigger('click');
                            $("#book-add-stock-form")[0].reset();

                            let messageText     = `Berhasil menambah ${response.stock} stock buku`;
                            let stockBookText   = $('#book-stock').text();
                            stockBookText       = parseInt(stockBookText) + parseInt(response.stock);

                            alertMessage(messageText);
                            $('#book-stock').text(stockBookText);
                        }
                    }, datas);
                };
            });
        });
    });
    //#endregion Book add stock - Tambah stok buku

    // User Index - Daftar karyawan
    $('.user-block').on('click', function (e) {

        let thisButton = $(this);

        e.preventDefault();

        if ($(this).text() == 'Blokir') {
            if (confirm('Apakah anda yakin ingin menblok user tersebut ?')) {
                $.ajax({
                    type: "POST",
                    url: `/users/${$(this).parent().data('id')}/block`,
                    data: {
                        '_token': csrfToken,
                        'userBlock': true,
                    },
                    dataType: "JSON",
                    success: function (response) {
                        $('#pesan').show().removeClass('d-none').addClass('d-block').children('strong').text(response.pesan);
                        $(thisButton).text('Lepas Blokir');
                        $(thisButton).parent().parent().parent().addClass('text-grey tbold');
                    }
                });
            }
        } else {
            if (confirm('Apakah anda yakin ingin melepas blok user tersebut ?')) {
                $.ajax({
                    type: "POST",
                    url: `/users/${$(this).parent().data('id')}/restore`,
                    data: {
                        '_token': csrfToken,
                        'userRestoreBlock': true,
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
    $('.user-delete').on('click', function (e) {
        e.preventDefault();

        if (confirm('Apakah anda yakin ingin menghapus user tersebut secara permanen ?')) {
            $(this).parent().parent().parent().remove();

            $.ajax({
                type: "POST",
                url: `/users/${$(this).parent().data('id')}`,
                data: {
                    '_token': csrfToken,
                    '_method': 'DELETE',
                    'userDelete': true,
                },
                dataType: "JSON",
                success: function (response) {
                    $('#pesan').show().removeClass('d-none').addClass('d-block').children('strong').text(response.pesan);
                }
            });
        }
    });

    // User Edit / Update
    $('#user-edit-form').on('submit', function (e) {
        e.preventDefault();
        $('#click-to-the-top').trigger('click');

        let form = $(this)[0];
        let formData = new FormData(form);

        let confirmText = 'Apakah anda yakin ingin men-update user tersebut ?';

        if (confirm(confirmText)) {
            $.ajax({
                type: "POST",
                url: `/users/${$(this).data('id')}`,
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                dataType: "JSON",
                success: function (response) {
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
                    } else {
                        $('#pesan strong').text(response.pesan);
                    }
                }
            });
        }
    });

    // User Delete Photo Profile
    $('#user-destroy-photo-profile-form').on('submit', function (e) {
        e.preventDefault();

        let confirmText = 'Apakah anda yakin ingin mengapus foto profil anda ?';
        let userId = $(this).data('id');
        let form = $(this)[0];
        let formData = new FormData(form);

        if (confirm(confirmText)) {
            $.ajax({
                type: "POST",
                url: `/users/${userId}/destroyPhotoProfile`,
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                dataType: "JSON",
                success: function (response) {
                    console.log(response);
                },
                error: function (errors) {
                    console.log(errors);
                }
            });
        }
    });

    // Book Edit
    // Keyup input
    const bookEditCustomValidationCondition = (inputId, data, formAction = '') => {
        if (inputId == '#isbn') {
            data['rules'] = 'required,numeric,digits:13';
        }

        if (inputId == '#tersedia_dalam_ebook') {
            data['rules'] = 'nullable';
        }

        if (inputId == '#gambar_sampul_buku') {
            if (formAction == 'EDIT') {
                data['rules'] = 'nullable,mimes:png|jpg|jpeg';
            } else {
                data['rules'] = 'required,mimes:png|jpg|jpeg';
            }
        }
    }

    let inputIdBookEdit = [
        '#nama_penulis', '#isbn', '#judul_buku', '#sinopsis',
        '#price', '#kategori', '#tersedia_dalam_ebook',
        '#jumlah_barang', '#penerbit', '#jumlah_halaman',
        '#tanggal_rilis', '#subtitle', '#berat', '#panjang',
        '#lebar', '#diskon',
    ];

    let inputIdBookEditJoin = inputIdBookEdit.join(', ');

    $(inputIdBookEditJoin).on('keyup', function() {
        let validations     = [];

        inputIdBookEdit.map(inputId => {
            let data = {
                input    : inputId,
                inputName: capitalizeFirstLetter(inputId.replace('#', '').replace('_', ' ')),
                rules    : 'required',
            }

            bookEditCustomValidationCondition(inputId, data);
            validations.push(data);
            validator(validations);
        });

    });

    // Submit
    $('#book-edit-form, #book-store-form').on('submit', function (e) {
        e.preventDefault();
        let dataId        = $(this).data('id');
        let validations   = [];
        let finds         = 'input[type=number], input[type=text], input[type=date], input[type=file], textarea';
        let inputs        = $(this).find(finds);
        let bookStoreForm = $(this).attr('id') == 'book-store-form';

        inputs.map((key, input) => {
            let inputId   = $(input).attr('id');
            let inputName = capitalizeFirstLetter(inputId.replaceAll('_', ' '));
            inputId       = `#${inputId}`;
            let data      = {
                input    : inputId,
                inputName: inputName,
                rules    : 'required',
            }

            if (bookStoreForm) {
                bookEditCustomValidationCondition(inputId, data);
            } else {
                bookEditCustomValidationCondition(inputId, data, 'EDIT');
            }

            validations.push(data);
        });

        validator(validations, success => {
            if (success) {
                if (bookStoreForm) {
                    ajaxForm('POST', this , `/books`, response => {
                        if (!response.errors) {
                            let message = 'Berhasil menambah buku';

                            alertMessage(message);
                            $(this).trigger("reset");
                            $('#book-show-image').attr('src', '');
                        } else {
                            let errorMessages = ``;

                            for (const key in response.errors) {
                                if (response.errors.hasOwnProperty.call(response.errors, key)) {
                                    const element = response.errors[key];

                                    errorMessages += `<div>${element}</div>`;
                                }
                            }

                            alertMessage(errorMessages);
                        }
                    });
                } else {
                    ajaxForm('POST', this, `/books/${dataId}`, response => {
                        if (!response.errors) {
                            let message = 'Berhasil mengedit buku';

                            alertMessage(message);
                        } else {
                            let errorMessages = ``;

                            for (const key in response.errors) {
                                if (response.errors.hasOwnProperty.call(response.errors, key)) {
                                    const element = response.errors[key];

                                    errorMessages += `<div>${element}</div>`;
                                }
                            }

                            alertMessage(errorMessages);
                        }
                    }, requestMethod('PATCH'));
                }
            }
        });
    })

    $('#gambar_sampul_buku').on('change', function() {
        let dataHref = $(this).data('href');
        let data = [
            {
                input    : this,
                inputName: 'Gambar sampul buku',
                rules    : 'nullable,mimes:png|jpg|jpeg',
            }
        ];

        validator(data,
            success => {
                if (success) {
                    changeInputPhoto('book-show-image', 'gambar_sampul_buku');
                } else {
                    $('#book-show-image').attr('src', dataHref);
                    $(this).val('');
                }
            }
        );
    });

    // User Change Password
    $('#user-change-password-form').on('submit', function (e) {
        e.preventDefault();

        let userId = $(this).data('id');
        let form = $(this)[0];
        let formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: `/users/${userId}/change-password`,
            data: formData,
            processData: false,
            cache: false,
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
                } else {
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

    // Customer Create
    $('#customer-store').on('submit', function (e) {
        e.preventDefault();

        let alamatWajibDisi = $('#customer-store-empty').length;

        $('.validate-error').text('');

        if (alamatWajibDisi == 1) {
            $('#customer-store-empty').hide();
        }

        ajaxForm('POST', '#customer-store', `/customers`, function (response) {

            if (response.status) {
                let dataCustomers = $('.data-customer').length + 1;

                $('.close').trigger('click');
                $('#data-customers').append(response.dataCustomer);

                if (dataCustomers == 5) {
                    $('#customer-store-modal-trigger').hide();
                    scrollToElement($('.data-customer').last(), 500);
                } else {
                    $('#alamat-pengiriman > span').remove();
                    scrollToElement($('.data-customer').last(), 500);
                }

                customerUpdate();
                customerDestroy();
            } else {
                for (const key in response.errorMsg) {
                    if (response.errorMsg.hasOwnProperty.call(response.errorMsg, key)) {
                        const element = response.errorMsg[key];

                        $('.error_' + key).text(element[0]);
                    }
                }
            }

        });
    });

    $('#customer-store-modal-trigger').on('click', function () {
        $('#customer-store').each(function () {
            $(this).find(':input[type=text]').val('');
            $(this).find(':input[type=number]').val('');
        });
    });

    // Customer Update
    customerUpdate();

    // Customer Destroy
    customerDestroy();

    // Waiting for payment - menunggu pembayaran
    // Unggah bukti pembayaran
    $('#upload-payment-file').on('change', function() {
        let preview      = document.getElementById('upload-payment-image');
        let file         = document.getElementById('upload-payment-file').files[0];
        let reader       = new FileReader();
        let ext          = $('#upload-payment-file').val().split('.').pop().toLowerCase();
        let fileSizeInMB = (this.files[0].size / (1024*1024)).toFixed(2);

        if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
            let text = `Hanya bisa mengirim file gambar berupa: png, jpg, jpeg`;
            let html = `<div><small id="upload-payment-message" class="tred-bold">${text}</small></div>`;
            let uploadPaymentMessageLength = $('#upload-payment-message').length;

            if (uploadPaymentMessageLength == 0) {
                $('.upload-payment').prepend(html);
            } else if (uploadPaymentMessageLength == 1) {
                $('#upload-payment-message').text(text);
            }
        } else if (fileSizeInMB > 2) {
            let text = `File gambar tidak boleh lebih dari 2mb`;
            let html = `<div><small id="upload-payment-message" class="tred-bold">${text}</small></div>`;
            let uploadPaymentMessageLength = $('#upload-payment-message').length;

            if (uploadPaymentMessageLength == 0) {
                $('.upload-payment').prepend(html);
            }
            else if (uploadPaymentMessageLength == 1) {
                $('#upload-payment-message').text(text);
            }
        } else {
            $('#upload-payment-message').remove();

            reader.onloadend = () => {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }

            $('#upload-payment-image').toggleClass('d-none');
            $('#upload-payment-plus-logo').toggleClass('d-none');
            $('#upload-payment-cancel').removeClass('d-none');
            $('#upload-payment-submit-button').toggleClass('d-none');

        }
    });

    // Batal unggah foto pembayaran
    $('#upload-payment-cancel').on('click', function() {
        $('#upload-payment-plus-logo').removeClass('d-none');
        $('#upload-payment-cancel').addClass('d-none');
        $('#upload-payment-image').removeAttr('src');
        $('#upload-payment-file').val('');
        $('#upload-payment-image').toggleClass('d-none');
        $('#upload-payment-submit-button').toggleClass('d-none');
    });

    // Upload payment form
    $('#upload-payment-form').on('submit', function(event) {
        event.preventDefault();

        let dataId = $('#upload-payment-button').data('id');
        let datas  = [
            {
                status: 'uploadImage'
            }
        ]

        ajaxForm('POST', this, `/book-users/${dataId}`, function (response) {
            if (response.success) {
                let messageText = 'Berhasil men-upload bukti pembayaran';

                $('#upload-payment-file').val('');
                $('.close').trigger('click');
                $('#upload-payment-cancel').trigger('click');
                alertMessage(messageText);
            } else {
                let errors = response.errors.upload_payment;
                alert(errors);
            }

        }, datas);
    });

    $('.upload-payment-failed').on('click', function() {
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let dataId      = $(this).data('id');
        let confirmText = 'Apakah anda yakin ingin membatalkan pembayaran ini ?';

        if (confirm(confirmText)) {
            let datas = {
                _token: csrfToken,
                _method: 'PATCH',
                status: 'failed',
            };

            ajaxJson('POST', `/book-users/${dataId}`, datas, () => {
                $(this).parents('.upload-payment-value').remove();
                let messageText = 'Berhasil membatalkan pembelian';

                alertMessage(messageText);
            });
        }
    })

    // Lihat daftar tagihan
    $('.see-billing-list').on('click', function() {
        let dataId = $(this).data('id');
        let html = '';

        ajaxJson('GET', `/book-users/${dataId}`, {}, response => {
            $('.bill').html(response.viewRender);
        });
    });

    //#region  Confirmed payment - Konfirmasi pembayaran
    $('.confirm-payment').on('click', function() {
        let dataId = $(this).data('id');
        let datas  = {
            _token       : csrfToken,
            _method      : 'PATCH',
            status: 'orderInProcess'
        }

        let confirmText = 'Apakah anda yakin ingin menkonfirmasi pembayaran tersebut?';

        modalConfirm(this, confirmText, (accepted) => {
            if (accepted) {
                ajaxJson('POST', `/book-users/${dataId}`, datas, response => {
                    let messageText = 'Berhasil menkonfirmasi pembayaran dan akan di proses';

                    alertMessage(messageText);
                    setTimeout(() => $(this).parents('.uploaded-payment').remove(), 200);

                    addAndSubtractStatusNotification();
                });
            }
        });
    });
    //#endregion Confirmed payment - Konfirmasi pembayaran

    //#region Confirmed shipped - Konfirmasi pengiriman
    $('.confirm-shipping').on('click', function () {
        let confirmText = 'Apakah anda yakin akan menkonfirmasi pengiriman tersebut?';
        let dataId      = $(this).data('id');
        let datas       = {
            _token       : csrfToken,
            _method      : 'PATCH',
            status: 'orderOnDelivery'
        }

        modalConfirm(this, confirmText, accepted => {
            if (accepted) {
                console.log(true);
                // ajaxJson('POST', `/book-users/${dataId}`, datas, response => {
                //     let messageText = 'Berhasil menkonfirmasi pengiriman dan akan di proses';

                //     alertMessage(messageText);
                //     setTimeout(() => $(this).parents('.uploaded-payment').remove(), 200);

                //     addAndSubtractStatusNotification();
                // });
            }
        })
    });
    //#endregion Confirmed shipped - Konfirmasi pengiriman

    // #region Tracking packages - Lacak paket
    $('.tracking-packages').on('click', function() {
        let dataInvoice = $(this).data('invoice').toString();

        console.log(dataInvoice);
        let spinnerHtml  = `<div id="tracking-spinner" class="d-flex justify-content-center pb-4">`;
            spinnerHtml += `<div class="spin"></div>`;
            spinnerHtml += `</div>`;
        let modalLength  = $('#tracking-packages').length;
        let datas        = {
            waybill: '030200010250720',
            courier: 'jne',
            key    : 'ce496165f4a20bc07d96b6fe3ab41ded',
        };

        $(this).attr('data-target', '#tracking-packages');
        $(this).attr('data-toggle', 'modal');

        if (modalLength == 0) {
            let html =
            `<div class="modal fade" id="tracking-packages-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div id="tracking-packages-body" class="modal-body">
                            <div id="tracking-modal-header" class="mb-4 d-flex justify-content-between">
                                <h5 class="modal-title tred login-header">Lacak Paket</h5>
                                <button id="tracking-modal-close" type="button" class="close c-p" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div id="tracking-modal-content">
                                ${spinnerHtml}
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $(this).after(html);
            $('#tracking-packages-modal').modal('show');
        }

        $.ajax({
            type: "POST",
            url: "https://pro.rajaongkir.com/api/waybill",
            data: datas,
            dataType: "JSON",
            success: response => {
                let trackingPackagesBody =
                `<div class="mt-4 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Nomer Resi</div>
                        <div id="tracking-resi"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Nama Penerima</div>
                        <div id="tracking-receiver-name"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Status</div>
                        <div id="tracking-status"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Kurir</div>
                        <div id="tracking-courier-name"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Service</div>
                        <div id="tracking-service"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Dikirim tanggal</div>
                        <div id="tracking-date"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Dikirim oleh</div>
                        <div id="tracking-shipper-name"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Dikirim menuju</div>
                        <div id="tracking-destination"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <h5>Manifes</h5>
                    <div id="tracking-package-manifest" class="text-grey">
                    </div>
                </div>`;

                let contentLength = $('#tracking-modal-content').children().length; // Default 1

                if (contentLength == 1) {
                    $('#tracking-spinner').remove();
                    $('#tracking-modal-content').append(trackingPackagesBody);
                }

                let result              = response.rajaongkir.result;
                let details             = result.details;
                let manifestChildLength = $('#tracking-package-manifest').children().length;

                const html = (date, description) => {
                    let html =
                    `<div class="d-flex justify-content-between">
                        <div>${date}</div>
                        <div class="text-right">${description}</div>
                    </div>`;

                    return html;
                }

                $('#tracking-resi').text(details.waybill_number);
                $('#tracking-receiver-name').text(result.summary.receiver_name);
                $('#tracking-status').text(result.summary.status);
                $('#tracking-courier-name').text(result.summary.courier_name);
                $('#tracking-service').text(result.summary.service_code);
                $('#tracking-date').text(result.summary.waybill_date);
                $('#tracking-shipper-name').text(result.summary.shipper_name);
                $('#tracking-destination').text(result.summary.destination);

                if (manifestChildLength == 0) {
                    result.manifest.map(manifest => {
                        let dateTime = `${manifest.manifest_date} ${manifest.manifest_time}`;

                        $('#tracking-package-manifest').append(html(dateTime, manifest.manifest_description));
                    });
                }
            },
            error: errors => {
                console.log(errors.responseJSON.rajaongkir.status.description);
            }
        });

        const removeModal = () => {
            setTimeout(() => {
                $('#tracking-packages-modal').remove();
                $(this).removeAttr('data-target data-toggle');
            }, 200);
        };

        $('.modal').on('keydown', event => {
            if (event.key == 'Escape') {
                removeModal();
            }
        });
        $('#tracking-modal-close').on('click', () => removeModal());
    });
    //#endregion Tracking packages - Lacak paket

    //#region Cancel delivery - Batalkan pengiriman
    let cancelDelivery            = '.cancel-shipping-confirmation';
    let cancelDeliveryConfirmText = 'Apakah anda yakin ingin membatalkan pengiriman tersebut?';

    cancelStatusPayment(cancelDelivery, cancelDeliveryConfirmText, 'orderInProcess', 'Berhasil membatalkan pengiriman');
    //#endregion Cancel delivery

    //#region Cancel process confirmation - Batalkan proses konfirmasi
    let cancelProcessConfirmation            = '.cancel-process-confirmation';
    let cancelProcessConfirmationConfirmText = 'Apakah anda yakin ingin membatalkan proses tersebut?';

    cancelStatusPayment(cancelProcessConfirmation, cancelProcessConfirmationConfirmText,
        'cancelProcessConfirmation', 'Berhasil membatalkan proses');
    //#endregion Cancel process confirmation

    //#region Cancel payment - Batalkan pembayaran
    let cancelUploadImage            = '.cancel-upload-image';
    let cancelUploadImageConfirmText = 'Apakah anda yakin ingin membatalkan pembayaran tersebut?';

    cancelStatusPayment(cancelUploadImage, cancelUploadImageConfirmText,
        'cancelUploadImage', 'Berhasil membatalkan unggahan bukti pembayarn');
    //#end region cancel payment

    // #endregion Waiting for payment - menunggu pembayaran
}); // End of onload Event
