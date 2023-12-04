$(window).on('load', function() {
    // Hide the loading screen
    $('.loading-overlay').fadeOut('slow', function() {
        // Show the content section
        $('#page-body').fadeIn('slow');
    });

    document.addEventListener('livewire:navigated', () => {
        $('.loading-overlay').fadeOut()
    })

    $('a').on('click', function() {
        var url = $(this).attr('href');
        var valid = /^(ftp|http|https):\/\/[^ "]+$/.test(url);
        if(valid) {
            $('#page-body').hide();
            $('.loading-overlay').show();
        }
    });

    Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
        // Runs after commit payloads are compiled, but before a network request is sent...
        $('.loading-overlay').show();
        respond(({ status, response }) => {
            // Runs when the response is received...
            // "response" is the raw HTTP response object
            // before await response.text() is run...
        })

        succeed(({ status, json }) => {
            // Runs when the response is received...
            // "json" is the JSON response object...
            $('.loading-overlay').hide();
        })

        fail(({ status, content, preventDefault }) => {
            // Runs when the response has an error status code...
            // "preventDefault" allows you to disable Livewire's
            // default error handling...
            // "content" is the raw response content...
            $('.loading-overlay').hide();
        })
    });
});
