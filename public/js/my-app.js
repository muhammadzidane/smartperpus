"use strict";

$(document).ready(function () {

    let csrfToken = $('meta[name="csrf-token"]').attr('content');

    $(search).on('blur', function() {
        $('#search-values').hide();
    });

    $('#search').on('keyup', function() {
        if ($(this).val() === '') {
            $('#search-values').hide();
        }
        else {
            $('#search-values').show();
            $.ajax({
                type   : 'POST',
                url    : '/ajax/request/store',
                data   : { '_token' : csrfToken, 'search_value' : $('#search').val() },
                success: function(data) {
                    data.books.length == 0 ? $('#search-books').prev().hide() : $('#search-books').prev().show();
                    data.authors.length == 0 ? $('#search-authors').prev().hide() : $('#search-authors').prev().show();

                    if (data.books.length == 0 && data.authors.length == 0) {
                        $('#search-values > div:first-child()').hide();
                    }
                    else {
                        $('#search-values > div:first-child()').show();
                    }

                    $('#search-books').html('');
                    data.books.forEach(element => {
                        $('#search-books').append('<li>' + element.name +'</li>');
                    });

                    $('#search-authors').html('');
                    data.authors.forEach(element => {
                        $('#search-authors').append('<li>' + element.name +'</li>');
                    });

                }
            });
        }
    });

});
