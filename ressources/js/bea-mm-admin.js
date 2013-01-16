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
		var el = jQuery( sl ), _s = this;
		
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
						
						_s.draftSelect( resp )
						console.log( { number : resp.data.length } );
						el.find( '.messages' ).removeClass('error success').addClass( resp.success === true ? "success" : "error" ).html( resp.success === true ? _.template( bea_mm_vars.draftSuccess, { number : resp.data.length } ) : bea_mm_vars.draftFailed );
					}
				} );
			}
		} );
	},
	draftSelect : function( resp ) {
		var total = resp.data.length,
		i =0,
		tmpl = "<option value='<%= object_id %>'><%= title %></option>";
		
		if( resp.data.success === false ) {
			return 'Error';
		}
		
		for( i ; i < resp.data.length; i++ ) {
			var select = document.getElementById( 'translations-'+resp.data[i].blog_id );
			
			if( select === null ) {
				continue;
			}
			
			/// Create dom object option and add it to his select
			var opt = document.createElement("option");
			opt.value = resp.data[i].object_id;
			opt.innerText = resp.data[i].title;
			opt.selected = true;
			select.appendChild( opt );
			
			// Refresh chosen
			jQuery( '.bea_chosen_select' ).trigger("liszt:updated");
			
		}
	}
};

jQuery( function() {
	fr.bea.mm.init();
});