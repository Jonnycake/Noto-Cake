function jc_get_events(fetch_info, success, failure)
{
    var start_time = fetch_info.startStr.substring(0, 19);
    var end_time = fetch_info.endStr.substring(0, 19);

    (function($) {
        $.ajax({
            method: "GET",
            url: my_restapi_details.rest_url + 'jc/v1/events?start='+start_time+'&end='+end_time,
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', my_restapi_details.nonce );
            },
            success : function( response ) {
                success(response);
            }
        });
    })(jQuery);
}

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
            }
        });
    })(jQuery);
}

function jc_edit_event(data, el)
{
    (function($) {
        $.ajax({
            method: "PATCH",
            url: my_restapi_details.rest_url + 'jc/v1/events',
            data: JSON.stringify(data),
            contentType: "application/json",
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', my_restapi_details.nonce );
            },
            success : function( response ) {
                el.trigger('jc-events-updated');
            }
        });
    })(jQuery);
}
