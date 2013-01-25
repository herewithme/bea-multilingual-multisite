var bea_mm_link;

(function( $ ) {
	var inputs = {}, rivers = {}, ed, River, Query;

	bea_mm_link = {
		timeToTriggerRiver : 150,
		minRiverAJAXDuration : 200,
		riverBottomThreshold : 5,
		keySensitivity : 100,
		lastSearch : '',
		textarea : '',

		init : function( ) {
			var d = jQuery( '#bea-mm' );
			d.on( 'click', '.add_relation', function( e ) {
				e.preventDefault( );
				var el = jQuery( this ).closest( 'li' );
				inputs.post_type = el.data( 'post_type' );
				inputs.blog_id = el.data( 'blog_id' );

				// Nonce
				inputs.nonce = el.data( 'nonce' );
				inputs.nonce_link = el.data( 'nonce-link' );

				bea_mm_link.open( );
			} );

			inputs.dialog = $( '#bea-mm-link' );
			inputs.submit = $( '#bea-mm-link-submit' );
			inputs.id = $( '#bea-mm-id' );

			inputs.search = $( '#bea-mm-search-field' );
			// Build Rivers
			rivers.search = new River( $( '#bea-mm-search-results' ) );
			rivers.recent = new River( $( '#bea-mm-most-recent-results' ) );
			rivers.elements = $( '.query-results', inputs.dialog );

			// Bind event handlers
			inputs.dialog.keydown( bea_mm_link.keydown );
			inputs.dialog.keyup( bea_mm_link.keyup );
			inputs.submit.click( function( e ) {
				e.preventDefault( );
				bea_mm_link.update( );
			} );
			$( '#bea-mm-link-cancel' ).click( function( e ) {
				e.preventDefault( );
				bea_mm_link.close( );
			} );

			rivers.elements.bind( 'river-select', bea_mm_link.updateFields );

			inputs.search.keyup( bea_mm_link.searchInternalLinks );

			inputs.dialog.bind( 'wpdialogrefresh', bea_mm_link.refresh );
		},

		open : function( ) {
			// Initialize the dialog if necessary (html mode).
			if( ! inputs.dialog.data( 'wpdialog' ) ) {
				inputs.dialog.wpdialog( {
					title : bea_mm_linkL10n.title,
					width : 480,
					height : 'auto',
					modal : true,
					dialogClass : 'wp-dialog',
					zIndex : 300000

				} );
			}

			inputs.dialog.wpdialog( 'open' );
		},

		refresh : function( ) {
			// Refresh rivers (clear links, check visibility)
			rivers.search.refresh( );
			rivers.recent.refresh( );

			bea_mm_link.setDefaultValues( );

			// Load the most recent results if this is the first time opening the panel.
			if( !rivers.recent.ul.children( ).length ) {
				rivers.recent.ajax( );
			}
		},

		close : function( ) {
			inputs.dialog.wpdialog( 'close' );
		},

		getAttrs : function( ) {
			return {
				id : inputs.id.val( ),
				blog_id : inputs.blog_id,
				post_type : inputs.post_type,
				nonce : inputs.nonce_link
			};
		},

		update : function( ) {
			bea_mm_link.htmlUpdate( );
		},

		htmlUpdate : function( ) {
			var attrs;
			// Get the attrs
			attrs = bea_mm_link.getAttrs( );

			// Call other plugin
			fr.bea.mm.addRelation( attrs );

			bea_mm_link.close( );
		},

		updateFields : function( e, li, originalEvent ) {
			inputs.id.val( li.data( 'id' ) );
		},
		setDefaultValues : function( ) {
			// Update save prompt.
			inputs.submit.val( bea_mm_linkL10n.save );
		},

		searchInternalLinks : function( ) {
			var t = $( this ), waiting, search = t.val( );

			if( search.length > 2 ) {
				rivers.recent.hide( );
				rivers.search.show( );

				// Don't search if the keypress didn't change the title.
				if( bea_mm_link.lastSearch == search ) {
					return;
				}

				bea_mm_link.lastSearch = search;
				waiting = t.siblings( 'img.waiting' ).show( );

				rivers.search.change( search );
				rivers.search.ajax( function( ) {
					waiting.hide( );
				} );
			} else {
				rivers.search.hide( );
				rivers.recent.show( );
			}
		},

		next : function( ) {
			rivers.search.next( );
			rivers.recent.next( );
		},
		prev : function( ) {
			rivers.search.prev( );
			rivers.recent.prev( );
		},

		keydown : function( event ) {
			var fn, key = $.ui.keyCode;

			switch( event.which ) {
				case key.UP:
					fn = 'prev';
				case key.DOWN:
					fn = fn || 'next';
					clearInterval( bea_mm_link.keyInterval );
					bea_mm_link[ fn ]( );
					bea_mm_link.keyInterval = setInterval( bea_mm_link[fn], bea_mm_link.keySensitivity );
					break;
				default:
					return;
			}
			event.preventDefault( );
		},
		keyup : function( event ) {
			var key = $.ui.keyCode;

			switch( event.which ) {
				case key.ESCAPE:
					event.stopImmediatePropagation( );
					if( ! $( document ).triggerHandler( 'wp_CloseOnEscape', [{
						event : event,
						what : 'bea_mm_link',
						cb : bea_mm_link.close
					}] ) ) {
						bea_mm_link.close( );
					}
					return false;
					break;
				case key.UP:
				case key.DOWN:
					clearInterval( bea_mm_link.keyInterval );
					break;
				default:
					return;
			}
			event.preventDefault( );
		},

		delayedCallback : function( func, delay ) {
			var timeoutTriggered, funcTriggered, funcArgs, funcContext;

			if( !delay )
				return func;

			setTimeout( function( ) {
				if( funcTriggered )
					return func.apply( funcContext, funcArgs );
				// Otherwise, wait.
				timeoutTriggered = true;
			}, delay );

			return function( ) {
				if( timeoutTriggered )
					return func.apply( this, arguments );
				// Otherwise, wait.
				funcArgs = arguments;
				funcContext = this;
				funcTriggered = true;
			};
		}
	}

	River = function( element, search ) {
		var self = this;
		this.element = element;
		this.ul = element.children( 'ul' );
		this.waiting = element.find( '.river-waiting' );

		this.change( search );
		this.refresh( );

		element.scroll( function( ) {
			self.maybeLoad( );
		} );
		element.delegate( 'li', 'click', function( e ) {
			self.select( $( this ), e );
		} );
	};

	$.extend( River.prototype, {
		refresh : function( ) {
			this.deselect( );
			this.visible = this.element.is( ':visible' );
		},
		show : function( ) {
			if( !this.visible ) {
				this.deselect( );
				this.element.show( );
				this.visible = true;
			}
		},
		hide : function( ) {
			this.element.hide( );
			this.visible = false;
		},
		// Selects a list item and triggers the river-bea-mm-select event.
		select : function( li, event ) {
			var liHeight, elHeight, liTop, elTop;

			if( li.hasClass( 'unselectable' ) || li == this.selected ) {
				event.preventDefault( );
			}

			this.deselect( );
			this.selected = li.addClass( 'selected' );
			// Make sure the element is visible
			liHeight = li.outerHeight( );
			elHeight = this.element.height( );
			liTop = li.position( ).top;
			elTop = this.element.scrollTop( );

			if( liTop < 0 ) {// Make first visible element
				this.element.scrollTop( elTop + liTop );
			} else if( liTop + liHeight > elHeight ) {// Make last visible element
				this.element.scrollTop( elTop + liTop - elHeight + liHeight );
			}

			// Trigger the river-bea-mm-select event
			this.element.trigger( 'river-select', [li, event, this] );
		},
		deselect : function( ) {
			if( this.selected ) {
				this.selected.removeClass( 'selected' );
			}
			this.selected = false;
		},
		prev : function( ) {
			if( !this.visible )
				return;

			var to;
			if( this.selected ) {
				to = this.selected.prev( 'li' );
				if( to.length )
					this.select( to );
			}
		},
		next : function( ) {
			if( !this.visible ) {
				return;
			}

			var to = this.selected ? this.selected.next( 'li' ) : $( 'li:not(.unselectable):first', this.element );
			if( to.length ) {
				this.select( to );
			}
		},
		ajax : function( callback ) {
			var self = this, delay = this.query.page == 1 ? 0 : bea_mm_link.minRiverAJAXDuration, response = bea_mm_link.delayedCallback( function( results, params ) {
				self.process( results, params );
				if( callback ) {
					callback( results, params );
				}
			}, delay );

			this.query.ajax( response );
		},
		change : function( search ) {
			if( this.query && this._search == search )
				return;

			this._search = search;
			this.query = new Query( search );
			this.element.scrollTop( 0 );
		},
		process : function( results, params ) {
			var list = '', alt = true, classes = '', firstPage = params.page == 1;

			if( !results.success ) {
				if( firstPage ) {
					list += _.template( document.getElementById( 'bea-mm-search-line-empty' ).text )
				}
			} else {
				list = _.template( document.getElementById( 'bea-mm-search-line' ).text, {
					'results' : results.data
				} );
			}

			this.ul[ firstPage ? 'html' : 'append' ]( list );
		},
		maybeLoad : function( ) {
			var self = this, el = this.element, bottom = el.scrollTop( ) + el.height( );

			if( ! this.query.ready( ) || bottom < this.ul.height( ) - bea_mm_link.riverBottomThreshold )
				return;

			setTimeout( function( ) {
				var newTop = el.scrollTop( ), newBottom = newTop + el.height( );

				if( ! self.query.ready( ) || newBottom < self.ul.height( ) - bea_mm_link.riverBottomThreshold )
					return;

				self.waiting.show( );
				el.scrollTop( newTop + self.waiting.outerHeight( ) );

				self.ajax( function( ) {
					self.waiting.hide( );
				} );
			}, bea_mm_link.timeToTriggerRiver );
		}
	} );

	Query = function( search ) {
		this.page = 1;
		this.allLoaded = false;
		this.querying = false;
		this.search = search;
	};

	$.extend( Query.prototype, {
		ready : function( ) {
			return !(this.querying || this.allLoaded );
		},
		ajax : function( callback ) {
			var self = this, query = {
				action : 'bea_mm_search',
				page : this.page,
				'nonce' : inputs.nonce,
				'post_type' : inputs.post_type,
				'blog_id' : inputs.blog_id,
			};

			if( this.search ) {
				query.search = this.search;
			}

			this.querying = true;

			$.post( ajaxurl, query, function( r ) {
				self.page++;
				self.querying = false;
				self.allLoaded = !r;
				callback( r, query );
			}, "json" );
		}
	} );

	$( document ).ready( bea_mm_link.init );
})( jQuery );
