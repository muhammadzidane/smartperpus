"use strict";

$('#login-form').on('submit', function(event) {
    event.preventDefault();

    let validations = [
        {
            input: '#email',
            inputName: 'Email',
            rules: 'required',
        },
        {
            input: '#password',
            inputName: 'Password',
            rules: 'required',
        }
    ];

    validator(validations, success => {
        if (success) {
            ajaxForm('POST', this, this.action, response => {
                if (response.errors) {
                    let appendedElement = $(this).children().first();

                    backendMessage(appendedElement, response.errors);
                } else {
                    window.location.href = response.url;
                }
            });
        }
    });
});
