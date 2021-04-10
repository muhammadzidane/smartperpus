'use strict';

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

    // $('#button-submit').on('click', function (e) {
    //     e.preventDefault();

    //     if ($(this).hasClass('active-login') && $('#email').val() != '' && $('#password').val() != '') {
    //         $.ajax({
    //             type: "POST",
    //             url: "/ajax/request/check-login",
    //             data: {
    //                 '_token'  : csrfToken,
    //                 'email'   : $('#email').val(),
    //                 'password': $('#password').val(),
    //             },
    //             success: function (response) {
    //                 let errorLogin  = '<div class="error tred small mb-2" role="alert">';
    //                     errorLogin += '<strong>' + response.message + '</strong>';
    //                     errorLogin += '</div>';

    //                 if (!response.success) {
    //                     $('#error-login').first().html((errorLogin));
    //                 }
    //                 else {
    //                     $('#form-login').trigger('submit');
    //                 }
    //             }
    //         });
    //     }
    // });
}
