jQuery( function($) {

	/**
	 * Make Action Buttons widget exclusive to County Actions sidebar.
	 *
	 * Could likely use some tidying and other help.
	 */

	// From Available or Inactive Widgets:
	$('.widget').on( 'dragcreate dragstart', function( event, ui ) {
		var id = $(this).find( 'input[name="id_base"]' ).val(),
				current_count = $( '#county-actions .widget' ).length;
		if ( id === 'county_actions_widget' ) {
			if ( current_count < 1 ) {
				$(this).draggable( 'option', 'connectToSortable', '#county-actions, #wp_inactive_widgets' );
			} else {
				$(this).draggable( 'option', 'connectToSortable', '#wp_inactive_widgets' );
			}
		} else {
			$(this).draggable( 'option', 'connectToSortable', ':not(#county-actions)' );
		}
	});

	// From another sidebar:
	$( '.widgets-sortables' ).on( 'sortactivate sort', function( event, ui ) {
		var id = $(ui.item).find( 'input[name="id_base"]' ).val(),
				current_count = $( '#county-actions .widget' ).length;
		if ( $(ui.sender).attr( 'id' ) !== 'county-actions' && id !== 'county_actions_widget' ) {
			$(this).sortable( 'option', 'connectWith', ':not(#county-actions)' );
			$(this).sortable( 'refresh' );
		}
		if ( id === 'county_actions_widget' ) {
			if ( current_count < 1 ) {
				$(this).sortable( 'option', 'connectWith', '#county-actions, #wp_inactive_widgets' );
			} else {
				$(this).sortable( 'option', 'connectWith', '#wp_inactive_widgets' );
			}
			$(this).sortable( 'refresh' );
		}
	});

});