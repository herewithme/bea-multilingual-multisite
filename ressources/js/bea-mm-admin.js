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
	init : function() {
		var _s = this;
		
		_s.initAjaxChosen( '.bea_chosen_select' );
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
			allow_single_deselect: true,
		} );
	}
}

jQuery( function() {
	fr.bea.mm.init();
});