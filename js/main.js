jQuery( document ).ready( function( ) {
	jQuery( ".wppn-wrapper" ).prependTo( "body" );

	jQuery('.wppn-ajax').click( function( event ) {
		event.preventDefault();
		var data = {
		'action': 'wppn_ajax_set_cookie',
		'nonce': jQuery(this).attr("data-nonce")    // We pass php values differently!
		};
		jQuery.post(wppnAjax.ajaxurl, data, function(response) {
			if(response.type == "success") {
				jQuery('.wppn-wrapper').slideUp('slow');
			}
			else {
				alert('not nice');
			}
			
		});
		console.log(wppnAjax.ajaxurl);
	});

});