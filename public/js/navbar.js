// JS untuk Navbar
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

        if (keywordValue != "" || keywordValue != 0)  {

            let datas = { keywords: keywordValue }

            ajaxJson('GET', '/books/search', datas, response => {
                let html = '';

                response.books.forEach(book => {
                    html += `<a href="/books/${book.id}" class="nav-book-search-link">${book.name}</a>`;
                });

                $('.nav-book-search-values').html(html);
                $('.nav-book-search').show();
            });

        } else {
            $('.nav-book-search').hide();
        }
    });

    $('.search-form').on('submit', function(event) {
        let searchValue = $(this).find('input[name=keywords]').val();

        if (searchValue == "" || searchValue == 0) {
            event.preventDefault();
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

    // Book Show
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
    //#end Function - Book show click

    bookShowClick();

    //#region Tambah gambar buku
    $('#book-image-store').on('click', function() {
        let secondImages    = $('.book-show-images').children(':not(:first-child)').find('img');
        let secondImageHtml = [];

        for (const key in secondImages) {
            if (secondImages.hasOwnProperty.call(secondImages, key)) {
                const element = secondImages[key];

                let src  = $(element).attr('src');
                let html = `<div class="col-3"><img src="${$(element).attr('src')}" class="book-show-image"></div>`;

                if (src != undefined) {
                    secondImageHtml.push(html);
                }
            }
        }

        bootStrapModal('Tambah Gambar Buku', 'modal-md', () => {
            let primaryImageBook = $('#primary-book-image').attr('src');
            let html =
            `
            <div class="book-modal-image row mb-4">
                <div class="col-3"><img src="${primaryImageBook}" class="w-100"></div>
                ${secondImageHtml}
            </div>
            <form id="add-book-image-form" action="{{ route('add.book.images', array('book' => $book->id)) }}" enctype="multipart/form-data" method="POST">
                <div class="form-group">
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg">
                </div>
                <button type="submit" class="btn btn-red">Tambah Foto</button>
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>
            `;

            return html;
        });

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
                    console.log('success');
                    ajaxForm('POST', this, `/books/add-book-images/${$('#book-show').data('id')}`, response => {
                        if (!response.errors) {
                            $(this).trigger('reset');

                            let messageText         = 'Berhasil menambah gambar buku';
                            let src                 = `${window.location.origin}/storage/books/book_images/${response.image}`;
                            let html                = `<div class="book-show-click"><img src="${src}" class="book-show-image"></div>`;
                            let bookShowImageLength = $('.book-show-click').length + 1;

                            $('#custom-modal').modal('hide');
                            alertMessage(messageText);
                            $('.book-show-images').append(html);

                            console.log(bookShowImageLength);

                            if (bookShowImageLength > 3) {
                                $('#book-image-store').parent().remove();
                            }

                            bookShowClick();
                        }
                    })
                }
            });
        });
    });
    //#endregion Tambah gambar buku

    //#region Edit gambar
    $('#book-image-edit').on('click', function() {
        bootStrapModal('Edit Gambar Buku', 'modal-md', () => {
            let html = `<div><img></div>`;

            return html;
        });
    });
    //#endregion Edit gambar
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
            let userAddress       = $('#user-address').text() == '-' ? '' : $('#user-address').text();
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
                            $('#user-address').text(user.address);
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
    $('.upload-payment-button').on('click', function() {
        let buttonModalUpload = $(this);

        bootStrapModal('Upload Bukti Pembayaran', 'modal-sm', () => {
            let html =
            `<div class="upload-payment">
                <form id="upload-payment-form" action="{{ route('book-users.update', array('book_user' => 3)) }}" enctype="multipart/form-data" data-id="" method="post">
                    <div class="upload-payment-select-image">
                        <button type="button" id="upload-payment-cancel" class="btn-none d-none"><i class="fa fa-times" aria-hidden="true"></i></button>
                        <label>
                            <input id="upload-payment-file" type="file" class="d-none" name="upload_payment" accept=".jpg, .jpeg, .png">
                            <i id="upload-payment-plus-logo" class="fa fa-plus" aria-hidden="true"></i>
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="_token" value="${csrfToken}">
                        </label>
                        <div>
                            <img id="upload-payment-image" class="w-100 d-none" src="" alt="gambar">
                        </div>
                        <button id="upload-payment-submit-button" class="btn btn-red d-none mt-3" type="submit">Kirim</button>
                    </div>
                </form>
                <div class="upload-payment-information">
                    <div class="text-grey bold">
                        <div>File harus berupa jpg, jpeg, png</div>
                        <div>Maksimal 2mb</div>
                    </div>
                </div>
            </div>`;

            return html;
        });

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

        $('#upload-payment-form').on('submit', function(event) {
            event.preventDefault();

            let dataId = buttonModalUpload.data('id');
            let datas  = [{status: 'uploadImage'}];

            ajaxForm('POST', this, `/book-users/${dataId}`, function (response) {
                if (response.success) {
                    let messageText = 'Berhasil mengupload bukti pembayaran';
                    let html        = `<span class="btn btn-grey btn-sm-0 hd-14">Sudah mengirim bukti</span>`;

                    $('#upload-payment-file').val('');
                    $('.close').trigger('click');
                    $('#upload-payment-cancel').trigger('click');
                    alertMessage(messageText);
                    buttonModalUpload.parents('.borbot-gray-bold').prev().find('.upload-payment-failed').remove();
                    buttonModalUpload.parent().append(html);
                    buttonModalUpload.remove();
                } else {
                    let errors = response.errors.upload_payment;
                    alert(errors);
                }
            }, datas);
        });
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

                ajaxJson('POST', `/book-users/${dataId}`, datas, () => {
                    $(this).parents('.upload-payment-value').remove();
                    let messageText = 'Berhasil membatalkan pembelian';

                    alertMessage(messageText);
                });
            }
        });
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

        modalConfirm(confirmText, accepted => {
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
    $('.confirm-process').on('click', function () {
        let confirmText = 'Apakah anda yakin akan menkonfirmasi pengiriman tersebut?';
        let dataId      = $(this).data('id');
        let datas       = {
            _token : csrfToken,
            _method: 'PATCH',
            status : 'orderOnDelivery'
        }

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                ajaxJson('POST', `/book-users/${dataId}`, datas, () => {
                    let messageText = 'Berhasil menkonfirmasi pengiriman dan telah sampai';

                    alertMessage(messageText);
                    setTimeout(() => $(this).parents('.uploaded-payment').remove(), 200);
                    addAndSubtractStatusNotification();
                });
            }
        })
    });
    //#endregion Confirmed shipped - Konfirmasi pengiriman

    $('.confirm-shipping').on('click', function () {
        let confirmText = 'Apakah anda yakin akan menkonfirmasi pengiriman tersebut?';
        let dataId      = $(this).data('id');
        let datas       = {
            _token : csrfToken,
            _method: 'PATCH',
            status : 'arrived'
        }

        modalConfirm(confirmText, accepted => {
            if (accepted) {
                ajaxJson('POST', `/book-users/${dataId}`, datas, () => {
                    let messageText = 'Berhasil menkonfirmasi pengiriman dan telah sampai';

                    alertMessage(messageText);
                    setTimeout(() => $(this).parents('.uploaded-payment').remove(), 200);
                    addAndSubtractStatusNotification();
                });
            }
        })
    });

    // #region Tracking packages - Lacak paket
    $('.tracking-packages').on('click', function() {
        let dataInvoice = $(this).data('invoice').toString();

        let spinnerHtml  = `<div id="tracking-spinner" class="d-flex justify-content-center pb-4">`;
            spinnerHtml += `<div class="spin"></div>`;
            spinnerHtml += `</div>`;
        let modalLength  = $('tracking-packages-modal').length;

        let datas        = {
            waybill: '030200010250720',
            courier: 'jne',
            key    : 'ce496165f4a20bc07d96b6fe3ab41ded',
        };

        $(this).attr('data-target', '#tracking-packages');
        $(this).attr('data-toggle', 'modal');

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
        'cancelUploadImage', 'Berhasil membatalkan unggahan bukti pembayaran');
    //#end region cancel payment

    // #endregion Waiting for payment - menunggu pembayaran

    //#region Income
    $('#income-today, #income-this-month, #income-all').on('click', function() {
        $('html, body').animate({
            scrollTop: $("#income-results-header").offset().top
        }, 800);
    });
    //#endregion Income
}); // End of onload Event
