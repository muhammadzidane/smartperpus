'use strict';
// Random Number
function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

// Cek apakah form input dengan class yg sama, ada valuenya atau tidak
function checkFormRequiredInputs(classInputs) {
    let flag = [];

    for (const input of $(classInputs)) {
        if ($(input).val() != '') {
            flag.push(true);
        }
        else {
            flag.push(false);
        }
    }

    let check = flag.every(function booleanCheck(value) {
        return value == true ? true : false;
    });

    return check == true ?  true : false;
}

// Jika sebuah Form <input></input> yang sudah di tentukan dengan class yang sama, dan value
// tersebut kosong . Maka tombol submit pada form tersebut tidak akan berfungsi.

function keyUpToggleFormButton(classInputs) {
    $(classInputs).on('keyup', function () {
        checkFormRequiredInputs($(classInputs)) === true
        ? $('.button-submit').addClass('active-login')
        : $('.button-submit').removeClass('active-login');
    });
}

// Mengambil value dari URL
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

// Number format
function numberFormat (number, decimals, decPoint, thousandsSep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    const n = !isFinite(+number) ? 0 : +number
    const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    let s = ''

    const toFixedFix = function (n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
        }
        else {
            const arr = ('' + n).split('e')
            let sig = ''
            if (+arr[1] + prec > 0) {
            sig = '+'
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
        }
    }

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }

    if ((s[1] || '').length < prec) {
      s[1] = s[1] || ''
      s[1] += new Array(prec - s[1].length + 1).join('0')
    }

    return s.join(dec)
}

function rupiahFormat(value) {
    return 'Rp' + numberFormat(value, 0, 0, '.');
}

function exitFilters() {
    globalThis.csrfToken;

    $('.exit-filter').on('click', function() {
        $(this).parent().remove();

        $.ajax({
            type: "POST",
            url : "/ajax/request/filter-search",
            data: {
                '_token'         : csrfToken,
                'min_price'      : $('.filter-min-price').data('filter-value') ?? 0,
                'max_price'      : $('.filter-max-price').data('filter-value') ?? 999999999,
                'star_value'     : $('.rating-4-plus').length == 0 ? null : $('.filter-star-search').data('filter-star'),
                'sort_book_value': $('.filter-sort').data('filter-value'),
                'keywords'       : getUrlParameter('keywords'),
            },
            success: function (response) {
                $('#book-search').html(response.books);
                $('.book').css('width', '22.52%');
            }
        });

    });
}

function ajaxFilterDataBooks() {
    globalThis.csrfToken;

    let min_price_val        = $('.filter-min-price').data('filter-value');
    let max_price_val        = $('.filter-max-price').data('filter-value');

    $.ajax({
        type: "POST",
        url : "/ajax/request/filter-search",
        data: {
            '_token'         : csrfToken,
            'min_price'      : min_price_val,
            'max_price'      : max_price_val,
            'star_value'     : $('.rating-4-plus').length == 0 ? null : $('.filter-star-search').data('filter-star'),
            'sort_book_value': $('.filter-sort').data('filter-value'),
            'keywords'       : getUrlParameter('keywords'),
            'page'           : getUrlParameter('page'),
        },
        success: function (response) {
            $('#book-search').html(response.books);
            $('.book').css('width', '22.52%');
            exitFilters();
        }
    });
}

function appendFilter(filter, appendText = []) {

    let filter_html = function(text, value = null , filterName) {
        let val  = `<div class="search-filter ${filterName}" data-filter-value="${value}">`;
            val += `<span class="search-filter-value">${text}</span>`;
            val += `<i class="exit-filter fa fa-times text-grey ml-2" aria-hidden="true"></i>`;
            val += `</div>`;

        return val;
    };

    if ($(filter).length == 0) {
        $('#search-filters').append(filter_html(appendText[0],
        appendText[1], appendText[2]));
    }
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }

    return false;
};

