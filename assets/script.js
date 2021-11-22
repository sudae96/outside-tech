jQuery(document).ready(function ($) {

	var ajaxUrl = otech_obj.ajax_url;
    var wpNonce = otech_obj.ajax_nonce;

	$('body').on('submit', 'form#event-filter', function (e) {
		e.preventDefault();
		var data = $(this).serialize();
		
		$.ajax({
	        url: ajaxUrl,
	        type: 'POST',
	        data: {
	            action: 'otech_event_filter',
	            wp_nonce: wpNonce,
	            filter_data: data
	        },
	        beforeSend: function (xhr) {

            },
	        success: function( response ) {
	        	console.log(response);
	            $('body').find('#filter-container').html(response);
	        }
	    });
	});

	jQuery.ajax( {
	    beforeSend: function ( xhr ) {
	        xhr.setRequestHeader( 'X-WP-Nonce', otech_obj.nonce );
	    },
	    url: otech_obj.root + 'wp/v3/events/',
	    method: 'GET',
	    success: (response) => {
	    	console.log('Success');
		    console.log( response );
		    $('#rest-api-events-container').html(response);
	    },
	    error: (response) => {
	    	console.log('Sorry Error Occured');
	    	console.log(response);
	    }
	});
});