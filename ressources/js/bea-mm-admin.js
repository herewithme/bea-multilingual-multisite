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
	init : function() {
		var _s = this;
		
		// Check vars given
		if( typeof bea_mm_vars !== 'object' ) {
			throw new Error( 'bea_mm_vars have to be declared for this script run' );
		}
		
		// Make an object
		_s.spinner = jQuery( bea_mm_vars.spinner );
		
		// Chosen init
		_s.initAjaxChosen( '.bea_chosen_select' );
		
		// All draft generator
		_s.initDraftGenerator( '#bea_mm_create_all_drafts' );
	},
	initAjaxChosen : function( cl ) {
		var el = jQuery( cl ),
		nonce = el.data( 'nonce' ),
		post_type = el.data( 'post_type' ),
		blog_id = el.data( 'blog_id' );
		
		el.ajaxChosen( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			jsonTermKey : 's',
			minTermLength : 2,
			data : {
				action : 'bea_mm_search',
				nonce : nonce,
				post_type : post_type,
				blog_id : blog_id
			}
		}, function( data ) {
			if( data.success === false || typeof data.data !== "object" ) {
				return [];
			}
			
			return data.data;
		}, {
			allow_single_deselect: true
		} );
	},
	initDraftGenerator : function( sl ) {
		var el = jQuery( sl );
		
		// Handle the generator draft
		el.on( 'click', 'button', function( e ) {
			e.preventDefault();
			
			if( !el.hasClass( 'ajaxing' ) ) {
				var bu = jQuery( this ),
					nonce = bu.data( 'nonce' ),
					post_type = bu.data( 'post_type' ),
					blog_id = bu.data( 'blog_id' );
				
				jQuery.ajax( {
					type : 'POST',
					url : ajaxurl,
					dataType : 'json',
					data : {
						action : 'bea_mm_auto_draft',
						nonce : nonce,
						post_type : post_type,
						blog_id : blog_id,
						id : document.getElementById( 'post_ID' ).value
					},
					beforeSend : function() {
						el.addClass( 'ajaxing' );
						bu.before( fr.bea.mm.spinner.show() );
					},
					success : function( resp ) {
						el.removeClass( 'ajaxing' );
						fr.bea.mm.spinner.hide();
					}
				} );
			}
		} );
	}
};

jQuery( function() {
	fr.bea.mm.init();
});