jQuery.noConflict();
jQuery( function( $ ) {
	'use strict';

	var pluginData = tfPageKeysData,
		$addNew = $( 'a.add-new-h2' ),
		$form = $( '#page-keys-form' ),
		$listTable = $form.find( '.wp-list-table.page-keys' ),
		$inputs = $listTable.find( 'input.page-key' ),
		$submit = $form.find( '#submit' ),
		$duplicatesNotice = $form.find( '.error.inline' ),
		unsavedChanges = false;

	window.onbeforeunload = function() {
		if ( unsavedChanges ) {
			return pluginData.messages.unload;
		}
	};

	$form.submit( function() {
		window.onbeforeunload = null;
	} );

	$duplicatesNotice.hide();

	function checkForDuplicates( pageKey ) {
		var $inputs = $listTable.find( 'input.page-key' ).filter( function() { return $( this ).val() === pageKey } ),
			duplicatesFound = $inputs.length > 1;
		$inputs.toggleClass( 'duplicate', duplicatesFound );
		$duplicatesNotice.toggle( duplicatesFound );
		$submit.prop( 'disabled', duplicatesFound );
	}

	$inputs.prop( 'readonly', true );

	$listTable.on( 'change', 'input.page-key', function() {
		checkForDuplicates( $( this ).val() );
	} );

	$addNew.on( 'click', function( e ) {
		e.preventDefault();

		var data = {
			_wpnonce: pluginData.nonce,
			action  : pluginData.actions.add
		};
		$.post( pluginData.url, data, function( response ) {
			if ( response.success ) {
				var $tr = $listTable.find( 'tbody tr' ).last(),
					$row = $( response.data.row );
				if ( $tr.hasClass( 'alternate' ) ) {
					$row.removeClass( 'alternate' );
				}
				$tr.after( $row );
				$row.find( 'input.page-key' ).select();
				$submit.prop( 'disabled', false );
				unsavedChanges = true;
			} else {
				$form.before( response.data.errors );
			}
		} );
	} );

	$listTable.on( 'click', 'a.edit', function( e ) {
		e.preventDefault();

		$( this ).closest( 'td' ).find( 'input' ).prop( 'readonly', false ).select();
		$submit.prop( 'disabled', false );
		unsavedChanges = true;
	} );

	$listTable.on( 'click', 'a.submitdelete', function( e ) {
		e.preventDefault();

		if ( confirm( pluginData.messages.delete ) ) {
			var data = {
				_wpnonce: pluginData.nonce,
				action  : pluginData.actions.delete,
				id      : $( this ).data( 'id' ),
				page_key: $( this ).closest( 'td' ).find( 'input.page-key' ).val()
			};
			$.post( pluginData.url, data, function( response ) {
				$form.before( response.data.errors );
				if ( response.success ) {
					$listTable.find( 'a.submitdelete-' + response.data.id ).closest( 'tr' ).hide( 'slow', function() {
						var pageKey = $( this ).val();
						$( this ).remove();
						checkForDuplicates( pageKey );
					} ).nextAll().toggleClass( 'alternate' );
				}
			} );
		}
	} );

	$submit.prop( 'disabled', true );

	$listTable.on( 'change', 'select', function() {
		$submit.prop( 'disabled', false );
		unsavedChanges = true;
	} );

} );
