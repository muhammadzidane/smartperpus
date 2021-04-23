'use strict';

let csrfToken = $('meta[name="csrf-token"]').attr('content');

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
            console.log(response.page)
            $('#book-search').html(response.books);
            exitFilters();
        }
    });
}

function appendFilter(filter, appendText = []) {
    let filter_html = function(text, value = null , filterName = null) {
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
    else {
        $(filter).text(appendText).append(exitFilters());
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