function checkShippingInsurance() {
    let shippingCost           = $('.inp-courier-choise-service:checked').val() ?? 0;
    let totalPayment           = ($('#total-payment').data('total-payment') * parseInt($('#book-needed').text())) + parseInt(shippingCost);

    let shippingInsuranceHtml  = `<div id="shipping-book" class="d-flex justify-content-between">`;
        shippingInsuranceHtml += `<div>Asuransi Pengiriman</div>`;
        shippingInsuranceHtml += `<div>Rp1.000</div>`;
        shippingInsuranceHtml += `</div>`;

    if ($('#shipping-insurance').is(':checked') && $('#shipping-book').length <= 0) {
        $('#book-payment').append(shippingInsuranceHtml);
        $('#total-payment').attr('data-total-payment', totalPayment + 1000);
        $('#total-payment').text(`Rp${numberFormat(totalPayment + 1000, 0, 0, '.')}`);
        $('#book-total-payment').val(totalPayment + 1000);
    }
    else {
        if (!$('#shipping-insurance').is(':checked')) {
            $('#shipping-book').remove();
            $('#total-payment').attr('data-total-payment', totalPayment);
            $('#total-payment').text(`Rp${numberFormat(totalPayment, 0, 0, '.')}`);

            $('#book-total-payment').val(totalPayment);
        }
        else {
            $('#total-payment').attr('data-total-payment', totalPayment + 1000);
            $('#total-payment').text(`Rp${numberFormat(totalPayment + 1000, 0, 0, '.')}`);
            $('#book-total-payment').val(totalPayment + 1000);
        }
    }
}

function ucwords(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function ajaxForm(method, formSelector, ajaxUrl, successFunction, formDataAppend = '') {
    let form        = $(formSelector)[0];
    let formData    = new FormData(form);

    if (formDataAppend !== '') {
        formData.append(formDataAppend[0], formDataAppend[1]);
    }

    $.ajax({
        type       : method,
        url        : ajaxUrl,
        data       : formData,
        dataType   : "JSON",
        processData: false,
        cache      : false,
        contentType: false,
        success    : successFunction,
        error : function(errors) {
            console.log(errors.responseJSON);
        }
    });
}

function scrollToElement(selector, duration) {
    $([document.documentElement, document.body]).animate({
        scrollTop: $(selector).offset().top
    }, duration);
}

// Customer
function customerDestroy(){
    //#region - Customer Destroy
    $('.customer-destroy-form').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let dataCustomerLength = $('.data-customer').length - 1;
        let customerId         = $(this).data('id');
        let dataCustomer       = $(this).closest($('.data-customer'));
        let dataCustomers      = $('.data-customer');

        if (confirm('Apakah anda yakin ingin menghapus alamat tersebut ?')) {
            ajaxForm('POST', this, `/customers/${customerId}`, function() {;
                scrollToElement(dataCustomers, 500);
                dataCustomer.remove();

                if (dataCustomerLength < 5) {
                    $('#customer-store-modal-trigger').show();
                }

                if (dataCustomerLength == 0) {
                    $('#customer-store-empty').show();
                }
            });
        }


    });
    //#endregion - Customer Destroy
}

