var fr;
if( !fr ) {
	fr = {};
} else if( typeof fr !== "object" ) {
	throw new Error( 'fr already exists and not an object' );
}

if( !fr.bea ) {
	fr.bea = {};
} else if( typeof fr.bea !== "object" ) {
	throw new Error( 'fr.civ already exists and not an object' );
}

if( !fr.bea.mm ) {
	fr.bea.mm = {};
} else if( typeof fr.bea.mm !== "object" ) {
	throw new Error( 'fr.bea.mm already exists and not an object' );
}

fr.bea.mm = {
	spinner : '',
	messages : null,
	bea_mm : null,
	post_id : 0,
	template_add : '',
	template_edit : '',
	init : function( ) {
		var self = this;

		// Check vars given
		if( typeof bea_mm_vars !== 'object' ) {
			throw new Error( 'bea_mm_vars have to be declared for this script run' );
		}

		// Init vars
		self.post_id = document.getElementById( 'post_ID' ).value;
		self.template_add = document.getElementById( 'bea-mm-tpl-add' ).text;
		self.template_edit = document.getElementById( 'bea-mm-tpl-edit' ).text;
		self.bea_mm = jQuery( "#bea-mm" );
		self.messages = jQuery( document.getElementById( 'bea_mm_messages' ) );
		// Make an object
		self.spinner = jQuery( bea_mm_vars.spinner );
		// All draft generator
		self.initDraftGenerator( '#bea_mm_create_all_drafts' );
		// Unlink
		self.initUnlink( '.del-item' );
	},
	initDraftGenerator : function( sl ) {
		var el = jQuery( sl ), self = this;

		// Handle the generator draft
		el.on( 'click', 'button', function( e ) {
			e.preventDefault( );

			if( !el.hasClass( 'ajaxing' ) ) {
				var bu = jQuery( this ), nonce = bu.data( 'nonce' ), post_type = bu.data( 'post_type' ), blog_id = bu.data( 'blog_id' );

				jQuery.ajax( {
					type : 'POST',
					url : ajaxurl,
					dataType : 'json',
					data : {
						action : 'bea_mm_auto_draft',
						nonce : nonce,
						post_type : post_type,
						blog_id : blog_id,
						id : fr.bea.mm.post_id
					},
					beforeSend : function( ) {
						el.addClass( 'ajaxing' );
						bu.before( fr.bea.mm.spinner.show( ) );
					},
					success : function( resp ) {
						el.removeClass( 'ajaxing' );
						fr.bea.mm.spinner.hide( );

						fr.bea.mm.setMessage( resp.success === true ? "success" : "error", resp.success === true ? _.template( bea_mm_vars.draftSuccess, {
							number : resp.data.length
						} ) : bea_mm_vars.draftFailed );
					}
				} );
			}
		} );
	},
	initUnlink : function( sel ) {
		fr.bea.mm.bea_mm.on( 'click', sel, function( e ) {
			e.preventDefault( );
			var p = jQuery( this ).closest( 'li' ), obj = {
				nonce : p.data( 'nonce-unlink' ),
				id : fr.bea.mm.post_id,
				object_id : p.find( 'input' ).val( ),
				blog_id : p.data( 'blog_id' ),
				post_type : p.data( 'post_type' )
			};
			fr.bea.mm.removeRelation( obj, p );
		} );
	},
	addRelation : function( translation ) {
		var bu = jQuery( '#bea-mm-translation-' + translation.blog_id ), input = bu.find( 'input' );
		translation.object_id = translation.id;
		translation.id = fr.bea.mm.post_id;
		translation.action = 'bea_mm_link';

		jQuery.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			data : translation,
			beforeSend : function( ) {
				bu.before( fr.bea.mm.spinner.show( ) );
			},
			success : function( resp ) {
				fr.bea.mm.spinner.hide( );
				fr.bea.mm.setMessage( resp.success === true ? "success" : "error", resp.success === true ? bea_mm_vars.linkSuccess : bea_mm_vars.linkFailed );
				bu.find( '.controls' ).html( _.template( fr.bea.mm.template_edit, resp.data ) );
				input.val( translation.object_id );
			}
		} );
	},
	removeRelation : function( translation ) {
		var bu = jQuery( '#bea-mm-translation-' + translation.blog_id );
		translation.action = 'bea_mm_unlink';

		jQuery.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			data : translation,
			beforeSend : function( ) {
				bu.before( fr.bea.mm.spinner.show( ) );
			},
			success : function( resp ) {
				fr.bea.mm.spinner.hide( );
				fr.bea.mm.setMessage( resp.success === true ? "success" : "error", resp.success === true ? bea_mm_vars.linkSuccess : bea_mm_vars.linkFailed );
				bu.find( '.controls' ).html( _.template( fr.bea.mm.template_add, resp.data ) );
				bu.find( 'input' ).val( 0 );
			}
		} );
	},
	setMessage : function( status, message ) {
		fr.bea.mm.messages.removeClass( 'error success' ).addClass( status ).html( message );
	}
};

jQuery( function( ) {
	fr.bea.mm.init( );
} );
