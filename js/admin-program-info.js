jQuery(document).ready(function($) {
	$('#page_template').change(function() {
		$('#cahnrswp_county_program_info').toggle( $(this).val() == 'templates/program.php' );
	}).change();
});