// Customer Update
function customerUpdate() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let changeName, dataCustomer, changePhoneNumber, changeAddress, changeCity, changeDistrict, changeProvince;

    // Customer Modal Edit
    $('.customer-edit').on('click', function(e) {
        e.stopImmediatePropagation;

        $('#method-patch').remove();

        let form              = $('.modal-customer-edit').children().children();
            changeName        = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-name'));
            changePhoneNumber = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-phone-number'));
            changeAddress     = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-address'));
            changeDistrict    = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-district'));
            changeCity        = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-city'));
            changeProvince    = $($(this).parents('div .d-flex')[1]).prev().find($('.customer-province'));
            dataCustomer      = $(this).closest($('.data-customer'));

        $('#customer-edit-form').attr('data-id', $(this).data('id'));
        $('#customer-edit-form').attr('action', `http://smartperpus.com/customers/${$(this).data('id')}`);

        $.ajax({
            type    : "POST",
            url     : `/customers/${$(this).data('id')}/ajax/request/edit-submit-get-data`,
            data    : { '_token' : csrfToken },
            dataType: "JSON",
            success : function (response) {
                console.log(response.customer.name);
                form.children('input[name=nama_penerima]').val(response.customer.name);
                form.children('input[name=nomer_handphone]').val(response.customer.phone_number);
                form.children('input[name=alamat_tujuan]').val(response.customer.address);
            },

        });
    });

    $('#customer-edit-form').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let customerId = $(this).data('id');

        $('.validate-error').text('');

        let namaPenerima = $(this.nama_penerima).val();
        let phoneNumber  = $(this.nomer_handphone).val();
        let address      = $(this.alamat_tujuan).val() + ',';
        let district     = $(this.kecamatan).find(':selected').text() + ',';
        let city         = $(this.kota_atau_kabupaten).find(':selected').text() + '.';
        let province     = $(this.provinsi).find(':selected').text();

        console.log(namaPenerima);

        if (confirm('Apakah anda yakin ingin mengedit alamat tersebut ?')) {

            let patchMethod = `<input id="method-patch" type="hidden" name="_method" value="PATCH">`;

            $(this).append(patchMethod);

            ajaxForm('POST', this, `/customers/${customerId}`, function(response) {
                if (response.status) {
                    changeName.text(namaPenerima);
                    changePhoneNumber.text(`( ${phoneNumber} )`);
                    changeAddress.text(address);
                    changeDistrict.text(district);
                    changeCity.text(city);
                    changeProvince.text(province);

                    $('.close').trigger('click');

                    scrollToElement(dataCustomer, 300);
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
        }

    });
}
// End Customer

// Toggle Text
function toggleText (selector, a, b) {
    return $(selector).text($(selector).text() == b ? a : b);
}

// Book User / Book Payment
// Jika deadline pembayaran habis maka hapus data dari database, lalu redirect ke home
function ajaxBookPurchaseDeadlineDestroy() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type    : "POST",
        url     : `/book-purchases/ajax-payment-deadline`,
        data    : { '_token' : csrfToken },
        dataType: "JSON",
        success : function (response) {
            console.log(response);
        },
    });
}

function getPaymentDeadlineText(userId) {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        type    : "POST",
        url     : `/book-purchases/${userId}/ajax-payment-deadline-text`,
        data    : { '_token' : csrfToken },
        dataType: "JSON",
        success : function (response) {
            let deadlineText = `${response.hours} : ${response.minutes} : ${response.seconds}`;

            $('#payment-limit-time').text(deadlineText);

            setInterval(() => {
                let minutes = response.minutes;
                let seconds = response.seconds < 10 ? '0' + response.seconds-- : response.seconds--;
                let hours   = response.hours;
                console.log(hours);

                let deadlineText = `${hours} : ${minutes} : ${seconds}`;

                if (response.seconds < 0) {
                    response.minutes--;
                    response.seconds = 59;
                }

                if (response.minutes < 0) {
                    response.hours--;
                    response.minutes = 59;
                }

                if (response.hours < 0) {
                    window.location.href = response.home;
                }

                $('#payment-limit-time').text(deadlineText);
            }, 1000);

        },
    });
}

// Search
const ajaxJson = (method, url, data, successResponse) => {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "JSON",
        success: successResponse
    });
}

// Show message chatting
const showMessageChatting = () => {
    $('#user-chats-error-image').addClass('d-block');

    setTimeout(() => {
        $('#user-chats-error-image').slideUp(400, () => {
            $('#user-chats-error-image').removeClass('d-block');
        });
    }, 3000);
}

const singleMessage = (messageText) => {
    let html = `<div id="message"><i class="message-check fas fa-check"></i>${messageText}</div>`;
    let messageLength = $('#message').length;

    if (messageLength == 0) {
        $('.cus-navbar').append(html);
    }

    setTimeout(() => {
        $('#message').slideUp(500, () => {
            $('#message').remove();
        });
    }, 2300);
}

