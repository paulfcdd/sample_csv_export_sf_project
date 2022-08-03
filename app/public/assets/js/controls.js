$(document).ready(function () {
    $('.save-record').click(function () {
        let hours = $('span.hours').text();
        let minutes = $('span.minutes').text();
        let seconds = $('span.seconds').text();
        let url = $(this).data('url');
        let loggedTime = hours + ':' + minutes + ':' + seconds;
        let description = $('.description').val();

        if (description.length === 0) {
            alert('Description can not be empty');
        } else {
            if (seconds === '00') {
                alert('Can not save empty timer');
            }
        }

        if (description.length > 0 && seconds !== '00') {
            $.ajax({
                url: url,
                method: 'post',
                data: {
                    loggedTime: loggedTime,
                    description: description
                },
                success: function(result){
                    window.location.replace(result.redirectPath);
                }
            });
        }
    });
})