'use strict';

$(document).ready(function() {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#button-register').on('click', function(e) {
        e.preventDefault();

        if (checkFormRequiredInputs('.register-form') && $('#button-register').hasClass('active-login')) {
            $.ajax({
                type: "POST",
                url : "/ajax/request/register",
                data: {
                    '_token'             : csrfToken,
                    'nama_awal'          : $('#nama_awal').val(),
                    'nama_akhir'         : $('#nama_akhir').val(),
                    'email'              : $('#email').val(),
                    'password'           : $('#password').val(),
                    'konfirmasi_password': $('#konfirmasi_password').val(),
                },
                success : function (response) {
                    if (response.errors) {
                        $('#error-register').addClass('error-register');

                        let messages   = ``;

                        for (const key in response.errors) {
                            if (response.errors.hasOwnProperty.call(response.errors, key)) {
                                const element = response.errors[key];

                                element.forEach(value => {
                                    messages += `<div class='d-flex justify-content-between'>`;
                                    messages += `<div>${value}</div>`;
                                    messages += `<i class="fas fa-exclamation-triangle"></i>`
                                    messages += `</div>`;

                                });

                            }
                        }
                        $('#error-register').html(messages);
                    }
                    else {
                        $('#error-register').removeClass('error-register');
                        $('#error-register').html('');

                        $('#form-register').trigger('submit');
                    }
                },
            });
        }
        else {

        }
    });

    keyUpToggleFormButton('.register-form');
});
