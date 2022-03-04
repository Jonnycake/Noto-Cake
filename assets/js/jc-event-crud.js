function jc_create_event(data, el)
{
    // Create a post with the WordPress REST API
    (function($) {
        $.ajax({
            method: "POST",
            url: my_restapi_details.rest_url + 'jc/v1/events',
            data: JSON.stringify(data),
            contentType: "application/json",
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', my_restapi_details.nonce );
            },
            success : function( response ) {
                el.trigger('jc-events-updated');

                // Save the post ID in case you need it for something
                var myNewPostID = response.id;
            }
        });
    })(jQuery);
}
