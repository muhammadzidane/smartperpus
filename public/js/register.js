"use strict";

$('#form-register').on('submit', function(event) {
    event.preventDefault();

    let formRegisterInputs = $(this).find('.form-control-custom').toArray();
    let validations = formRegisterInputs.map(input => {
        let inputName = capitalizeFirstLetter(input.id.replace(/_|-/, ' '));
        let validations = {
            input    : '#' + input.id,
            inputName: inputName,
            rules    : 'required',
        };

        if (input.id == 'email') {
            validations['rules'] = 'required,email';
        }

        if (input.id == 'password') {
            validations['rules'] = 'required,min:8';
        }

        if (input.id == 'konfirmasi_password') {
            validations['rules'] = 'required,min:8,same:password';
        }

        return validations;
    });


    validator(validations, success => {
        if (success) {
            this.submit();
        };
    });
});

formDisableSubmit('#form-register', 'input:not([type=hidden])');