// Modal Confirm - Jangan pakai arrow function
const modalConfirm = (button, text, callback) => {
    $(button).attr('data-target', '#modal-confirm');
    $(button).attr('data-toggle', 'modal');

    let html =
    `<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-confirm-content">
                <div class="modal-confirm-body">
                    <div class="text-left">${text}</div>
                    <div class="modal-confirm-close"><i class="fa fa-times"></i></div>
                </div>
                <div class="modal-confirm-buttons">
                    <button class="modal-confirm-accept">Ya</button>
                    <button class="modal-confirm-cancel">Tidak</button>
                </div>
            </div>
        </div>
    </div>`;

    let modalConfirmLength = $('#modal-confirm').length;

    if (modalConfirmLength == 0) {
        $(button).after(html);
    }

    const closeModalAction = () => {
        $(button).removeAttr('data-target data-toggle')
        $('#modal-confirm').modal('hide');

        setTimeout(() => {
            $('#modal-confirm').remove();
        }, 200);
    };


    $('.modal-confirm-accept').on('click', () => {
        closeModalAction();

        callback(true);
    });

    $('.modal-confirm-cancel').on('click', () => {
        closeModalAction();

        callback(false);
    });

    $('.modal-confirm-close').on('click', () => {
        closeModalAction();

        callback(false);
    });
}
// End Modal confirm

// Modal zoom image - Click untuk menampilkan modal gambar yang lebih besar
$('.zoom-modal-image').on('click', function() {
    let windowWidth = $(window).width();

    if (windowWidth >= 768) {
        let html =
        `<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-image position-relative">
                    <button type="button" class="close modal-close c-p" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-body">
                        <div class="w-75 mx-auto">
                            <img id="image-modal-source" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        let modalLength = $('#imagemodal').length;


        if (modalLength == 0) {
            $(this).after(html)
        }

        $('#imagemodal').modal('show');

        let clickedImageSource = $(this).attr('src');

        $('#image-modal-source').attr('src', clickedImageSource);

        $('.modal-close').on('click', () => {
            setTimeout(() => {
                $('#imagemodal').remove();
            }, 200);
        });
    }
});
// End Modal zoom image

//#region Add and subtract status notification - Menambah dan menghapus notifikasi status
const addAndSubtractStatusNotification = () => {
    let statusActive           = $('.active-acc');
    let statusActiveNotification     = statusActive.find('.status-circle');
    let statusActiveNotificationNext = statusActive.next().find('.status-circle');

    if (parseInt(statusActiveNotification.text()) == 1) {
        statusActiveNotification.remove();
    } else {
        statusActiveNotification.text(parseInt(statusActiveNotification.text()) - 1);
    }

    if (statusActiveNotificationNext.length == 0) {
        let html = '<div class="status-circle">1</div>';

        statusActive.next().append(html);
    } else {
        statusActiveNotificationNext.text(parseInt(statusActiveNotificationNext.text()) + 1);
    }
};
//#endregion Add and subtract status notification - Menambah dan menghapus notifikasi status

//#region Cancel status payment - Membatalkan status pembayaran
const cancelStatusPayment = (buttonSelector, alertConfirmText, status, successMessage) => {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $(buttonSelector).on('click', function() {
        let dataId      = $(this).data('id');
        let confirmText = alertConfirmText;
        let datas       = {
            _token : csrfToken,
            _method: 'PATCH',
            status : status,
        };

        modalConfirm(this, confirmText, (accepted) => {
            if (accepted) {
                ajaxJson('POST', `/book-users/${dataId}`, datas, () => {
                    let messageText = successMessage;
                    let statusActive           = $('.active-acc');
                    let statusNotification     = statusActive.find('.status-circle');
                    let statusNotificationPrev = statusActive.prev().find('.status-circle');

                    singleMessage(messageText);
                    setTimeout(() => $(this).parents('.uploaded-payment').remove(), 200);

                    statusNotification.text(statusNotification.text() - 1);

                    let html = `<div class="status-circle">1</div>`;

                    if (statusNotificationPrev.length == 0) {
                        statusActive.prev().append(html);
                    } else {
                        statusNotificationPrev.text(parseInt(statusNotificationPrev.text()) + 1);
                    }

                    if (statusNotification.text() == 0) {
                        statusNotification.remove();
                    }
                });
            }
        });
    });
};
//#endregion Cancel status payment - Membatalkan status pembayaran

