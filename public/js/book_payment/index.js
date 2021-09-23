setInterval(() => {
    let hours                  = $('#deadline-hours');
    let minutes                = $('#deadline-minutes');
    let seconds                = $('#deadline-seconds');
    let deadlineHours          = parseInt(hours.text());
    let deadlineMinutes        = parseInt(minutes.text());
    let deadlineSeconds        = parseInt(seconds.text());
    let newDeadlineSeconds     = seconds.text(deadlineSeconds - 1);
    let padZeroDeadlineSeconds = newDeadlineSeconds.text().padStart(2, '0');

    newDeadlineSeconds.text(padZeroDeadlineSeconds);

    if (deadlineSeconds == 0) {
        minutes.text(deadlineMinutes - 1);

        let padZeroDeadlineMinutes = minutes.text().padStart(2, '0');

        minutes.text(padZeroDeadlineMinutes);
        seconds.text(59);

        if (deadlineMinutes == 0) {
            hours.text(deadlineHours - 1);

            let padZeroDeadlineHours = hours.text().padStart(2, '0');

            hours.text(padZeroDeadlineHours);
            minutes.text(59);

            if (deadlineHours == 0) {
                hours.text('00');
                minutes.text('00');
                seconds.text('00');

                console.log(false);
            }
        }
    }
}, 1000);

$('.payment-intro').on('hide.bs.collapse', function () {
    $(this).siblings('.collapse-intro').find('i').removeClass('fa-caret-down');
    $(this).siblings('.collapse-intro').find('i').addClass('fa-caret-right');
})

$('.payment-intro').on('hide.bs.collapse', function () {
    $(this).siblings('.collapse-intro').find('i').removeClass('fa-caret-down');
    $(this).siblings('.collapse-intro').find('i').addClass('fa-caret-right');
})
