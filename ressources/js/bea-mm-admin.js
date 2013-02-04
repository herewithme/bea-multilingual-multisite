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
	messages : null,
	bea_mm : null,
	post_id : 0,
	template_add : '',
	template_edit : '',
	need_translation : [],
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
		self.translations = self.bea_mm.find( '.list-relations li' );
		self.draftsGenerator = document.getElementById( 'bea_mm_create_drafts' );

		// All draft generator
		self.initDraftGenerator( '#bea_mm_create_all_drafts' );
		
		// Selectable draft generators
		self.initSelectableDraftGenerator( '#bea_mm_create_drafts' );
		
		// Init tha var with translations empty
		self.initNeedTranslations();
		
		// Unlink
		self.initUnlink( '.del-item' );
	}, initNeedTranslations : function() {
		var self = this;
		self.need_translation = [];
		
		// Make an array with all the blog_ids to translate
		_.each( self.translations, function( el, i ) {
			if( el.getAttribute( 'data-translated' ) === 'false' ) {
				self.need_translation.push( el.getAttribute( 'data-blog_id' ) );
			} else {
				self.showDraftCheckBox( el.getAttribute( 'data-blog_id' ) );
			}
		});
		
		// Do not show the draft generator if no transalations
		if( self.need_translation.length === 0 ) {
			jQuery( '.tools-relations' ).hide();
		} else {
			jQuery( '.tools-relations' ).show();
		}
		
		// Hide/show the checkboxes lines
		self.hideDraftCheckBox();
		
		return self.need_translation;
	},
	hideDraftCheckBox : function() {
		var self = this;
		_.each( self.need_translation, function( el ) {
			jQuery( '#bea-mm-draft-'+el ).removeProp( 'checked' ).closest( 'li' ).show();
		});
	},
	showDraftCheckBox : function( id ) {
		if( _.isNaN( parseInt( id, 10 ) ) ) {
			return false;
		}
		jQuery( '#bea-mm-draft-'+id ).removeProp( 'checked' ).closest( 'li' ).hide();
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
						fr.bea.mm.setMessage( 'alert', bea_mm_vars.allDraftWaiting );
					},
					success : function( resp ) {
						el.removeClass( 'ajaxing' );
						// Add success//error message
						fr.bea.mm.setMessage( resp.success === true ? "success" : "failure", resp.success === true ? _.template( bea_mm_vars.draftSuccess, {
							number : resp.data.length
						} ) : bea_mm_vars.draftFailed );
						
						// Display all the needed drafts
						if( resp.success === true ) {
							_.each( resp.data, function( value ) {
								fr.bea.mm.makeEditLine( value.blog_id, value );
							} );
						}
					}
				} );
			}
		} );
	},
	initSelectableDraftGenerator : function( sl ) {
		var el = jQuery( sl ), self = this;

		// Handle the generator draft
		el.on( 'click', '.add-draft', function( e ) {
			e.preventDefault( );

			if( !el.hasClass( 'ajaxing' ) ) {
				var bu = jQuery( this ), 
				nonce = bu.data( 'nonce' ), 
				blog_ids = el.find( 'input:checked' ).map(function() {return this.value;}).get(),
				num_drafts = blog_ids.length,
				i= 0;
				
				if( num_drafts <= 0 ) {
					fr.bea.mm.appendMessage( 'failure', bea_mm_vars.selectLanguage );
					return false;
				}
				
				fr.bea.mm.createDraftForBlogs( blog_ids, nonce );
			}
		} );
	},
	createDraftForBlogs : function( blog_ids, nonce ) {
		fr.bea.mm.appendMessage( 'alert', bea_mm_vars.allDraftWaiting );
		fr.bea.mm.createDraftForBlog( blog_ids, nonce, 0, fr.bea.mm.createDraftForBlog );
	},
	createDraftForBlog : function( blog_ids, nonce, i ,callback ) {
		var c_id = blog_ids[i];
		i += 1;
		
		// Check this is ok
		if( typeof c_id == 'undefined' ) {
			return false;
		}
		
		// Generate the selected drafts
		jQuery.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			async : true,
			data : {
				action : 'bea_mm_selected_drafts',
				nonce : nonce,
				blog_ids : [c_id],
				id : fr.bea.mm.post_id
			},
			success : function( resp ) {
				// add the message
				fr.bea.mm.appendMessage( resp.success === true ? "success" : "failure", resp.success === true ? _.template( bea_mm_vars.draftSuccess, {
					number : resp.data.length
				} ) : bea_mm_vars.draftFailed );
				
				// Add the templates for the selected drafts
				if( resp.success === true ) {
					_.each( resp.data, function( value ) {
						fr.bea.mm.makeEditLine( value.blog_id, value );
					} );
				}
				callback( blog_ids, nonce, i, callback );
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
		var bu = jQuery( '#bea-mm-translation-' + translation.blog_id ), input = bu.find( 'input' ), spinner = bu.find('.spinner');
		translation.object_id = translation.id;
		translation.id = fr.bea.mm.post_id;
		translation.action = 'bea_mm_link';

		if( _.isNaN( parseInt( translation.object_id, 10 ) ) ) {
			fr.bea.mm.appendMessage( 'failure', bea_mm_vars.selectSomething );
			return false;
		}

		jQuery.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			data : translation,
			beforeSend : function( ) {
				fr.bea.mm.setMessage( 'alert', bea_mm_vars.linkWaiting );
				spinner.show();
			},
			success : function( resp ) {
				spinner.hide();
				fr.bea.mm.appendMessage( resp.success === true ? "success" : "failure", resp.success === true ? bea_mm_vars.linkSuccess : bea_mm_vars.linkFailed );
				fr.bea.mm.makeEditLine( translation.blog_id, resp.data );
				input.val( translation.object_id );
			}
		} );
	},
	removeRelation : function( translation ) {
		var bu = jQuery( '#bea-mm-translation-' + translation.blog_id ), spinner = bu.find('.spinner');
		translation.action = 'bea_mm_unlink';

		jQuery.ajax( {
			type : 'POST',
			url : ajaxurl,
			dataType : 'json',
			data : translation,
			beforeSend : function( ) {
				fr.bea.mm.setMessage( 'alert', bea_mm_vars.unlinkWaiting );
				spinner.show();
			},
			success : function( resp ) {
				spinner.hide();
				fr.bea.mm.setMessage( resp.success === true ? "success" : "failure", resp.success === true ? bea_mm_vars.unlinkSuccess : bea_mm_vars.unlinkFailed );
				fr.bea.mm.makeDefaultLine( translation.blog_id, translation );
				bu.find( 'input' ).val( 0 );
			}
		} );
	},
	makeEditLine : function( blog_id, data ) {
		if( typeof data !== 'object' || _.isNaN( parseInt( blog_id, 10 ) ) ) {
			return false;
		}

		var but = jQuery( '#bea-mm-translation-' + blog_id );
		but.attr( 'data-translated', 'true' );
		but.find( '.controls' ).html( _.template( fr.bea.mm.template_edit, data ) );
		this.initNeedTranslations();
		return true;
	},
	makeDefaultLine : function( blog_id, data ) {
		if( typeof data !== 'object' || _.isNaN( parseInt( blog_id, 10 ) ) ) {
			return false;
		}

		var but = jQuery( '#bea-mm-translation-' + blog_id );
		but.attr( 'data-translated', 'false' );
		but.find( '.controls' ).html( _.template( fr.bea.mm.template_add, data ) );
		this.initNeedTranslations();
		return true;
	},
	setMessage : function( status, message ) {
		fr.bea.mm.messages.removeClass( 'hidden failure success alert' ).addClass( status ).html( message );
	},
	appendMessage : function( status, message ) {
		fr.bea.mm.messages.removeClass( 'hidden failure success alert' ).addClass( status ).append( '<p>'+message+'</p>' );
	}
};

jQuery( function( ) {
	fr.bea.mm.init( );
} );
