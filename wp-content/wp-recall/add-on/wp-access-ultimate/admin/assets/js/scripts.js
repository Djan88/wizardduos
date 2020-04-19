jQuery( document ).ready( function( $ ) {

	$( '#wau-manager .rcl-custom-field form' ).submit( function( e ) {

		var form = jQuery( this );

		rcl_preloader_show( form );

		rcl_ajax( {
			data: 'action=wau_ajax_manager_update_data&' + form.serialize(),
			success: function( result ) {

				if ( result['update-page'] ) {
					location.reload();
					return;
				}

				form.parents( 'li#field-' + result.id )
					.children( '.field-header' )
					.children( '.field-title' )
					.text( result.id + ': ' + result.title );
			}
		} );

		return false;

	} );

	$( '.wau-accounts-list' ).find( '.account-check:checked' ).each( function() {

		wau_set_props_checkboxes( this );

	} );

	$( '.wau-accounts-list .account-check' ).click( function() {

		wau_set_props_checkboxes( this );

	} );

} );

function wau_set_props_checkboxes( e ) {

	var check = ( jQuery( e ).attr( "checked" ) != 'checked' ) ? false : true;

	jQuery( e ).parent().find( '.wau-childrens-list .account-check' ).each( function() {
		jQuery( this ).prop( "disabled", check ).prop( "checked", false );
	} );

}

function wau_delete_manager_item( e ) {

	if ( !confirm( 'Вы уверены?' ) )
	 return false;

	var item = jQuery( e ).parents( '.rcl-custom-field' );

	rcl_ajax( {
		data: {
			action: 'wau_ajax_get_manager_item_delete_form',
			'item-type': item.data( 'type' ),
			'item-id': item.data( 'slug' )
		}
	} );

	return false;
}

function wau_manager_save_sort( typeObject ) {

	var fields = new Array;
	jQuery( "#wau-manager ." + typeObject + "-list ul" ).find( ".rcl-custom-field" ).each( function( a, b ) {
		fields[a] = {
			"id": jQuery( this ).attr( "data-slug" ),
			/*"parent": jQuery(this).attr("data-parent")*/
		}
	} );

	var box = jQuery( "#wau-manager" );

	rcl_preloader_show( box );

	var dataString = "action=wau_ajax_update_sort_" + typeObject + "&sort=" + JSON.stringify( fields );

	jQuery.ajax( {
		type: "POST",
		data: dataString,
		dataType: "json",
		url: ajaxurl,
		success: function( result ) {

			rcl_preloader_hide();

			if ( result["error"] ) {
				rcl_notice( result["error"], "error", 10000 );
				return false;
			}

			rcl_notice( result["success"], "success", 10000 );

		}
	} );

}