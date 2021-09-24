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

formDisableSubmit('#book-edit-form, #book-store-form', 'input:not([type=hidden]):not([name=diskon]')
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

    $.get(`/api/books/get-authors`, data, response => {
        if (response.status == 'success') {
            let html = response.data.authors.map((author) => `<div class="p-2">${author.name}</div>`);
                html = `<div class="form-search-content">${html}</div>`;

            $('.form-search-content').remove();
            $(this).after(html);

            $('.form-search-content').on('click', event => {
                $(this).val($(event.target).text());
                $(this).attr('value', $(event.target).text());
                $('.form-search-content').remove();
            });
        }
    });
});
