require(['jquery'], function ($) {
    // Do function when page has fully loaded all scripts and code.
    $(document).ready(function() {

        $('.state-section-title').click(function(e) {
            // Grab current anchor value
            const $currentAttrValue = $('.state-store-list ' + $(this).attr('href'));

            if ($(e.target).is('.active')) {
                // Remove active class from section title
                $(this).removeClass('active');
                // Open up the hidden content panel
                $currentAttrValue.slideUp(300).removeClass('open');
            } else {
                // Add active class to section title
                $(this).addClass('active');
                // Close the open content panel
                $currentAttrValue.slideDown(300).addClass('open');
            }
            e.preventDefault();
        });

        $('#search-submit').on('click', function(e) {
            e.preventDefault();
            const input = $('#address-input').val();
            $.ajax({
                method: 'POST',
                url: $(this).data('route'),
                data: { address: input },
                dataType: 'json'
            }).done(function (data) {
                // Clear out search result list
                $('.search-results').html(data.html).show();
            });
        });
    });
});
