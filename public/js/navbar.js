"use strict";

$(document).ready(function () {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('.form-reset').on('click', function() {
        $(this).parents('form').trigger('reset');
        $(this).parents('form').find('input[type=number]').val('');
        $(this).parents('form').find('input[type=checkbox]').removeAttr('checked');
    });

    $('.search-text').on('keyup', function() {
        let keywordValue = $(this).val();

        if (keywordValue != "" && keywordValue.length >= 3)  {
            let datas = { keywords: keywordValue }

            $.get('/books/search', datas, response => {
                let html = '';
                let books = response.data.books;
                let authors = response.data.authors;

                if (books.length != 0) {
                    html += `<div class="ml-2 pb-1">Buku</div>`;

                    let i        = 0;
                    let htmlLink = ``;

                    books.forEach(data => {
                        i++;

                        if (i <= 6) {
                            htmlLink += `<a href="/books/${data.id}" class="nav-book-search-link">${data.name}</a>`;
                        }

                    });

                    htmlLink = `<div id="book-links">${htmlLink}</div>`;
                    html += htmlLink;

                    let j = 0;

                    if (books.length > 6) {
                        j++;

                        if (j <= 6) {
                            html += `<a href="/books?keywords=${keywordValue}" class="text-right nav-book-search-link">Lihat semua</a>`;
                        }
                    }
                }

                if (authors.length != 0) {
                    html += `<div class="ml-2 pb-1">Author</div>`;

                    let i = 0;
                    let htmlLink = ``;

                    authors.forEach(data => {
                        i++;

                        if (i <= 6) {
                            htmlLink += `<a href="/authors/${data.id}" class="nav-book-search-link">${data.name}</a>`;
                        }
                    });

                    htmlLink = `<div id="author-links">${htmlLink}</div>`;

                    html += htmlLink;

                    let j = 0;

                    if (authors.length > 6) {
                        j++;

                        if (j <= 6) {
                            html += `<a href="/authors?keywords=${keywordValue}" class="text-right nav-book-search-link">Lihat semua</a>`;
                        }
                    }
                }

                $('.nav-book-search').html('');
                $('.nav-book-search').append(html);
                $('.nav-book-search').show();
            });

        } else {
            $('.nav-book-search').hide();
        }
    });

    $('#search-books-form').on('submit', function(event) {
        let search = $('.search-text').val();

        if (search.length < 3) event.preventDefault();
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

    // Login
    $('#form-login').on('submit', function (event) {
        event.preventDefault();

        let validations = [
            {
                input: '#email',
                inputName: 'Email',
                rules: 'required,email'
            },
            {
                input: '#password',
                inputName: 'Password',
                rules: 'required'
            },
        ];

        validator(validations, success => {
            if (success) {
                ajaxForm('POST', this, this.action, response => {
                    if (response.status == 'fail') {
                        let errorMessages =
                        `<div class="alert-messages m-0 mb-3">
                            <div class="alert-message">
                                <div class="alert-message-text">${response.message}</div>
                                <i class="alert-message-icon fas fa-exclamation-triangle"></i>
                            </div>
                        </div>`;

                        if ($('.alert-messages').length == 0) $('#form-login').prepend(errorMessages);
                    } else {
                        window.location.href = response.data.url;
                    }
                });
            }
        });
    });

    // Menghapus pesan error dan input value login, saat men-click tombol exit pada modal login
    $('#login-exit').on('click', function () {
        $('.error').html('');
        $('#errorLogin').html('');
        $('#email').val('');
        $('#password').val('');
    });

    // Jika validasi form login bagian backend mulai bekerja, maka munculkan alert
    if (!$('.error-backend').is(':visible')) {
        $('.error-backend').trigger('click');
    }

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
                    citiesAndDistrictsHtml += `<option value="${city.id}">${city.type} ${city.name}</option>`;
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
    const checkOngkir = () => {
        $('.courier-choise').on('click', function (event) {
            event.stopImmediatePropagation();

            let dataCustomerLength = $('.data-customer').length;
            let addressChecked     = $('.customer-address').is(':checked');

            if (dataCustomerLength != 0 && addressChecked) {
                // Loading Couriers - Membuat loading saat layanan kurir belum muncul
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

                let destination              = $('.customer-address:checked').parents('.row').find('.destination-datas');
                let originSubdistictId       = $('#origin').data('subdistrict-id');
                let originType               = $('#origin').data('origin-type');
                let destinationSubdistrictId = destination.data('destination-id');
                let destinationType          = destination.data('destination-type');
                let weight                   = $('#weight').data('weight');
                let courierChoise            = $('input[name=courier-choise]:checked').val();

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
    } ;

    checkOngkir();

    $('#book-payment-form').on('submit', function (e) {
        e.preventDefault();

        let bookId             = $(this).data('id');
        let courierServiceCost = $('.inp-courier-choise-service:checked').val()
        let courierServiceName = $('.inp-courier-choise-service:checked').next().text();
        let paymentMethod      = $('.inp-payment-method:checked').val();
        let address            = $('.customer-address:checked').val();
        let uniqueCode         = randomIntFromInterval(100, 999);

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
                let appendElement = $('.home-and-anymore-show');

                backendMessage(appendElement, response.errors);
            } else {
                window.location.href = response.url;
            }
        });

    });

    // Book Payment
    let pathName = window.location.pathname;
    let regexMatch = /\/book-purchases\/[0-9]{1}/;
    let regexPathName = pathName.match(regexMatch);

    let userId   = $('#payment-limit-date').data('id');
    let months   = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let myDays   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
    let today    = new Date();
    let tomorrow = new Date(today);
    let i        = 59;

    tomorrow.setDate(today.getDate() + 1);

    ajaxBookPurchaseDeadlineDestroy();

    if (regexPathName) {
        getPaymentDeadlineText(userId);
    }

    let paymentLimitText = `${myDays[tomorrow.getDay()] ?? myDays[0]}, ${tomorrow.getDate()} ${months[tomorrow.getMonth()]} ${tomorrow.getFullYear()}`;
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

            ajaxJson('POST', '/wishlists', datas);
        } else {
            if (wishlistTargets.length != 0) {
                wishlistTargets.forEach(element => {
                    element.removeClass('fas');
                    element.addClass('far');
                })
            }
            datas['_method'] = 'DELETE';

            ajaxJson('POST', `/wishlists/${dataId}`, datas);
        }
    });

    //#region Wishlist Search
    $('.wishlist-search-input').on('keyup', function(event) {
        let value = event.target.value;
        let datas = { keywords: value };

        ajaxJson('GET', '/wishlists/search', datas, response => {
            $('.books')[0].outerHTML = response.render;
            $('.book').on('click', function () {
                window.location.href = $(this).find('.book-show-link').attr('href');
            });
        });
    });
    //#endregion Wishlist Search

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
        let confirmText = 'Apakan anda yakin ingin menghapus semua pesan ?';

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                ajaxForm('POST', '#chat-delete-form', `/user-chats/${userId}`, () => {
                    let text = 'Berhasil menghapus semua pesan';

                    $('#user-chats-error-image').text(text);
                    showMessageChatting();
                    $('#menu-chattings').children().remove();
                    $('.user-chat-active').remove();
                });
            }
        });


        $('#chat-delete-button').trigger('click');
    });
    // End Chat

    //#region Book Show
    $('#book-delete-modal').on('click', function(e) {
        e.preventDefault();
        let confirmText = 'Apakah anda yakin ingin menghapus semua data pada buku ini ?';

        modalConfirm(confirmText, (accepted) => {
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

            ajaxJson('POST', '/wishlists', datas);
        } else {
            $(this).find('i').removeClass('fas');
            $(this).find('i').addClass('far');
            datas['_method'] = 'DELETE';

            ajaxJson('POST', `/wishlists/${dataId}`, datas);
        }
    });

    //#region Function - Book show click
    const bookShowClick = () => {
        $('.book-show-click').on('click', function() {
            let clickedSrc =  $(this).find('img').attr('src');

            $(this).siblings('.book-show-image-active').removeClass('book-show-image-active');
            $(this).addClass('book-show-image-active');
            $('#primary-book-image').attr('src', clickedSrc);
        });
    }
    //#endregion Function - Book show click

    bookShowClick();

    //#region Tambah gambar buku
    $('#add-book-image-form').on('submit', function(event) {
        event.preventDefault();

        let validation = [
            {
                input: '#image',
                inputName: 'Gambar',
                rules: 'required,mimes:jpg|jpeg|png,maxSize:2000',
            }
        ];

        validator(validation, success => {
            if (success) {
                ajaxForm('POST', this, `/books/add-book-images/${$('#book-show').data('id')}`, response => {
                    if (!response.errors) {
                        $(this).trigger('reset');

                        let messageText         = 'Berhasil menambah gambar buku';
                        let src                 = `${window.location.origin}/storage/books/book_images/${response.image}`;
                        let html                =
                        `<div class="book-show-click" data-id="${response.book_image.id}">
                            <img src="${src}" class="book-show-image">
                            <button class="book-image-delete btn-none"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>`;
                        let bookShowImageLength = $('.book-show-click').length + 1;

                        $('#custom-modal').modal('hide');
                        alertMessage(messageText);
                        $('.book-show-images').append(html);

                        console.log(bookShowImageLength);

                        if (bookShowImageLength > 3) {
                            $('#add-book-image-form').parent().remove();
                        }

                        bookShowClick();
                        bookImageDelete();
                    }
                })
            }
        });
    });
    //#endregion Tambah gambar buku

    //#region Edit gambar
    $('#book-image-edit-form').on('submit', function(event) {
        event.preventDefault();

        const clickedDataId = () => $('.book-show-image-active').data('id');

        let checkBookImageFirst = $('.book-show-images').children().first().hasClass('book-show-image-active');

        if (!checkBookImageFirst) {
            let validations = [
                {
                    input: '#book-image-edit-file',
                    inputName: 'Gambar',
                    rules: 'required,mimes:jpg|jpeg|png,maxSize:2000'
                }
            ];

            validator(validations, success => {
                if (success) {
                    ajaxForm('POST', this, `/book_images/${clickedDataId()}/edit`, response => {
                        if (response.update) {
                            let message = 'Berhasil mengedit gambar buku';
                            let src     = `${window.location.origin}/storage/books/book_images/${response.src}`;

                            alertMessage(message);
                            $(this).trigger('reset');
                            $('.book-show-image-active').find('img').attr('src', src);
                        } else {
                            let afterMessage = $('.book-show-images');

                            backendMessage(afterMessage, response.errors)
                        }
                    });
                }
            });
        } else {
            $('#book-edit')[0].click();;
        }
    });
    //#endregion Edit gambar

    //#region Delete Book Images
    const bookImageDelete = () => {
        $('.book-image-delete').on('click', function() {
            let dataId  = $(this).parent().data('id');
            let message = 'Apakah anda yakin ingin menghapus gambar buku?';

            modalConfirm(message, accepted => {
                if (accepted) {
                    ajaxJson('POST', `/book_images/${dataId}/delete`, requestMethodName('DELETE')[0], response => {
                        if (response.delete) {
                            let message = 'Berhasil menghapus gambar buku';

                            alertMessage(message);
                            $(this).parent().siblings().removeClass('book-show-image-active');
                            $(this).parent().prev().addClass('book-show-image-active');
                            $(this).parent().remove();
                        }
                    });
                }
            });
        });
    }

    bookImageDelete();
    //#endregion Delete Book Images

    //#region Cart - in Book Show
    //#region Cart Delete
    const cartDelete = () => {
        $('#cart-delete').on('click', function() {
            let dataId = $(this).data('id');

            let datas = {
                _token: csrfToken,
                _method: 'DELETE',
            };

            ajaxJson('POST', `/carts/${dataId}`, datas, response => {
                if (response.delete) {
                    let message = 'Berhasil menghapus buku dari keranjang';
                    let html = `<button id="cart-store" class="btn-none"><i class="add-shop fa fa-plus" aria-hidden="true"></i> Keranjang</button>`;

                    alertMessage(message);
                    $(this)[0].outerHTML = html;
                    cartStore();
                }
            });
        });
    }

    cartDelete();
    //#region Cart Delete

    //#region Cart Store
    const cartStore = () => {
        $('#cart-store').on('click', function() {
            let bookId = $('#book-show').data('id');
            let userId = $('#app').data('user-id');

            let datas = {
                _token: csrfToken,
                user_id: userId,
                book_id: bookId,
            };

            ajaxJson('POST', '/carts', datas, response => {
                if (response.cart) {
                    let message = 'Berhasil memasukannya ke keranjang';
                    let html    = `<button id="cart-delete" class="btn-none tred" data-id="${response.cart.id}">Hapus dari keranjang</button>`;

                    $(this)[0].outerHTML = html;
                    alertMessage(message);

                    cartDelete();
                }
            });
        });
    }

    cartStore();
    //#endregion Cart Store
    //#endregion Cart
    //#endregion Book Show

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
    //#endregion Cart - in Book Show

    // User Index - Daftar karyawan
    $('.user-block').on('click', function (event) {
        event.preventDefault();

        let thisButton = $(this);
        let buttonEdit = $(this).parents('td').prev().find('a');

        if ($(this).text() == 'Blokir') {
            let confirmText = 'Apakah anda yakin ingin menblok user tersebut ?';
            modalConfirm(confirmText, success => {
                if (success) {
                    $.ajax({
                        type: "POST",
                        url: `/users/${$(this).parent().data('id')}/block`,
                        data: {
                            '_token': csrfToken,
                            'userBlock': true,
                        },
                        dataType: "JSON",
                        success: function (response) {
                            let message = 'Berhasil memblokir user';
                            alertMessage(message);

                            $(thisButton).text('Lepas Blokir');
                            $(thisButton).parent().parent().parent().addClass('text-grey tbold');
                            buttonEdit.attr('href', '');
                        }
                    });

                }
            });
        } else {
            let confirmText = 'Apakah anda yakin ingin melepas blok user tersebut ?';

            modalConfirm(confirmText, success => {
                let userId = $(this).parent().data('id');

                if (success) {
                    $.ajax({
                        type: "POST",
                        url: `/users/${userId}/restore`,
                        data: {
                            '_token': csrfToken,
                            'userRestoreBlock': true,
                        },
                        dataType: "JSON",
                        success: function (response) {
                            let message = 'Berhasil melepas blokir user';
                            alertMessage(message);

                            $(thisButton).text('Blokir');
                            $(thisButton).parent().parent().parent().removeClass('text-grey tbold');
                            buttonEdit.attr('href', `/users/${userId}/edit`);
                        }
                    });
                }
            });
        }


    });

    // User Delete / Destroy
    $('.user-delete').on('click', function (e) {
        e.preventDefault();

        let confirmText = 'Apakah anda yakin ingin menghapus karyawan tersebut secara permanen ?';

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                let datas = {
                    '_token'    : csrfToken,
                    '_method'   : 'DELETE',
                    'userDelete': true,
                }

                ajaxJson('POST', `/users/${$(this).parent().data('id')}`, datas, success => {
                    let message = 'Berhasil menghapus karyawan';

                    $(this).parent().parent().parent().remove();
                    alertMessage(message);
                });
            }
        });

    });

    let userEditForm = $('#user-edit-form').children().find('input, select').toArray();
        userEditForm = userEditForm.map(input => '#' + $(input).attr('id'));

    // User Edit / Update
    $('#user-edit-form').on('submit', function (e) {
        e.preventDefault();

        let userEditForm = $('#user-edit-form').children().find('input, select').toArray();
        userEditForm     = userEditForm.map(input => {
            let validations  = {
                input    : '#' + input.id,
                inputName: capitalizeFirstLetter(input.id.replace(/_|-/, ' ')),
                rules    : 'required',
            };

            if (input.id == 'nomer_handphone') {
                validations['rules'] = 'required,numeric,min:9,max:15';
            }

            if (input.id == 'user-email') {
                validations['rules'] = 'required,email';
            }

            return validations;
        });

        let validations = userEditForm;

        validator(validations, success => {
            if (success) {
                let dataId = $(this).data('id');

                ajaxForm('POST', this, `/users/${dataId}`, response => {
                    if (response.status === 'fail') {
                        let addMessage    = $(this).children(':nth-child(2)');

                        backendMessage(addMessage, response.errors);
                        $('#click-to-the-top').trigger('click');
                    } else {
                        let message = 'Berhasil mengedit user';

                        $('.alert-messages').remove();
                        alertMessage(message);
                    }
                })
            }
        });
    });

    //#region User create / update photo profile
    const deletePhotoProfile = () => {
        $('#user-delete-photo-form').on('submit', function (e) {
            e.preventDefault();

            let message = 'Apakah anda yakin ingin menghapus foto profile ?';

            modalConfirm(message, accepted => {
                if (accepted) {
                    ajaxForm('POST', this, this.action, response => {
                        if (response.update) {
                            let origin      = window.location.origin;
                            let avatarImage = `${origin}/img/avatar-icon.png`;
                            let html        = '<i class="fas fa-user"></i>';
                            let message     = 'Berhasil menghapus foto profile';
                            let userPhoto   = $('#user-circle-fit').length

                            if (userPhoto != 0 && $('.user-circle').find('i').length ==0 ) {
                                $('#user-circle-fit').remove();
                                $('#user-add-photo').text('Tambah Foto');
                                $('#user-show-profile').attr('src', avatarImage );
                                $('.user-circle').append(html);
                                alertMessage(message);
                                $(this).remove();
                            }
                        }
                    });
                }
            });
        });
    }

    $('#user-add-photo').on('click', function() {
        let profileImage = $('#user-show-profile').attr('src');
        let dataId       = $(this).data('id');
        let buttonText   = $(this).text() == 'Edit Foto' ? 'Edit' : 'Tambah';
        let headerText   = $(this).text() == 'Edit Foto' ? 'Edit Foto' : 'Tambah Foto';

        let html         =
        `<div class="text-center">
            <img id="user-modal-image" class="profile-img" src="${profileImage}">
        </div>
        <form id="user-add-photo-profile-form" action="/users/${dataId}/add-photo-profile" method="post">
            <input id="user-image" class="mt-5" name="image" type="file" accept="image/png, image/jpeg, image/jpg">
            <div class="text-right mt-4">
                <button type="button"
                data-dismiss="modal" aria-label="Close"
                class="btn-none tred-bold mr-3">Batal</button>
                <button id="user-add-photo-form" class="btn btn-red">${buttonText}</button>
            </div>
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_token" value="${csrfToken}">
        </form>`;

        bootStrapModal(headerText, 'modal-md', () => html );

        $('#user-image').on('change', function() {
            changeInputPhoto('user-modal-image', 'user-image');
        });

        $('#user-add-photo-profile-form').on('submit', function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();

            let validation = [
                {
                    input    : '#user-image',
                    inputName: 'Gambar',
                    rules    : 'required,mimes:jpg|jpeg|png,maxSize:2000',
                }
            ];

            validator(validation, success => {
                if (success) {
                    ajaxForm('POST', this, this.action, response => {
                        // Backend validation
                        if (response.errors) {
                            let addMessage = $('#custom-modal').find('.modal-content').children().first();

                            backendMessage(addMessage, response.errors);
                            $('.alert-messages').addClass('m-auto w-90');
                        } else {
                            let message                = 'Berhasil menambah foto profile';
                            let navbarProfileImageText = `<img id="user-circle-fit" src=""></img>`;

                            if ($('.user-circle').find('i').length != 0) {
                                $('.user-circle').find('i').remove();
                                $('.user-circle').append(navbarProfileImageText);
                            }

                            let dataId = $('#user-add-photo').data('id');
                            let addFormDestroyPhoto =
                            `<div class="mt-2">
                                <form id="user-delete-photo-form" action="/users/${dataId}/destroy-photo" method="post">
                                    <button type="submit" class="btn-none tred-bold">Hapus Foto</button>
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                </form>
                            </div>`;

                            let userDeleteFormLength = $('#user-delete-photo-form').length;

                            if (userDeleteFormLength == 0) {
                                $('#user-change-password').parent().append(addFormDestroyPhoto);
                            }

                            changeInputPhoto('user-circle-fit', 'user-image');
                            changeInputPhoto('user-show-profile', 'user-image');

                            $('#custom-modal').modal('hide');
                            $('#user-image').val('');
                            $('#user-add-photo').text('Edit Foto')
                            alertMessage(message);

                            deletePhotoProfile();
                        }
                    });
                }
            });
        });

    });
    deletePhotoProfile();
    //#endregion User create / update photo profile

    //#region User change password
    $('#user-change-password').on('click', function() {
        let dataId = $(this).data('id');

        bootStrapModal('Ubah Password', 'modal-md', function() {
            let html   =
            `<form id="user-change-password" action="/users/${dataId}/change-password" method="POST">
                <div class="form-group">
                    <label for="password-lama">Password Lama</label>
                    <input id="password-lama" name="password_lama" type="password" class="form-control-custom" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password-baru">Password Baru</label>
                    <input id="password-baru" name="password_baru" type="password" class="form-control-custom" autocomplete="off">
                </div>
                <div class="text-right mt-4">
                    <button class="btn-none tred-bold mr-3"
                    data-dismiss="modal" aria-label="Close">Batal</button>
                    <button class="btn btn-red" type="submit">Ubah</button>
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>`;

            return html;
        })

        $('#user-change-password').on('submit', function(event) {
            event.preventDefault();

            let validations = [
                {
                    input: '#password-lama',
                    inputName: 'Password lama',
                    rules: 'required',
                },
                {
                    input: '#password-baru',
                    inputName: 'Password baru',
                    rules: 'required,min:8',
                },
            ];

            validator(validations, success => {
                if (success) {
                    ajaxForm('POST', this, this.action, response => {
                        if (response.errors) {
                            let element = $(this).parent().prev();

                            backendMessage(element, response.errors);
                            $('.alert-messages').addClass('mx-auto w-90');
                        } else if (response.updated){
                            let message = `Berhasil mengubah password`;

                            $('#custom-modal').modal('hide');
                            alertMessage(message);
                        }
                    });
                }
            });
        });
    });
    //#endregion User change password

    //#region User change biodata
    $('#user-change-biodata').on('click', function() {
        bootStrapModal('Ubah Biodata Diri', 'modal-md', () => {
            let firstName         = $('#user-first-name').text();
            let lastName          = $('#user-last-name').text();
            let userDateOfBirth   = $('#user-date-of-birth').text();
            let userMan           = $('#user-gender').text() == 'Laki-laki' ? 'selected' : '';
            let userWoman         = $('#user-gender').text() != 'Laki-laki' ? 'selected' : '';
            let userAddress       = $('#user-hidden-address').text() == '-' ? '' : $('#user-hidden-address').text();
            let userPhoneNumber   = $('#user-phone-number').text();
            let dataId            = $(this).data('id');

            let html =
            `<form id="user-change-biodata-form" action="/users/${dataId}/change-biodata" method="POST">
                <div class="form-group">
                    <label for="nama-awal">Nama Awal</label>
                    <input id="nama-awal" name="first_name" type="text" class="form-control-custom" value="${firstName}">
                </div>
                <div class="form-group">
                    <label for="nama-akhir">Nama Akhir</label>
                    <input id="nama-akhir" name="last_name" type="text" class="form-control-custom" value="${lastName}">
                </div>
                <div class="form-group">
                    <label for="tanggal-lahir">Tanggal Lahir</label>
                    <input id="tanggal-lahir" name="date_of_birth" type="date" class="form-control-custom" value="${userDateOfBirth}">
                </div>
                <div class="form-group">
                    <label for="jenis-kelamin">Jenis Kelamin</label>
                    <select id="jenis-kelamin" name="gender" class="form-control-custom">
                        <option value="L" ${userMan}>Laki-laki</option>
                        <option value="P" ${userWoman}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat <small class="text-grey">(boleh kosong)</small></label>
                    <textarea id="alamat" name="address" class="w-100">${userAddress}</textarea>
                </div>
                <div class="form-group">
                    <label for="nomer-handphone">Nomer Handphone <small class="text-grey">(boleh kosong)</small></label>
                    <input id="nomer-handphone" name="phone_number" type="number" class="form-control-custom" value="${userPhoneNumber}">
                </div>
                <div class="text-right mt-4">
                    <button class="btn-none tred-bold mr-3"
                    data-dismiss="modal" aria-label="Close">Batal</button>
                    <button class="btn btn-red" type="submit">Ubah</button>
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>`;

            return html;
        });

        $('#user-change-biodata-form').on('submit', function(event) {
            event.preventDefault();

            let formInputs = $(this).find('input:not([type=hidden])').toArray();
            let validations = formInputs.map(input => {
                let inputId    = '#' + input.id;
                let inputName  = capitalizeFirstLetter(input.id.replace(/_|-/, ' '));
                let validation = {
                    input    : inputId,
                    inputName: inputName,
                    rules    : 'required',
                };

                if (inputId == '#tanggal-lahir') {
                    validation['rules'] = 'nullable';
                }

                if (inputId == '#nomer-handphone') {
                    validation['rules'] = 'nullable,numeric,min:9,max:15';
                }

                return validation;
            });

            validator(validations, success => {
                if (success) {
                    ajaxForm('POST', this, this.action, response => {
                        if (response.errors) {
                            let parentPrev = $(this).parent().prev();

                            backendMessage(parentPrev, response.errors);
                            $('.alert-messages').addClass('w-90 mx-auto')
                        } else {
                            let message = 'Berhasil mengedit biodata';
                            let user    = response.user;
                            let gender  = user.gender == 'L' ? 'Laki-laki' : 'Perempuan';

                            alertMessage(message);
                            $('#custom-modal').modal('hide');
                            $('.navbar-user-first-name').text(user.first_name);
                            $('#user-first-name').text(user.first_name);
                            $('#user-last-name').text(user.last_name);
                            $('#user-date-of-birth').text(user.date_of_birth);
                            $('#user-gender').text(gender);
                            $('#user-hidden-address').text(user.address);
                            $('#user-email').text(user.email);
                            $('#user-phone-number').text(user.phone_number);
                        }
                    });
                }
            });
        });
    });
    //#endregion User change biodata

    //#region User change email
    $('#user-change-email').on('click', function() {
        let dataId   = $(this).data('id');
        let oldEmail = $('#user-email').text();

        bootStrapModal('Ubah Email', 'modal-md', () => {
            let html   =
            `<form id="user-change-email" action="/users/${dataId}/change-email" method="POST">
                <div class="form-group">
                    <label>Email Lama</label>
                    <input id="user-old-email" type="text" class="form-control-custom disb" value="${oldEmail}" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email Baru</label>
                    <input id="email" name="email" type="email" class="form-control-custom" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Masukan Password</label>
                    <input id="password" name="password" type="password" class="form-control-custom" autocomplete="off">
                </div>
                <div class="text-right mt-4">
                    <button class="btn-none tred-bold mr-3"
                    data-dismiss="modal" aria-label="Close">Batal</button>
                    <button class="btn btn-red" type="submit">Ubah</button>
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>`;

            return html;
        });

        $('#user-change-email').on('submit', function(event) {
            event.preventDefault();

            let validations = [
                {
                    input: '#email',
                    inputName: 'Email',
                    rules: 'required,email'
                },
                {
                    input: '#password',
                    inputName: 'Password',
                    rules: 'required,'
                },
            ];

            validator(validations, success => {
                if (success) {
                    ajaxForm('POST', this, this.action, response => {
                        if (response.errors) {
                            let parentPrev = $(this).parent().prev();

                            backendMessage(parentPrev, response.errors);
                            $('.alert-messages').addClass('w-90 mx-auto');
                        } else if (response.updated) {
                            let user    = response.user;
                            let message = 'Berhasil mengubah email';

                            $('#user-old-email').val(user.email);
                            $('#user-email').text(user.email);
                            $('.alert-messages').remove();
                            $('#custom-modal').modal('hide');
                            alertMessage(message);
                        }
                    });
                }
            });
        });

    });
    //#endregion User change email

    // Book Edit
    // Keyup input
    const bookEditCustomValidationCondition = (inputId, data, formAction = '') => {
        if (inputId == '#isbn') {
            data['rules'] = 'required,digits:13';
        }

        if (inputId == '#tersedia_dalam_ebook') {
            data['rules'] = 'nullable';
        }

        if (inputId == '#diskon') {
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

                            $(this)[0].reset();
                            $('#book-show-image').attr('src', '')
                            $('.alert-messages').remove();
                            alertMessage(message);
                        } else {
                            let addMessage    = $('#book-edit-form, #book-store-form').children().first();

                            backendMessage(addMessage, response.errors);
                            $('#click-to-the-top').trigger('click');
                        }
                    });
                } else {
                    ajaxForm('POST', this, `/books/${dataId}`, response => {
                        if (!response.errors) {
                            let message = 'Berhasil mengedit buku';

                            $('.alert-messages').remove();
                            alertMessage(message);
                        } else {
                            console.log(false);
                            let addMessage    = $('#book-edit-form, #book-store-form').children().first();

                            backendMessage(addMessage, response.errors);
                            $('#click-to-the-top').trigger('click');
                        }
                    }, requestMethodName('PATCH'));
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

    $('#tanggal_rilis').on('change', function() {
        $(this).next().length != 0 ? $(this).next().remove() : '';
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

    const formHtmlCustomer = (formId, action, method, buttonText,methodRequest = '') => {
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        methodRequest = methodRequest != '' ? `<input type="hidden" name="_method" value="${methodRequest}">` : '';

        let html =
        `
        <form id="${formId}" action="${action}" method="${method}">
            <div class="form-group mx-auto">
                <label for="nama_penerima">Nama Penerima</label>
                <input id="user-customer-name" type="text" name="nama_penerima" class="form-control-custom book-edit-inp">
            </div>
            <div class="form-group mx-auto mt-4">
                <label for="alamat_tujuan">Alamat Tujuan</label>
                <input id="user-customer-address" type="text" name="alamat_tujuan" class="form-control-custom book-edit-inp">
            </div>
            <div class="form-group mx-auto mt-4 position-relative">
                <label for="provinsi">Cari Kota / Kecamatan</label>
                <input id="user-city-district-search" type="text" class="form-control-custom book-edit-inp">
                <input id="user-city-or-district" type="hidden" name="kota_atau_kecamatan">
                <div id="user-hidden-address" class="user-address">
                </div>
            </div>
            <div class="form-group mx-auto mt-4">
                <label for="nomer_handphone">Nomer Handphone</label>
                <input id="user-customer-phone" type="text" name="nomer_handphone" class="form-control-custom book-edit-inp">
            </div>
            <div class="form-group mt-5">
                <button class="button-submit active-login" type="submit">${buttonText}</button>
            </div>
            ${methodRequest}
            <input type="hidden" name="_token" value="${csrfToken}">
        </form>
        `;

        return html;
    };

    const formCityAndDistrictKeyup = () => {
        $('#user-city-district-search').on('keyup', function() {
            let value = $(this).val();

            console.log(value);

            let data  = {
                keywords: value,
            };

            if (value.length >= 3) {
                ajaxJson('GET', `/customers/city-or-district`, data, response => {
                    $('#user-hidden-address').html('');
                    $('#user-hidden-address').show();

                    let html;
                    let requestAdressLength  = response.request_address.length;

                    if (requestAdressLength == 0) {
                        html = `<div class="px-2"><div>Data tidak valid</div></div>`;
                    } else {
                        html = response.request_address;
                        html = html.map(data => {
                            let province = data.province_name;
                            let type     = data.type;
                            let city     = data.city_name;
                            let district = data.district_name;
                            let address  = `${province}, ${type} ${city}, Kec. ${district}`;

                            let dataProvince   = `data-province="${data.province_id}"`;
                            let dataCity       = `data-city="${data.city_id}"`;
                            let dataDistrict   = `data-district="${data.district_id}"`;
                            let attributesData = [
                                dataProvince,
                                dataCity,
                                dataDistrict,
                            ];

                            attributesData = attributesData.join(' ');

                            let html = `<div class="user-address-data px-2" ${attributesData}><div>${address}</div></div>`;

                            return html;
                        });
                    }

                    $('#user-hidden-address').append(html);

                    $('.user-address-data').on('click', function() {
                        let province      = $(this).data('province');
                        let city          = $(this).data('city');
                        let district      = $(this).data('district');
                        let addressValues = `${province}-${city}-${district}`;

                        $('.user-address').hide();
                        $('#user-city-district-search').val($(this).text());
                        $('#user-city-or-district').attr('value', addressValues);

                        addressValues = $('#user-city-or-district').attr('value');
                        addressValues = addressValues.split('-');
                        addressValues = {
                            province_id: addressValues[0],
                            city_id    : addressValues[1],
                            district_id: addressValues[2],
                        };
                    });
                })
            } else if (value.length < 3) {
                $('#user-hidden-address').hide();
            }
        });
    };

    const formCustomerValidations = () => {
        let validations = [
            {
                input    : '#user-customer-name',
                inputName: 'Nama penerima',
                rules    : 'required,min:3'
            },
            {
                input    : '#user-customer-address',
                inputName: 'Alamat tujuan',
                rules    : 'required,min:10',
            },
            {
                input    : '#user-city-district-search',
                inputName: 'Kota / Kecamatan',
                rules    : 'required',
            },
            {
                input    : '#user-customer-phone',
                inputName: 'Nomer handphone',
                rules    : 'required,numeric,min:9,max:15',
            },
        ];

        return validations;
    };

    //#region User - Customer Update
    const userCustomerUpdate = () => {
        $('.user-customer-update').on('click', function(event) {
            event.stopImmediatePropagation();

            bootStrapModal('Ubah Alamat Pengiriman', 'modal-md', () => {
                let dataId = $(this).data('id');
                let html   = formHtmlCustomer('user-customer-update', `/customers/${dataId}`, 'POST', 'Edit', 'PATCH');

                return html;
            });

            let buttonUpdate         = $(this);
            let userCustomer         = $(this).parents('.user-customer');
            let userCustomerName     = userCustomer.find('.customer-name').text();
            let userCustomerAddress  = userCustomer.find('.customer-address').text();
            let userCustomerPhone    = userCustomer.find('.customer-phone-number').text();
            let userCustomerProvince = userCustomer.find('.customer-province');
            let userCustomerCity     = userCustomer.find('.customer-city');
            let userCustomerDistrict = userCustomer.find('.customer-district');

            console.log(userCustomer);

            let cityOrDistrictSearchValue  = `${userCustomerProvince.text()} ${userCustomerCity.text()} Kec. ${userCustomerDistrict.text()}`;
            let cityOrDistrictValue        = `${userCustomerProvince.data('province')}`;
                cityOrDistrictValue       += `-${userCustomerCity.data('city')}-${userCustomerDistrict.data('district')}`;

            $('#user-customer-name').val(userCustomerName);
            $('#user-customer-address').val(userCustomerAddress);
            $('#user-city-district-search').val(cityOrDistrictSearchValue);
            $('#user-city-or-district').val(cityOrDistrictValue);
            $('#user-customer-phone').val(userCustomerPhone);

            formCityAndDistrictKeyup();

            $('#user-customer-update').on('submit', function(event) {
                event.preventDefault();

                validator(formCustomerValidations(), success => {
                    if (success) {
                        $('#custom-modal').modal('hide');

                        ajaxForm('POST', this, this.action, response => {
                            if (response.status == 'success') {
                                let data         = response.data;
                                let message      = 'Berhasil mengedit alamat';
                                let userCustomer = buttonUpdate.parents('.user-customer');

                                userCustomer.find('.customer-name').text(data.customer.name);
                                userCustomer.find('.customer-phone-number').text(data.customer.phone_number);
                                userCustomer.find('.customer-address').text(data.address);
                                userCustomer.find('.customer-province').text(data.province.name);
                                userCustomer.find('.customer-province').attr('data-province', data.province.id);
                                userCustomer.find('.customer-city').text(data.city.name);
                                userCustomer.find('.customer-city').attr('data-city', data.city.id);
                                userCustomer.find('.customer-district').text(data.district.name);
                                userCustomer.find('.customer-district').attr('data-district', data.district.id);

                                $('#checkout-courier-service').remove();
                                alertMessage(message);
                            } else if (response.status == 'fail') {
                                backendMessage($('.modal-title'), response.data);
                            }
                        })
                    }
                });
            });
        })
    };

    userCustomerUpdate();
    //#endregion User - Customer Update

    //#region User - Customer Delete
    const userCustomerDelete = () => {
        $('.user-customer-delete').on('click', function() {
            let confirmText = 'Apakah anda yakin akan menghapus alamat tersebut ?';

            modalConfirm(confirmText, accepted => {
                if (accepted) {
                    let dataId       = $(this).data('id');
                    let datas        = {
                        _token : csrfToken,
                        _method: 'DELETE',
                    }

                    ajaxJson('POST', `/customers/${dataId}`, datas, response => {
                        if (response.status == 'success') {
                            let customerLength = $('.user-customer').length;

                            if (customerLength == 5) {
                                let html = `<button id="user-create-customer" class="btn-none tred-bold">Tambah</button>`;

                                $('#user-customer-title').after(html);
                                userCustomerCreate();
                            }

                            $('#checkout-courier-service').remove();
                            $(this).parents('.user-customer').remove();
                            alertMessage(response.message);
                        }
                    });
                }
            });
        });
    }

    userCustomerDelete();
    //#endregion User - Customer Delete

    //#region User - Customer Create
    const userCustomerCreate = () => {
        $('#user-create-customer').on('click', function() {
            bootStrapModal('Tambah Alamat Pengiriman', 'modal-md', () => {
                let html =  formHtmlCustomer('user-customer-store', '/customers', 'POST', 'Tambah');

                return html;
            });

            formCityAndDistrictKeyup();

            $('#user-customer-store').on('submit', function(event) {
                event.preventDefault();

                validator(formCustomerValidations(), success => {
                    if (success) {
                        $('#custom-modal').modal('hide');

                        ajaxForm('POST', this, this.action, response => {
                            if (response.success) {
                                let data    = response.data;
                                let message = 'Berhasil menambah alamat';
                                let html =
                                `
                                <div class="user-customer mt-3 d-flex borbot-gray-0 pb-2">
                                    <label>
                                        <div class="d-flex">
                                            <div class="mr-2 d-flex">
                                                <input type="radio" name="customer" class="my-auto" value="${data.customer.id}">
                                            </div>
                                            <div>
                                                <div>
                                                    <span class="customer-name">${data.customer.name}</span> -
                                                    <span class="customer-phone-number">${data.customer.phone_number}</span>
                                                </div>
                                                <div>
                                                    <span class="customer-address">${data.address}</span>.
                                                    <span class="customer-province" data-province="${data.province.id}">${data.province.name},</span>
                                                    <span class="customer-city" data-city="${data.city.id}">${data.city.type} ${data.city.name},</span>
                                                    <span class="customer-district" data-district="${data.district.id}">${data.district.name}</span>
                                                </div>
                                            </div>
                                        </div>
                                        </label>
                                    <div class="ml-auto text-right">
                                        <div>
                                            <button class="user-customer-update btn-none tred-bold" type="button" data-id="${data.customer.id}">Ubah</button>
                                        </div>
                                        <div>
                                            <button class="user-customer-delete btn-none tred-bold" type="button" data-id="${data.customer.id}">Hapus</butt>
                                        </div>
                                    </div>
                                </div>
                                `;
                                let userAddressLength = $('.user-customer').length;

                                if (userAddressLength == 0) {
                                    $('#user-create-customer').appendTo($('#user-customer-title').parent());
                                    $('#user-customer-lists').html(html);
                                } else if (userAddressLength == 4) {
                                    $('#user-customer-lists').append(html);
                                    $('#user-create-customer').remove();
                                }
                                else {
                                    $('#user-customer-lists').append(html);
                                }

                                alertMessage(message);
                                userCustomerUpdate();
                                userCustomerDelete();
                            } else {
                                backendMessage($('.modal-title'), response.errors)
                            }
                        });
                    }
                });
            });
        });
    }

    userCustomerCreate();
    //#endregion User - Customer Create

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

                checkOngkir();
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
    // Upload payment form
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

    $('.upload-payment-failed').on('click', function() {
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let dataId      = $(this).data('id');
        let confirmText = 'Apakah anda yakin ingin membatalkan pembayaran ini ?';

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                let datas = {
                    _token: csrfToken,
                    _method: 'PATCH',
                    status: 'failed',
                };

                $.post(`/book-users/${dataId}`, datas, (response) => {
                    console.log(response);
                    $(this).parents('.upload-payment-value').remove();
                    let messageText = 'Berhasil membatalkan pembelian';

                    alertMessage(messageText);
                })
            }
        });
    })

    // Lihat daftar tagihan
    $('.status-detail').on('click', function() {
        let invoice = $(this).data('invoice');

        $.get(`/status/${invoice}/detail`, response => {
            bootStrapModal('Detail', 'modal-md', () => {
                let data = response.data;

                let html =
                `
                <div>
                    <div class="mb-3">
                        <h5>Alamat Pengiriman</h5>
                        <div>
                            <div>${data.customer.name}</div>
                            <div>${data.customer.phone_number}</div>
                            <div>${data.customer.address}, Kec.${data.district}, ${data.city} ${data.city_type} . ${data.province}</div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>Kurir</td>
                        <td>${data.book_user.courier_name.toUpperCase()} (${data.book_user.courier_service})</td>
                    </tr>
                    <tr>
                        <td>Metode Pembayaran</td>
                        <td>${data.book_user.payment_method} (dicek manual)</td>
                    </tr>
                    <tr>
                        <td>Kode Unik</td>
                        <td>Rp(${data.book_user.unique_code})</td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td class="tred-bold">${rupiahFormat(data.total_payment)}</td>
                    </tr>
                    </tbody>
                    </table>
                </div>`;

                return html;
            });
        });
    });

    //#region  Confirmed payment - Konfirmasi pembayaran
    $('.status-confirm-payment ,.status-cancel-upload').on('click', function(event) {
        let path = new RegExp('status-confirm-payment|status-cancel-upload');
        let exec = path.exec(this.className)[0];
        let confirmText;

        switch (exec) {
            case 'status-confirm-payment':
                confirmText = 'Apakah anda yakin ingin menkonfirmasi pembayaran tersebut ?';
                break;
            case 'status-cancel-upload':
                confirmText = 'Apakah anda yakin ingin membatalkan bukti pembayaran tersebut ?';
            break;
        }

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                let datas  = {
                    _token       : csrfToken,
                    _method      : 'PATCH',
                    update       : exec,
                }

                let invoiceParent = $(this).parents('.status-invoice');
                let invoice       = invoiceParent.attr('id');

                $.post(`/api/status/${invoice}`, datas, response => {
                    if (response.status == 'success') {
                        invoiceParent.remove();
                        alertMessage(response.message);
                    }
                });
            }
        });
    });
    //#endregion Confirmed payment - Konfirmasi pembayaran

    $('.status-on-delivery').on('click', function() {
        let invoice = $(this).parents('.status-invoice');
        let html =
        `<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-confirm-content">
                    <div class="modal-confirm-body">
                        <div class="text-left">Masukan nomer resi dari barang tersebut dengan benar</div>
                        <div class="form-group mt-4">
                            <input type="text" class="form-control-custom" name="resi_number">
                            <input type="hidden" name="update" value="status-on-delivery">
                        </div>
                        <div class="modal-confirm-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></div>
                    </div>
                    <div class="modal-confirm-buttons">
                        <button id="status-on-delivery-form" class="modal-confirm-accept" type="button">Kirim</button>
                        <button class="modal-confirm-cancel" type="button" data-dismiss="modal" aria-label="Close">Batal</button>
                    </div>
                </div>
            </div>
        </div>`;

        let modalConfirmLength = $('#modal-confirm').length;

        if (modalConfirmLength == 0) $('body').prepend(html);

        $('#modal-confirm').modal('show');

        $('#status-on-delivery-form').on('click', function(event) {
            event.preventDefault();

            let validations = [
                {
                    input: 'input[name=resi_number]',
                    inputName: 'Nomer Resi',
                    rules: 'required'
                },
            ];

            validator(validations, success => {
                if (success) {
                    let inputResiValue = $('input[name=resi_number]').val();

                    let datas = {
                        '_method'    : 'PATCH',
                        '_token'     : csrfToken,
                        'resi_number': inputResiValue,
                        'update'     : 'status-on-delivery',
                    }

                    $.post(`/api/status/${invoice.attr('id')}`, datas, response => {
                        if (response.status == 'success') {
                            $('#modal-confirm').modal('hide');
                            invoice.remove();
                            alertMessage(response.message);
                        }
                    });
                }
            });
        });
    });

    // #region Tracking packages - Lacak paket
    $('.tracking-packages').on('click', function() {
        console.log($(this).data('resi'));
        console.log($(this).data('courier'));

        let spinnerHtml  = `<div id="tracking-spinner" class="d-flex justify-content-center py-4">`;
            spinnerHtml += `<div class="spin"></div>`;
            spinnerHtml += `</div>`;

        let datas        = {
            waybill: $(this).data('resi'),
            courier: $(this).data('courier'),
            key    : 'ce496165f4a20bc07d96b6fe3ab41ded',
        };

        bootStrapModal('Informasi Pengiriman', 'modal-lg', () => {
            return `<div id="tracking-modal-content">${spinnerHtml}</div>`;
        });

        $.post('https://pro.rajaongkir.com/api/waybill', datas)
        .done(response => {
            let result = response.rajaongkir.result;
            let trackingPackagesBody =
            `<div class="mb-3">
                <h5>Alamat Pengiriman</h5>
                <div>
                    <div class="tbold">${result.details.receiver_name}</div>
                    <div>${result.details.receiver_address1}</div>
                    <div>${result.details.receiver_address2}</div>
                    <div>${result.details.receiver_address3}</div>
                </div>
            </div>
            <div class="mt-4">
                <h5>Manifes</h5>
                <div id="tracking-package-manifest" class="text-grey mt-3">
                </div>
            </div>`;

            let contentLength = $('#tracking-modal-content').children().length; // Default 1

            if (contentLength == 1) {
                $('#tracking-modal-content').html(trackingPackagesBody);
            }

            let manifestHtml        = result.manifest.map(manifest => {
                return `<div class="manifest-circle">
                    <div>${manifest.manifest_date} ${manifest.manifest_time}</div>
                    <div class="text-right">${manifest.manifest_description}</div>
                </div>`;
            });

            $('#tracking-package-manifest').html(manifestHtml.join(''));
            $('.manifes-circle').last().addClass('manifes-circle-last');
        })
        .fail(function(xhr) {
            let message = xhr.responseJSON.rajaongkir.status.description;
            let html    = `<div><h5 class="text-grey tbold">${message}</h5></div>`;

            $('#tracking-modal-content').html(html);
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

    //#region Income
    $('#income-today, #income-this-month, #income-all').on('click', function() {
        $('html, body').animate({
            scrollTop: $("#income-results-header").offset().top
        }, 800);
    });
    //#endregion Income

    //#region Cart
    const cartCheck = () => {
        $('.cart-check').on('change', function() {
            let cartCheck      = this;
            let paymentAmount  = $('#cart-amounts');
            let amount         = $(cartCheck).parents('.white-content-header-2').next().find('.cart-amount-req');

            let checkedAll = $('.cart-check').toArray();
                checkedAll = checkedAll.filter(element => element.checked);

            if (checkedAll.length >= 1) {
                let checked           = $('.cart-check');
                    checked           = checked.toArray().filter(element => element.checked);
                let checkedPriceValue = checked.map(element => {
                    let amount    = $(element).parents('.white-content').find('.cart-amount-req').val();
                    let bookPrice = $(element).parents('.white-content').find('.cart-book-price').data('price');

                    let results = bookPrice * amount;

                    return results;
                });
                checkedPriceValue = checkedPriceValue.reduce((total, value) => total + value, 0);

                let price = rupiahFormat(checkedPriceValue);

                $('#cart-total-payment').val(price);
            } else {
                $('#cart-total-payment').val('Rp0');
            }

            if (cartCheck.checked) {
                $('#cart-amounts').val(parseInt(paymentAmount.val()) + parseInt(amount.val()));
            } else {
                $(cartCheck).parents('.white-content').find('.cart-note-cancel').trigger('click');
                $('#cart-amounts').val(parseInt(paymentAmount.val()) - parseInt(amount.val()));
            }

            $('.cart-amount').on('click', function(event) {
                event.stopImmediatePropagation();

                let check           = $(this).parents('.white-content').find('.cart-check');
                let isChecked       = check.is(':checked');

                if (isChecked) {
                    setTimeout(() => {
                        let checkPlusButton = $(event.target).hasClass('fa-plus-circle');
                        let amount          = $(this).prev().find('.cart-amount-req');
                        let totalStock      = $(this).prev().find('.cart-total-stock');
                        let amountPlus      = parseInt(amount.val()) + 1;
                        let amountSub       = parseInt(amount.val()) - 1;
                        let paymentAmount   = $('#cart-amounts');
                        let paymentPlus     = parseInt(paymentAmount.val()) + 1;
                        let paymentSub      = parseInt(paymentAmount.val()) - 1;
                        let paymentPrice    = $('#cart-total-payment').val().replace(/[^0-9]/g, '');
                            paymentPrice    = parseInt(paymentPrice);
                        let customerId      = $(this).parents('.white-content').find('.cart-delete').data('id');

                        if (checkPlusButton && amount.val() >= 1) {
                            if (totalStock.val() != 0) {
                                totalStock.val(parseInt(totalStock.val()) - 1);

                                let cartCheckValue = check.val().split('-');
                                cartCheckValue[2]  = amountPlus;
                                cartCheckValue     =  cartCheckValue.join('-');

                                check.attr('value', cartCheckValue);
                                amount.val(amountPlus);
                                paymentAmount.val(paymentPlus);

                                let datas = {
                                    _token : csrfToken,
                                    _method: 'PATCH',
                                    amount : amountPlus
                                };

                                $.post(`/carts/${customerId}`, datas, function(response) {
                                    console.log(response);
                                });
                            }
                        } else if (amount.val() != 1){
                            totalStock.val(parseInt(totalStock.val()) + 1);

                            let cartCheckValue = check.val().split('-');
                            cartCheckValue[2]  = amountSub;
                            cartCheckValue     =  cartCheckValue.join('-');

                            check.attr('value', cartCheckValue);
                            amount.val(amountSub);
                            paymentAmount.val(paymentSub);

                            let datas = {
                                _token : csrfToken,
                                _method: 'PATCH',
                                amount : amountSub
                            };

                            $.post(`/carts/${customerId}`, datas);
                        }

                        let checkedAll      = $('.cart-check').toArray();
                            checkedAll      = checkedAll.filter(element => element.checked);

                        let checkedPriceValue = checkedAll.map(element => {
                            let elementParent = $(element).parents('.white-content');
                            let price         = elementParent.find('.cart-book-price').data('price');
                            let amount        = parseInt(elementParent.find('.cart-amount-req').val());

                            let result = price * amount;

                            return result;
                        });

                        let paymentTotal = checkedPriceValue.reduce((total, value) => total + value, 0);

                        $('#cart-total-payment').val(rupiahFormat(paymentTotal));
                    }, 300);
                }
            });
        });
    };

    cartCheck();

    // Disable klik pada saat memilih jenis buku
    $('.cart-book-version').on('mousedown', function(event) {
        let check           = $(this).parents('.white-content').find('.cart-check');
        let isChecked       = check.is(':checked');

        if (!isChecked) {
            event.preventDefault();
            this.blur();
            window.focus();
        }
    });

    $('#checked-all').on('click', function() {
        let checkedAll  = $(this).is(':checked');
            let checkboxes = $('.cart-check');

        for (const key in checkboxes) {
            if (checkboxes.hasOwnProperty.call(checkboxes, key)) {
                const element = checkboxes[key];

                if (element.type == 'checkbox') {
                    if (!element.checked) {
                        if (checkedAll) {
                            $(element).trigger('click');
                        }
                    } else if (!checkedAll) {
                        $(element).trigger('click');
                    }
                }
            }
        }
    });

    $('.cart-note').on('click', function() {
        let note      = $(this);
        let isChecked = note.parents('.white-content').find('.cart-check').is(':checked');

        if (isChecked) {
            let html =
            `<div class="form-group d-flex w-50">
                <input type="text" class="cart-note-input form-control-custom mr-2" placeholder="Tulis catatan">
                <button type="button" class="cart-note-cancel btn-none tred-bold d-inline">Batal</button>
            </div>`;

            note.hide();
            $(this).after(html);

            $('.cart-note-cancel').on('click', function(event) {
                event.stopImmediatePropagation();

                let check = note.parents('.white-content').find('.cart-check');
                let newAttributeValue = check.attr('value').split('-').slice(0, 2).join('-');

                check.attr('value', newAttributeValue);
                note.show();
                $(this).parent().hide();
                $(this).parent().remove()
            });
        }
    });

    $('#checkout-button').on('click', function(event) {
        let checks = $('.cart-check').map(function(key, check) {
            return $(check).is(':checked');
        });

        checks = checks.toArray();

        let checkEvery = checks.every(check => check == false);

        if (checkEvery) {
            event.preventDefault();
        }
    });

    $('.cart-delete').on('click', function() {
        let dataId      = $(this).data('id');
        let confirmText = 'Apakah anda yakin ingin menghapusnya dari daftar ?';

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                let datas = {
                    _token: csrfToken,
                    _method: 'DELETE',
                };

                ajaxJson('POST', `/carts/${dataId}`, datas, response => {
                    if (response.delete) {
                        let messageText = 'Berhasil menghapus buku dari daftar';

                        alertMessage(messageText);
                        $(this).parents('.white-content').remove();
                    }
                });
            }
        });
    });

    $('#cart-checkout').on('submit', function(event) {
        let cartsChecked               = $('.cart-check:checked');
        let cartsIsChecked             = $('.cart-check').is(':checked');
        let selectedCarts              = cartsChecked.parents('.white-content').find('.cart-book-version');
        let bookVersionChoiseIsChecked = selectedCarts.length != 0
            ? selectedCarts.toArray().every((selectedCart) => selectedCart.value != "")
            : false;

        if (!cartsIsChecked || !bookVersionChoiseIsChecked) {
            event.preventDefault();

            selectedCarts.toArray().forEach(cart => {
                if (cart.value == '') {
                    let message = $(cart).siblings('.cart-book-version-error').length;

                    if (message == 0) {
                        $(cart).after('<div class="cart-book-version-error tred-bold">Jenis buku wajib diisi.</div>');
                    }
                }
            });
        } else {
            cartsChecked.toArray().forEach(cart => {
                let bookVersion = $(cart).parents('.white-content').find('.cart-book-version').val() == null
                    ? ''
                    : (selectedCarts.val() == "hard_cover" ? '-0' : '-1');

                let noteValue = $(cart).parents('.white-content').find('.cart-note-input').val();
                    noteValue = noteValue == undefined || noteValue == '' ? '' : `-${noteValue}`

                let newCartsCheckedValue = cart.value + bookVersion;

                $(cart).attr('value', newCartsCheckedValue.split('-').slice(0, 2).join('-') + noteValue);
            });

        }
    });
    //#endregion Cart

    //#region Checkout
    $('input[name=customer]').on('change', function() {
        let customerAddressIsChecked = $('input[name=courier_name]').is(':checked');

        $('#checkout-courier-service').remove();

        if (customerAddressIsChecked) {
            $('input[name=courier_name]:checked').trigger('change');
            $('input[name=customer]').attr('disabled', true);
        }
    });

    $('input[name=courier_name]').on('change', function() {
        let customersAddress = $('.user-customer');
        let checkedCustomer  = $('input[name=customer]').is(':checked');

        $('#checkout-courier-service').remove();
        $('#checkout-courier-choise-title').children(':nth-child(2)').remove();

        if (!checkedCustomer) {
            let html = `<span id="error-courier-choise" class="tred-bold">Pilih alamat pengiriman</span>`;

            if ($('#error-courier-choise').length == 0) {
                $('#checkout-courier-choise-title').append(html);
            }
        } else {
            if (customersAddress.length == 0) {
                $('#user-create-customer').trigger('click');
            } else {
                let customer      = $('input[name=customer]:checked').parents('.user-customer');
                let districtId    = customer.find('.customer-district').attr('data-district');
                let courierValue  = $('input[name=courier_name]:checked').val();
                let spinnerHtml   = `<div id="spinner" class="mr-4 pr-3 py-4 d-flex justify-content-center">`;
                    spinnerHtml  += `<div class="spin"></div>`;
                    spinnerHtml  += `</div>`;
                let spinnerLength = $('#spinner').length;

                let totalWeight = $('.customer-book').map(function() {
                    return $(this).find('.book-weight').text();
                });

                totalWeight = totalWeight.toArray();
                totalWeight = totalWeight.reduce((total, value) => {
                    return parseInt(total) + parseInt(value);
                });

                if (spinnerLength == 0) $('#checkout-courier-choise').after(spinnerHtml);

                $('input[name=courier_name]').attr('disabled', true);

                let datas = {
                    key: 'ce496165f4a20bc07d96b6fe3ab41ded',
                    origin: '317', // Cimenyan
                    originType: 'subdistrict',
                    destination: districtId,
                    destinationType: 'subdistrict',
                    weight: totalWeight,
                    courier: courierValue,
                };

                $.post('https://pro.rajaongkir.com/api/cost', datas, function(response) {
                    let costs = response.rajaongkir.results[0].costs;
                    let html  = ``;

                    $('#spinner').remove();

                    if (costs.length == 0) {
                        html +=
                        `
                        <div id="checkout-courier-service" class="mt-2">
                        <div class="tbold">Ekspedisi tidak tersedia</div>
                        </div>
                        `;
                    } else {
                        html += '';
                        costs.forEach(function(cost) {
                            let costValue        = cost.cost[0].value;
                            let patt             = new RegExp('hari', 'i');
                            let estimatedArrival = cost.cost[0].etd;
                            let textHari         = patt.test(estimatedArrival);

                            estimatedArrival = textHari ? estimatedArrival.toLowerCase() : `${estimatedArrival} Hari`;

                            html += `<option value="${costValue}-${cost.service}">${cost.description} - ${estimatedArrival}</option>`;
                        });

                        html =
                        `
                        <div id="checkout-courier-service" class="mt-2">
                            <div class="tbold">Pilih Pengiriman</div>
                            <div class="mt-2">
                                <select id="select-courier-service" class="custom-select w-25" name="courier_service">
                                    ${html}
                                </select>
                            </div>
                        </div>`;
                    }

                    if ($('#checkout-courier-service').length == 0) {
                        $('#checkout-courier-choise').after(html);

                        let courierOptions     = $('#select-courier-service');
                        let courierOptionFirst = courierOptions.first().val();
                        let costFirst          = courierOptionFirst.split('-')[0];
                        let courierPriceHtml   = `<span id="checkout-courier-price" class="ml-2 text-grey">${rupiahFormat(costFirst)}</span>`;
                        let totalPaymentText   = $('#checkout-total-payment-text');
                        let totalPayment       = parseInt(totalPaymentText.data('price')) + parseInt(costFirst);

                        totalPaymentText.text(rupiahFormat(totalPayment));
                        courierOptions.after(courierPriceHtml);
                        $('#checkout-shipping-price').text(rupiahFormat(costFirst));
                        $('#checkout-shipping-cost').attr('value', costFirst);

                        $('#select-courier-service').on('change', function() {
                            let selectedValue      = $(this).children('option:selected').val();
                            let selectedCost       = selectedValue.split('-')[0];
                            let rupiahSelectedCost = rupiahFormat(selectedCost);
                            let totalPayment       = parseInt(selectedCost) + parseInt(totalPaymentText.data('price'));

                            totalPaymentText.text(rupiahFormat(totalPayment));
                            $('#checkout-courier-price').text(rupiahSelectedCost);
                            $('#checkout-shipping-price').text(rupiahSelectedCost);
                            $('#checkout-shipping-cost').attr('value', selectedCost);
                        });
                    }


                })
                .done(function() {
                    $('input[name=customer]').attr('disabled', false);
                    $('input[name=courier_name]').attr('disabled', false);
                });
            }
        }
    });

    //#region Checkout Store
    $('#checkout-form').on('submit', function(event) {
        let customerAddressChecked = $('input[name=customer]').is(':checked');
        let courierChoiseChecked   = $('input[name=courier_name]').is(':checked');
        let paymentMethodChecked   = $('input[name=payment_method]').is(':checked');
        let courierServiceLength   = $('#checkout-courier-service').length;

        // Validasi
        if (!customerAddressChecked || !courierChoiseChecked || !paymentMethodChecked || courierServiceLength == 0) {
            event.preventDefault();

            if (courierServiceLength == 0) {
                let html = `<span id="error-courier-choise" class="tred-bold">Pilih alamat pengiriman</span>`;

                if ($('#error-courier-choise').length == 0) {
                    $('#checkout-courier-choise-title').append(html);
                }
            }

            if (!customerAddressChecked) {
                let html = `<span id="error-customer-address" class="tred-bold">Pilih alamat pengiriman</span>`;

                if ($('#error-customer-address').length == 0) {
                    $('#user-customer-title').parent().after(html);
                }
            }

            if (!paymentMethodChecked) {
                let html = `<span id="error-payment-method" class="tred-bold">Pilih metode pembayaran</span>`;

                if ($('#error-payment-method').length == 0) {
                    $('#payment-method-title').after(html);
                }
            }
        }
    })
    //#endregion Checkout Store
    //#endregion Checkout
}); // End of onload Event
