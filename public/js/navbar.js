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
    formDisableSubmit('#form-login', 'input');
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
                        backendMessage($('#login-title'), [response.message]);
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

    // ajaxBookPurchaseDeadlineDestroy();

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

            $.post(`/wishlists/${dataId}`, datas);
        }
    });

    //#region Function - Book show click
    const bookShowClick = () => {
        $('.book-show-click').on('click', function() {
            let clickedSrc =  $(this).find('img').attr('src');

            $('.book-show-image-active').removeClass('book-show-image-active');
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
                ajaxForm('POST', this, `/book_images/${$('#book-show').data('id')}`, response => {
                    if (!response.errors) {
                        $(this).trigger('reset');

                        let messageText         = 'Berhasil menambah gambar buku';
                        let src                 = `${window.location.origin}/storage/books/book_images/${response.image}`;
                        let html                =
                        `<div class="col-3">
                            <div class="book-show-click" data-id="${response.book_image.id}">
                                <img class="book-show-image-click" src="${src}">
                                <button class="book-image-delete btn-none"><i class="fa fa-times"></i></button>
                            </div>
                        </div>`;

                        let bookShowImageLength = $('.book-show-click').length + 1;

                        $('#custom-modal').modal('hide');
                        alertMessage(messageText);
                        $('.book-show-images').append(html);

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
                    if ($('.book-show-image-active').attr('id') == undefined) {
                        ajaxForm('POST', this, `/book_images/${clickedDataId()}/update`, response => {
                            if (response.update) {
                                let message = 'Berhasil mengedit gambar buku';
                                let src     = `${window.location.origin}/storage/books/book_images/${response.src}`;

                                alertMessage(message);
                                $(this).trigger('reset');
                                $('.book-show-image-active').find('img').attr('src', src);
                                $('#primary-book-image').attr('src', src);
                            } else {
                                let afterMessage = $('.book-show-images');

                                backendMessage(afterMessage, response.errors)
                            }
                        });
                    } else {
                        let confirmText = 'Apakah anda yakin ingin mengubah gambar utama ?';

                        modalConfirm(confirmText, accepted => {
                            if (accepted) {
                                let bookId = $('#primary-book').data('id');

                                ajaxForm('POST', this, `/book_images/${bookId}/update-main`, response => {
                                    console.log(response);
                                    if (response.status == 'success') {
                                        alertMessage(response.message);
                                        $('#primary-book-image').attr('src', response.data.image);
                                    }
                                });
                            }
                        });
                    }
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
            let message = 'Apakah anda yakin ingin menghapus gambar buku tersebut ?';

            modalConfirm(message, accepted => {
                if (accepted) {
                    let data = {
                        _token : csrfToken,
                        _method: 'DELETE',
                    }

                    $.post(`/book_images/${dataId}/delete`, data, response => {
                        if (response.delete) {
                            let prevParentImage = $(this).parents('.book-show-click').parent().prev();
                            let prevImageSrc    = prevParentImage.find('img').attr('src');
                            let message         = 'Berhasil menghapus gambar buku';

                            alertMessage(message);
                            $('#primary-book-image').attr('src', prevImageSrc);
                            prevParentImage.find('.book-show-click').addClass('book-show-image-active');
                            $(this).parents('.book-show-click').parent().remove();
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

    formDisableSubmit('#book-add-discount-modal-form, #book-add-stock-modal-form', 'input');

    //#endregion Book Show

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

    //#region User store
    formDisableSubmit('#user-store-form', 'input, select');

    $('#user-store-form').on('submit', function(event) {
        event.preventDefault();

        let validations = $(this).children().find('input, select').toArray();

        validations  = validations.map(input => {
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

        validator(validations, success => {
            if (success) this.submit();
        });
    });
    //#endregion User store


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
    const userDeletePhoto = () => {
        $('#user-delete-photo-form').on('submit', function (e) {
            e.preventDefault();

            let message = 'Apakah anda yakin ingin menghapus foto profile ?';

            modalConfirm(message, accepted => {
                if (accepted) this.submit();
            });
        });
    }

    userDeletePhoto();

    $('#user-add-photo').on('click', function() {
        let profileImage = $('#user-show-profile').attr('src');
        let dataId       = $(this).data('id');
        let buttonText   = $(this).text() == 'Edit Foto' ? 'Ubah' : 'Tambah';
        let headerText   = $(this).text() == 'Edit Foto' ? 'Edit Foto' : 'Tambah Foto';

        let html         =
        `<div class="user-modal">
            <img id="user-modal-image" class="profile-img w-100" src="${profileImage}">
        </div>
        <form id="user-add-photo-profile-form" action="/users/${dataId}/add-photo-profile" method="post" enctype="multipart/form-data">
            <input id="user-image" class="mt-5" name="image" type="file" accept="image/png, image/jpeg, image/jpg">
            <div class="text-right mt-4">
                <button id="user-add-photo-form" class="btn btn-outline-danger">${buttonText}</button>
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
                if (success) this.submit();
            });
        });

    });
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
                    <button class="cursor-disabled btn btn-outline-danger" type="submit" disabled>Ubah</button>
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>`;

            return html;
        })

        formDisableSubmit('#user-change-password', 'input:not([type=hidden])')

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
            let userDateOfBirth   = $('#user-date-of-birth').data('date');
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
                    <label for="tanggal-lahir">Tanggal Lahir <small class="text-grey">(boleh kosong)</small></label>
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

            let validations = [
                {
                    input    : '#nama-awal',
                    inputName: 'Nama awal',
                    rules    : 'required,min:3',
                },
                {
                    input    : '#nama-akhir',
                    inputName: 'Nama akhir',
                    rules    : 'required,min:3',
                },
                {
                    input    : '#jenis-kelamin',
                    inputName: 'Jenis kelamin',
                    rules    : 'required',
                },
                {
                    input    : '#alamat',
                    inputName: 'alamat',
                    rules    : 'nullable,min:10',
                },
                {
                    input    : '#nomer-handphone',
                    inputName: 'Nomer handphone',
                    rules    : 'nullable,min:9,max:15',
                },
            ];

            validator(validations, success => {
                if (success) this.submit();
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
            `<form id="user-change-email-form" action="/users/${dataId}/change-email" method="POST">
                <div class="form-group">
                    <label>Email Lama</label>
                    <input id="user-old-email" type="text" class="form-control-custom disb" value="${oldEmail}" disabled>
                </div>
                <div class="form-group">
                    <label for="email_lama">Masukan Email Lama</label>
                    <input id="email_lama" name="email_lama" type="email" class="form-control-custom" autocomplete="off">
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
                    <button class="cursor-disabled btn btn-outline-danger" type="submit" disabled>Ubah</button>
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="${csrfToken}">
            </form>`;

            return html;
        });

        formDisableSubmit('#user-change-email-form', 'input:not([type=hidden])')

        $('#user-change-email-form').on('submit', function(event) {
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
                        if (response.status == 'fail') {
                            let parentPrev = $(this).parent().prev();

                            backendMessage(parentPrev, response.message);
                            $('.alert-messages').addClass('w-90 mx-auto');
                        } else {
                            $('#custom-modal').modal('hide');
                            alertMessage(response.message)
                        }
                    });
                }
            });
        });

    });
    //#endregion User change email

    $('#gambar_sampul_buku').on('change', function(event) {
        $('#example-book-text').remove();
        changeInputPhoto('book-show-image', 'gambar_sampul_buku');
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

    const formHtmlCustomer = (formId, action, method, buttonText, methodRequest = '') => {
        let csrfToken           = $('meta[name="csrf-token"]').attr('content');
        let attrDisabled        = buttonText == 'Tambah' ? 'disabled' : '';
        let cursorDisabledClass = buttonText == 'Tambah' ? 'cursor-disabled' : '';
        methodRequest           = methodRequest != '' ? `<input type="hidden" name="_method" value="${methodRequest}">` : '';

        let html =
        `
        <form id="${formId}" action="${action}" method="${method}" class="mt-4">
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
                <input id="user-city-district-search" type="text" class="form-control-custom book-edit-inp" autocomplete="off">
                <input id="user-city-or-district" type="hidden" name="kota_atau_kecamatan">
                <div id="user-hidden-address" class="user-address">
                </div>
            </div>
            <div class="form-group mx-auto mt-4">
                <label for="nomer_handphone">Nomer Handphone</label>
                <input id="user-customer-phone" type="text" name="nomer_handphone" class="form-control-custom book-edit-inp">
            </div>
            <div class="form-group mt-4 text-right">
                <button class="btn btn-outline-danger ${cursorDisabledClass}" type="submit" ${attrDisabled}>${buttonText}</button>
            </div>
            ${methodRequest}
            <input type="hidden" name="_token" value="${csrfToken}">
        </form>
        `;

        return html;
    };

    const formCityAndDistrictKeyup = () => {
        $('#user-city-district-search').on('keyup', function() {
            if ($(this).val().length >= 3) {
                setTimeout(() => {
                    let data  = {
                        keywords: $(this).val(),
                    };

                    $.get(`/customers/city-or-district`, data, response => {
                        $('#user-hidden-address').html('');
                        $('#user-hidden-address').show();

                        let html;
                        let requestAdressLength  = response.request_address.length;

                        if (requestAdressLength == 0) {
                            html = `<div class="px-2"><div>Data tidak valid</div></div>`;

                            $(this).parents('form').find('button[type=submit]').attr('disabled', true);
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
                            let province       = $(this).data('province');
                            let city           = $(this).data('city');
                            let district       = $(this).data('district');
                            let addressValues  = `${province}-${city}-${district}`;
                            let parentForm     = $(this).parents('form');
                            let inputValues    = parentForm.find('input:not([type=hidden])');
                                inputValues    = inputValues.map((key, input) => input.value).toArray();
                            let exitButtonHtml =
                            `<button type="button" class="user-destination-close btn btn-none">
                                <i class="fa fa-times text-grey"></i>
                            </button>`;

                            let inputValuesIsNotEmpty = inputValues.every((input) => input != '');

                            if (inputValuesIsNotEmpty) {
                                parentForm.find('button[type=submit]').removeClass('cursor-disabled');
                                parentForm.find('button[type=submit]').removeAttr('disabled');
                            }

                            $('#user-city-district-search').addClass('user-destination-input');
                            $('#user-city-district-search').after(exitButtonHtml);
                            $('#user-city-district-search').attr('disabled', true);

                            $('.user-address').hide();
                            $('#user-city-district-search').val($(this).text());
                            $('#user-city-or-district').attr('value', addressValues);

                            $('.user-destination-close').on('click', function() {
                                $(this).remove();
                                parentForm.find('button[type=submit]').addClass('cursor-disabled');
                                parentForm.find('button[type=submit]').attr('disabled', true);
                                $('#user-city-district-search').val('');
                                $('#user-city-district-search').removeClass('user-destination-input');
                                $('#user-city-district-search').attr('disabled', false);
                            });
                        });
                    })
                }, 200);
            } else if ($(this).val().length < 3) {
                $('#user-hidden-address').hide();
            }
        });
    };

    formCityAndDistrictKeyup();

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

            bootStrapModal('Ubah Alamat Utama', 'modal-md', () => {
                let dataId = $(this).data('id');
                let html   = formHtmlCustomer('user-customer-update', `/customers/${dataId}`, 'POST', 'Edit', 'PATCH');

                return html;
            });

            formDisableSubmit('#user-customer-update', 'input:not([type=hidden])');

            let userCustomer         = $(this).parents('.user-customer');
            let userCustomerName     = userCustomer.find('.customer-name').text();
            let userCustomerAddress  = userCustomer.find('.customer-address').text();
            let userCustomerPhone    = userCustomer.find('.customer-phone-number').text();
            let userCustomerProvince = userCustomer.find('.customer-province');
            let userCustomerCity     = userCustomer.find('.customer-city');
            let userCustomerDistrict = userCustomer.find('.customer-district')

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
                    if (success) this.submit();
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

    //#region Customer change main address
    const customerChangeMain = () => {
        $('.user-customer-select-main, .user-customer-main').on('click', function(event) {
            let mainCheck = $(this).hasClass('user-customer-main');

            if (!mainCheck) {
                setTimeout(() => {
                    let modalText = 'Apakah anda yakin ingin menyimpan data tersebut sebagai alamat pengiriman utama ?';
                    modalConfirm(modalText, accepted => {
                        let customerId = $(this).parents('.user-customer').attr('id');

                        if (accepted) {
                            let data = {
                                _token: csrfToken,
                                _method: 'PATCH'
                            }

                            $.post(`/api/customers/${customerId}/change-main-address`, data)
                            .done(() => {
                                let beforemMainAddress = $('.user-customer-main');

                                beforemMainAddress.text('Simpan sebagai utama');
                                beforemMainAddress.removeClass();
                                beforemMainAddress.addClass('user-customer-select-main c-p');


                                $(this).parents('.user-customer').find('.user-customer-delete').appendTo(beforemMainAddress.parents('.user-customer').find('.user-customer-update').parent().parent());
                                $(this).text('Utama');
                                $(this).removeClass('user-customer-select-main');
                                $(this).addClass('user-customer-main');
                            });
                        }
                    });
                }, 200);
            }
        });
    }

    customerChangeMain();
    //#endregion Customer change main address

    //#region User - Customer Create
    const userCustomerCreate = () => {
        $('#user-create-customer').on('click', function() {
            bootStrapModal('Tambah Alamat Pengiriman', 'modal-md', () => {
                let html =  formHtmlCustomer('user-customer-store', '/customers', 'POST', 'Tambah');

                return html;
            });

            formDisableSubmit('#user-customer-store', 'input:not([type=hidden])')
            formCityAndDistrictKeyup();

            $('#user-customer-store').on('submit', function(event) {
                event.preventDefault();

                validator(formCustomerValidations(), success => {
                    if (success) this.submit();
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

    customerUpdate();
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
                    $(this).parents('.upload-payment-value').remove();
                    let messageText = 'Berhasil membatalkan pembelian';

                    alertMessage(messageText);
                })
            }
        });
    })

    //#region Status
    //#region Lihat daftar tagihan
    $('.status-detail').on('click', function() {
        let invoice = $(this).data('invoice');

        $.get(`/status/${invoice}/detail`, response => {
            console.log(response);
            let data       = response.data;
            let failedDate = data.status_date.failed_date;

            const statusCircleHtml = (status, iconHtml, statusName) => {
                return `<div class="status-modal-detail">
                    <div class="status-modal-detail-circle ${status ? 'status-modal-detail-active' : ''}">
                        ${iconHtml}
                    </div>
                    <div class="mt-2 text-center text-grey">
                        <div class="tred-bold">${statusName}</div>
                        <div>${status ?? '-'}</div>
                    </div>
                </div>`;
            }

            bootStrapModal('Detail', 'modal-md', () => {
                delete data.status_date.failed_date;

                let statusHtml = Object.keys(data.status_date).map((key) => {
                    const status = data.status_date[key];
                    let statusName, iconHtml;

                    switch (key) {
                        case 'order_date':
                            statusName = 'Pesan';
                            iconHtml   = `<i class="fas fa-receipt"></i>`;
                            break;
                        case 'payment_date':
                            statusName = 'Pembayaran';
                            iconHtml   = `<i class="fas fa-money-bill-wave"></i>`;
                                break;
                        case 'shipped_date':
                            statusName = 'Dikirim';
                            iconHtml   = `<i class="fas fa-truck"></i>`;
                            break;
                        case 'completed_date':
                            statusName = 'Selesai';
                            iconHtml   = `<i class="far fa-check-circle"></i>`;
                            break;
                    }

                    return statusCircleHtml(status, iconHtml, statusName);
                });

                statusHtml = `<div class="d-flex mt-3 borbot-gray-0 pb-3">${statusHtml.join('')}</div>`

                let html =
                `
                <div>
                    ${statusHtml}
                    <div class="my-3">
                        <h5>Alamat Pengiriman</h5>
                        <div>
                            <div class="tbold">${data.customer.name}</div>
                            <div>${data.customer.phone_number}</div>
                            <div>${data.customer.address}, Kec.${data.district}, ${data.city} ${data.city_type} . ${data.province}</div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>Metode Pembayaran</td>
                        <td>${data.book_user.payment_method} (dicek manual)</td>
                    </tr>
                    <tr>
                        <td>Kurir</td>
                        <td>${data.book_user.courier_name.toUpperCase()} (${data.book_user.courier_service})</td>
                    </tr>
                    <tr>
                        <td>Biaya Pengiriman</td>
                        <td>${rupiahFormat(data.book_user.shipping_cost)}</td>
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

            if (failedDate) {
                let html = statusCircleHtml(failedDate, '<i class="far fa-times-circle"></i>', 'Dibatalkan');
                $('.status-modal-detail').first().after(html);
            }
        });
    });
    //#endregion Lihat daftar tagihan

    //#region Add rating
    $('.status-add-rating').on('click', function() {
        let invoice = $(this).parents('.status-invoice').attr('id');
        let bookId  = $(this).parents('.status-book').attr('id');

        bootStrapModal('Beri Rating Untuk Buku Ini', 'modal-md', () => {
            let starHtml = '';

            for (let i = 1; i <= 5; i++) {
                starHtml +=
                `<label class="status-rating-star">
                    <i class="status-rating-icon mx-auto far fa-star" aria-hidden="true"></i>
                    <input form="status-rating-form" type="radio" name="rating" value="${i}" class="d-none">
                </label>`;
            }

            let html =
            `<div class="status-rating mt-4">
                <div class="d-flex justify-content-center status-rating-stars mt-4">
                    ${starHtml}
                </div>
                <div class="mt-4 text-center w-100 container">
                    <div class="text-grey my-2 text-left">Berikan ulasan anda (opsional)</div>
                    <textarea form="status-rating-form" name="review" id="" rows="5" class="w-100"></textarea>
                </div>
                <div class="mt-4 text-right">
                    <input form="status-rating-form" type="hidden" name="invoice" value="${invoice}">
                    <input form="status-rating-form" type="hidden" name="book_id" value="${bookId}">
                    <button form="status-rating-form" type="submit" class="cursor-disabled btn btn-outline-danger" disabled>Kirim</button>
                </div>
            </div>`

            return html;
        });

        $('.status-rating-star').on('change', function() {
            $('button[form=status-rating-form]').removeClass('cursor-disabled');
            $('button[form=status-rating-form]').removeAttr('disabled');

            let rate = $(this).find('input').val();
            let ratingText;

            if (rate == 5) {
                $('.status-rating-star i').removeClass('far');
                $('.status-rating-star i').addClass('fas');

                ratingText = {
                    5: 'Super Bagus',
                };
            } else {
                $(this).find('i').removeClass('far');
                $(this).find('i').addClass('fas');
                $(this).prevAll().find('i')
                    .removeClass('far')
                    .addClass('fas');

                $(this).nextAll().find('i')
                    .removeClass('fas')
                    .addClass('far');

                ratingText = {
                    4: 'Sangat Baik',
                    3: 'Baik',
                    2: 'Kurang Baik',
                    1: 'Buruk',
                };
            }

            let html = `<h5 id="status-rating-text" class="mt-3 text-center">${ratingText[rate]}</h5>`;

            $('#status-rating-text').length == 0
                ? $('.status-rating-stars').after(html)
                : $('#status-rating-text').text(ratingText[rate]);
        })
    });

    disableMultipleSubmitForm('#status-rating-form', 'button[form=status-rating-form]');

    //#endregion Add rating
    //#endregion Status

    //#region  Confirmed payment - Konfirmasi pembayaran
    $('.status-confirm-payment ,.status-cancel-upload, .status-complete').on('click', function() {
        let path = new RegExp('status-confirm-payment|status-cancel-upload|status-complete');
        let exec = path.exec(this.className)[0];
        let confirmText;

        switch (exec) {
            case 'status-confirm-payment':
                confirmText = 'Apakah anda yakin ingin menkonfirmasi pembayaran tersebut ?';
                break;
            case 'status-cancel-upload':
                confirmText = 'Apakah anda yakin ingin membatalkan bukti pembayaran tersebut ?';
            break;
            case 'status-complete':
                confirmText = 'Apakah anda yakin ingin menyelesaikan orderan tersebut ?';
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
        `<div class="modal fade" id="modal-ondelivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

        let modalConfirmLength = $('#modal-ondelivery').length;

        if (modalConfirmLength == 0) $('body').prepend(html);

        $('#modal-ondelivery').modal('show');

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
                            $('#modal-ondelivery').modal('hide');
                            invoice.remove();
                            alertMessage(response.message);
                        }
                    });
                }
            });
        });

        $('#modal-ondelivery').on('hidden.bs.modal', function(event) {
            event.stopImmediatePropagation();
            $(this).remove();
        });
    });


    // #region Tracking packages - Lacak paket
    $('.tracking-packages').on('click', function() {
        let spinnerHtml  = `<div id="tracking-spinner" class="d-flex justify-content-center py-4">`;
            spinnerHtml += `<div class="spin"></div>`;
            spinnerHtml += `</div>`;

        let datas        = {
            waybill: $(this).data('resi'),
            courier: $(this).data('courier'),
            key    : 'ce496165f4a20bc07d96b6fe3ab41ded',
            _token : csrfToken,
        };

        bootStrapModal('Informasi Pengiriman', 'modal-lg', () => {
            return `<div id="tracking-modal-content">${spinnerHtml}</div>`;
        });

        $.post('/status/shipping-information', datas)
        .done(response => {
            console.log(response)
            if (response.status == 'fail') {
                let html    = `<div><h5 class="text-grey tbold">${response.message}</h5></div>`;

                $('#tracking-modal-content').html(html);
            } else {
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
            }
        })

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

        });
    };

    $('.cart-amount').on('click', function(event) {
        event.stopImmediatePropagation();

        let check           = $(this).parents('.white-content').find('.cart-check');
        let isChecked       = check.is(':checked');

        $(this).parents('.white-content').find('.cart-stock').children('input').addClass('text-grey');

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

                $(this).parents('.white-content').find('.cart-stock').children('input').removeClass('text-grey');

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

                        $.post(`/carts/${customerId}`, datas);
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
    $('input[name=courier_name]').on('change', function() {
        let mainAddress = $('#checkout-customer-main').val();

        if (mainAddress) {
            $('#checkout-courier-service').remove();
            $('#checkout-courier-choise-title').children(':nth-child(2)').remove();

            let districtId    = $('#checkout-district').data('id');
            let courierValue  = $('input[name=courier_name]:checked').val();
            let spinnerHtml   = `<div id="spinner" class="mr-4 pr-3 py-4 d-flex justify-content-center">`;
                spinnerHtml  += `<div class="spin"></div>`;
                spinnerHtml  += `</div>`;
            let spinnerLength = $('#spinner').length;

            if (spinnerLength == 0) $('#checkout-courier-choise').after(spinnerHtml);

            let totalWeight = $('.customer-book').map(function() {
                return $(this).find('.book-weight').text();
            });

            totalWeight = totalWeight.toArray();
            totalWeight = totalWeight.reduce((total, value) => {
                return parseInt(total) + parseInt(value);
            });

            $('input[name=courier_name]').attr('disabled', true);

            let datas = {
                _token         : csrfToken,
                key            : 'ce496165f4a20bc07d96b6fe3ab41ded',
                origin         : '317', // Cimenyan
                originType     : 'subdistrict',
                destination    : districtId,
                destinationType: 'subdistrict',
                weight         : totalWeight,
                courier        : courierValue,
            };

            $.post('/checkouts/cost', datas)
            .done(response => {
                let costs = response.rajaongkir.results[0].costs;

                $('#spinner').remove();

                let html  = ``;
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

                if (costs.length == 0) {
                    html =
                    `
                    <div id="checkout-courier-service" class="mt-2">
                        <div class="tbold">Ekspedisi tidak tersedia</div>
                    </div>
                    `;

                    $('#checkout-courier-choise').after(html);

                } else if ($('#checkout-courier-service').length == 0){
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

                $('input[name=courier_name]').attr('disabled', false);
            })
        }

    });

    //#region Checkout Store
    $('#checkout-form').on('submit', function(event) {
        let courierChoiseChecked   = $('input[name=courier_name]').is(':checked');
        let paymentMethodChecked   = $('input[name=payment_method]').is(':checked');
        let courierServiceLength   = $('#checkout-courier-service').length;

        // Validasi
        if (!courierChoiseChecked || !paymentMethodChecked || courierServiceLength == 0) {
            event.preventDefault();

            if (courierServiceLength == 0) {
                let html = `<span id="error-courier-choise" class="tred-bold">Pilih alamat pengiriman</span>`;

                if ($('#error-courier-choise').length == 0) {
                    $('#checkout-courier-choise-title').append(html);
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

    //#region Checkout - Customer store
    $('#checkout-customer-store').on('submit', function(event) {
        let validations = [
            {
                input    : '#user-customer-name',
                inputName: 'Nama penerima',
                rules    : 'required,min:3',
            },
            {
                input    : '#user-customer-address',
                inputName: 'Alamat Tujuan',
                rules    : 'required,min:10',
            },
            {
                input    : '#user-city-district-search',
                inputName: 'Kota / kecamatan',
                rules    : 'required',
            },
            {
                input    : '#user-customer-phone',
                inputName: 'Nomer handphone',
                rules    : 'required,numeric,min:9,max:15',
            },
        ];

        validator(validations, success => {
            if (!success) event.preventDefault();
        });
    });

    formDisableSubmit('#checkout-customer-store', 'input:not([type=hidden])');
    //#endregion Checkout - Customer store
    //#endregion Checkout

    //#region Book create
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
            data['rules'] = 'required,mimes:png|jpg|jpeg,maxSize:2000';
        }
    }

    formDisableSubmit('#book-edit-form, #book-store-form', 'input:not([type=hidden]):not([name=diskon]');

    $('#book-edit-form, #book-store-form').on('submit', function (e) {
        e.preventDefault();

        let validations   = [];
        let finds         = 'input[type=number], input[type=text], input[type=date], input[type=file], textarea';
        let inputs        = $(this).find(finds);

        inputs.map((key, input) => {
            let inputId   = $(input).attr('id');
            let inputName = capitalizeFirstLetter(inputId.replaceAll('_', ' '));
            inputId       = `#${inputId}`;
            let data      = {
                input    : inputId,
                inputName: inputName,
                rules    : 'required',
            }

            bookEditCustomValidationCondition(inputId, data);
            validations.push(data);
        });

        validator(validations, success => {
            if (success) this.submit();
        });
    })

    $('#nama_penulis').on('keyup', function() {
        let data = {
            author_name: $(this).val(),
        };

        setTimeout(() => {
            $.get(`/api/books/get-authors`, data, response => {
                if (response.status == 'success') {
                    console.log(response.data.authors);
                    let html = response.data.authors.map((author) => `<div class="p-2">${author.name}</div>`);
                        html = `<div class="form-search-content">${html.join('')}</div>`;

                    $('.form-search-content').remove();
                    $(this).after(html);

                    $('.form-search-content div').on('click', event => {
                        $(this).val($(event.target).text());
                        $(this).attr('value', $(event.target).text());
                        $('.form-search-content').remove();
                    });
                }
            });
        }, 200);

        if ($(this).val().length == 0) {
            $('.form-search-content').remove();
        }
    });

    //#endregion

}); // End of onload Event


const hapusUser = (myObject) => {
    myObject = $(myObject);
    const token = myObject.attr("token");
    const backendUrl = myObject.attr("url");
    let formData = {
        _method: "DELETE",
        _token: token,
        userDelete: true,
    }
    $.ajax({
        type: "POST",
        url: backendUrl,
        data: formData,
        dataType: "JSON",
        success: function (response) {
            console.log("RESPONSE ====>", response);
        },
    })

}
