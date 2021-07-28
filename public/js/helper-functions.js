'use strict';
// Random Number
function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

// Huruf pertama uppercase
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const requestMethodName = (methodName) => {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    return [
        {
            _token: csrfToken,
            _method: methodName
        }
    ];
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
        formDataAppend.map(data => {
            for (const key in data) {
                if (data.hasOwnProperty.call(data, key)) {
                    const element = data[key];

                    formData.append(key, element);
                }
            }
        });

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
const ajaxJson = (method, url, data, successResponse = '') => {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: "JSON",
        success: successResponse,
        error: error => {
            console.log(error.responseJSON.message);
        }
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

const backendMessage = (selector, errors) => {
    let errorMessages = ``;

    for (const key in errors) {
        if (errors.hasOwnProperty.call(errors, key)) {
            const error = errors[key];

            errorMessages +=
            `<div class="alert-message">
                <div class="alert-message-text">${error}</div>
                <i class="alert-message-icon fas fa-exclamation-triangle"></i>
            </div>`;
        }
    }

    let alertMessageLength = $('.alert-messages').length;

    if (alertMessageLength == 0) {
        let messages = `<div class="alert-messages">${errorMessages}</div>`;
        selector.after(messages);
    } else {
        $('.alert-messages').remove();

        let messages = `<div class="alert-messages">${errorMessages}</div>`;

        selector.after(messages);
    }
} ;

const alertMessage = (messageText) => {
    let html          = `<div id="message">${messageText}</div>`;
    let messageLength = $('#message').length;

    if (messageLength == 0) {
        $('.cus-navbar').append(html);
    }

    setTimeout(() => {
        $('#message').hide(0, () => {
            $('#message').remove();
        });
    }, 2300);
}

// Modal Confirm - Jangan pakai arrow function
const modalConfirm = (text, callback) => {
    let html =
    `<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-confirm-content">
                <div class="modal-confirm-body">
                    <div class="text-left">${text}</div>
                    <div class="modal-confirm-close"><i class="fa fa-times"></i></div>
                </div>
                <div class="modal-confirm-buttons">
                    <button class="modal-confirm-accept" type="button">Ya</button>
                    <button class="modal-confirm-cancel" type="button">Tidak</button>
                </div>
            </div>
        </div>
    </div>`;

    let modalConfirmLength = $('#modal-confirm').length;

    if (modalConfirmLength == 0) {
        $('body').prepend(html);
    }

    $('#modal-confirm').modal('show');

    const closeModalAction = () => {
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

//#region Modal
const bootStrapModal = (modalHeader, modalSize, callback) => {
    let html =
    `<div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ${modalSize} modal-dialog-centered">
            <div class="modal-content">
                <div class="p-3 position-relative">
                    <h5 class="modal-title tred login-header">${modalHeader}</h5>
                    <button id="modal-exit-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ${callback()}
                </div>
            </div>
        </div>
    </div>`;
    let modalLength = $('#custom-modal').length;

    if (modalLength == 0) {
        $('body').prepend(html);
    }

    $('#custom-modal').modal('show');
};
//#region Modal

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

//#region Validator - Validasi otomatis menambah message di element input next
const validator = (validations, success = '') => {
    let validationFails = [];

    for (const key in validations) {
        if (validations.hasOwnProperty.call(validations, key)) {
            const validation = validations[key];

            let inputForm = validation.input;
            let inputName = validation.inputName;
            let rules     = validation.rules;

            let inputValue      = $(inputForm).val();
            let splitRules      = rules.split(',');
            splitRules.map(rule => {
                if (rule == 'nullable') {
                    if (inputValue == '' || inputValue == 0) {
                        validationFails.push('');
                    }
                }

                if (rule == 'required') {
                    let message = `${inputName} tidak boleh kosong`;

                    if (inputValue == '' || inputValue == 0) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        }
                    } else {
                        $(inputForm).next('.error').remove();
                    }
                }


                let uniqueRegex = new RegExp('unique:[a-zA-Z]');

                if (uniqueRegex.test(rule)) {
                    let uniqueRegexInput = uniqueRegex.exec(rule).input;
                    let typeFiles        = uniqueRegexInput.replace('unique:', '').split('|');
                    let table            = typeFiles[0];
                    let row              = typeFiles[1];
                    let datas            = {
                        table     : table,
                        row       : row,
                        inputValue: inputValue,
                    }

                    ajaxJson('GET', '/validator/unique', datas, function(response) {
                        if (response.table) {
                            let message = `${inputName} sudah ada sebelumya`;

                            validationFails.push(message);

                            if ($(inputForm).next('.error').length == 0) {
                                $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                            } else {
                                $(inputForm).next('.error').children('small').text(message);
                            }
                        } else if (inputValue != '') {
                            $(inputForm).next('.error').remove();
                        }
                    });
                }

                if (rule == 'email') {
                    let emailRegex = /^\S+@\S+\.\S+/;
                        emailRegex = emailRegex.test(inputValue);
                    let message    = `${inputName} tidak valid`;

                    if (!emailRegex && inputValue != '') {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let sameRegex = new RegExp('same:[A-Za-z]');

                if (sameRegex.test(rule)) {
                    let sameRegexInput = sameRegex.exec(rule).input;
                    let sameRegexSplit = sameRegexInput.split(':');
                    let same           = sameRegexSplit[1];

                    let message = `${inputName} harus sama ${same}`;

                    if (inputValue != $('#' + same).val()) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let maxRegex = new RegExp('max:[0-9]');

                if (maxRegex.test(rule)) {
                    let maxRegexInput = maxRegex.exec(rule).input;
                    let maxRegexSplit = maxRegexInput.split(':');
                    let max           = maxRegexSplit[1];

                    let message = `${inputName} tidak boleh lebih dari ${max}`;

                    if (inputValue.length > max) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let minRegex = new RegExp('min:[0-9]');

                if (minRegex.test(rule)) {
                    let minRegexInput = minRegex.exec(rule).input;
                    let minRegexSplit = minRegexInput.split(':');
                    let min           = minRegexSplit[1];
                    let message       = `${inputName} Tidak boleh kurang dari ${min}`;

                    if (inputValue.length < min && inputValue != '' && inputValue != 0) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let digitsRegex = new RegExp('digits:[0-9]');

                if (digitsRegex.test(rule)) {
                    let digitsRegexInput = digitsRegex.exec(rule).input;
                    let digitsRegexSplit = digitsRegexInput.split(':');
                    let digits           = digitsRegexSplit[1];

                    let message = `${inputName} harus memiliki ${digits} digit angka`;

                    if (inputValue.length != digits && inputValue != '' && inputValue != 0) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let mimesRegex = new RegExp('mimes:[a-zA-Z]');

                if (mimesRegex.test(rule)) {
                    let mimesRegexInput = mimesRegex.exec(rule).input;
                    let typeFiles       = mimesRegexInput.replace('mimes:', '').split('|');
                    let ext             = $(inputForm).val().split('.').pop().toLowerCase();

                    if ($.inArray(ext, typeFiles) == -1 && inputValue != '') {
                        let message = `Hanya bisa mengirim file gambar berupa: ${typeFiles}`;

                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                let maxSizeRegex = new RegExp('maxSize:[0-9]');

                if (maxSizeRegex.test(rule)) {
                    let maxSizeRegexInput = maxSizeRegex.exec(rule).input;
                    let maxSizeSplit      = maxSizeRegexInput.split(':');
                    let size              = maxSizeSplit[1] / 1000;

                    let imageSize     = $(inputForm)[0].files[0] != undefined ? $(inputForm)[0].files[0].size : null;
                    let imageSizeInMb = imageSize /1024/1024;

                    if (imageSizeInMb > size) {
                        let message = `${inputName} tidak boleh lebih dari 2000 kilobyte (2mb)`;

                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }

                if (rule == 'numeric') {
                    let numberOnlyRegex = /^\d+$/;
                        numberOnlyRegex = numberOnlyRegex.test(inputValue);
                    let message         = `${inputName} harus berupa numerik`;

                    if (!numberOnlyRegex && inputValue != '' && inputValue != 0) {
                        validationFails.push(message);

                        if ($(inputForm).next('.error').length == 0) {
                            $(inputForm).after(`<div class="error tred"><small>${message}</small></div>`);
                        } else {
                            $(inputForm).next('.error').children('small').text(message);
                        }
                    } else if (inputValue != '') {
                        $(inputForm).next('.error').remove();
                    }
                }
            });

            let inputNext = $(inputForm).next('.error');
            let checkValidations = validationFails.every(validation => validation == []);
        }
    }

    let checkValidations = validationFails.every(validationFail => validationFail == []);

    if (success != '') {
        checkValidations ? success(true) : success(false);
    }
}
//#endregion Validator

//#region Change photo - Mengubah foto
const changeInputPhoto = (imageId, inputFileId) => {
    let preview = document.getElementById(imageId);
    let file    = document.getElementById(inputFileId).files[0];
    let reader  = new FileReader();

    reader.onloadend = () => {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
};
//#endregion Change photo
