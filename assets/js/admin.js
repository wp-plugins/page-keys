var Plugin = Plugin || {};

/* global jQuery, tfPageKeysData */
;( function( Plugin, $, pluginData ) {
	"use strict";

	Plugin.Button = {
		initialize: function() {
			$( 'a.add-new-h2' ).on( 'click', function( e ) {
				e.preventDefault();

				Plugin.Button.addPageKey();
			} );
		},
		addPageKey: function() {
			var data = {
				_wpnonce: pluginData.nonces.add,
				action  : pluginData.actions.add
			};

			$.post( pluginData.url, data, function( response ) {
				if ( response.success ) {
					var $tr = Plugin.ListTable.$listTable.find( 'tbody tr' ).last(),
						$row = $( response.data.row );

					if ( $tr.hasClass( 'alternate' ) ) {
						$row.removeClass( 'alternate' );
					}

					$tr.after( $row );
					$row.find( 'input.page-key' ).select();
					Plugin.Form.$submit.prop( 'disabled', false );
					Plugin.Form.unsavedChanges = true;
				} else {
					Plugin.Form.$form.before( response.data.errors );
				}
			} );
		}
	};

	$( function() {
		Plugin.Button.initialize();
	} );

} )( Plugin, jQuery, tfPageKeysData );

/* global jQuery, tfPageKeysData */
;( function( Plugin, $, pluginData ) {
	"use strict";

	Plugin.Form = {
		initialize: function() {
			this.unsavedChanges = false;

			window.onbeforeunload = function() {
				if ( Plugin.Form.unsavedChanges ) {
					return pluginData.messages.unload;
				}
			};

			this.$form = $( '#page-keys-form' ).on( 'submit', function() {
				window.onbeforeunload = null;
			} );

			this.$submit = this.$form.find( '#submit' ).prop( 'disabled', true );

			this.$duplicatesNotice = this.$form.find( '.error.inline' ).hide();
		},
		reactOnChanges: function() {
			this.$submit.prop( 'disabled', false );
			this.unsavedChanges = true;
		}
	};

	$( function() {
		Plugin.Form.initialize();
	} );

} )( Plugin, jQuery, tfPageKeysData );

/* global jQuery, tfPageKeysData */
;( function( Plugin, $, pluginData ) {
	"use strict";

	Plugin.ListTable = {
		initialize: function() {
			this.$listTable = Plugin.Form.$form.find( '.wp-list-table.page-keys' )
				.on( 'change', 'input.page-key', function() {
					Plugin.ListTable.checkForDuplicates( $( this ).val() );
				} )
				.on( 'click', 'a.edit', function( e ) {
					e.preventDefault();

					$( this ).closest( 'td' ).find( 'input' ).prop( 'readonly', false ).select();
					Plugin.Form.reactOnChanges();
				} )
				.on( 'change', 'select', function() {
					Plugin.Form.reactOnChanges();
				} )
				.on( 'click', 'a.submitdelete', function( e ) {
					e.preventDefault();

					Plugin.ListTable.deletePageKey( this );
				} );

			this.$listTable.find( 'input.page-key' ).prop( 'readonly', true );
		},
		checkForDuplicates: function( pageKey ) {
			var $inputs = this.$listTable.find( 'input.page-key' ).filter( function() {
					return $( this ).val() === pageKey;
				} ),
				duplicatesFound = $inputs.length > 1;

			$inputs.toggleClass( 'duplicate', duplicatesFound );
			Plugin.Form.$submit.prop( 'disabled', duplicatesFound );
			Plugin.Form.$duplicatesNotice.toggle( duplicatesFound );
		},
		deletePageKey: function( link ) {
			if ( confirm( pluginData.messages.delete ) ) {
				var data = {
					_wpnonce: pluginData.nonces.delete,
					action  : pluginData.actions.delete,
					id      : $( link ).data( 'id' ),
					page_key: $( link ).closest( 'td' ).find( 'input.page-key' ).val()
				};

				$.post( pluginData.url, data, function( response ) {
					Plugin.Form.$form.before( response.data.errors );

					if ( response.success ) {
						Plugin.ListTable.$listTable.find( 'a.submitdelete-' + response.data.id ).closest( 'tr' ).hide( 'slow', function() {
							var pageKey = $( link ).val();

							$( link ).remove();
							Plugin.ListTable.checkForDuplicates( pageKey );
						} ).nextAll().toggleClass( 'alternate' );
					}
				} );
			}
		}
	};

	$( function() {
		Plugin.ListTable.initialize();
	} );

} )( Plugin, jQuery, tfPageKeysData );
