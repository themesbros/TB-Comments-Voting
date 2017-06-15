jQuery( document ).ready( function($) {

	$( '.tb-comment-vote' ).click( function( e ) {

		e.preventDefault();

		var self 	 = $( this ),
			postId   = self.data( 'postid' ),
			type     = self.data( 'type' ),
			id       = self.data( 'id' ),
			readonly = self.data( 'readonly' ),
			data     = {
				action: 'tbcv_comment_vote',
				postid: postId,
				id: id,
				type: type,
				security: tbcv.nonce,
			};

		if ( readonly ) {
			return;
		}

		$.post( tbcv.url, data, function( response ) {
			if ( '' != response ) {
				if ( type == 'down' )
					response = '-' + response;
				$( 'span', self ).text( response );
				$( '#comment-' + id + ' .tb-comment-vote' ).attr( 'data-readonly', 'true' );
			}
		});

	});

});