jQuery(document).ready(function($) {
	// Toggle program info metabox
	$('#page_template').change(function() {
		$('#cahnrswp_county_program_info').toggle( $(this).val() == 'templates/program.php' );
	}).change();
	// Show program boilerplate.
	$('#county-program-icon').change(function() {
		switch ( $(this).val() ) {
			case 'four-h':
				boilerplate_toggle('4-H boilerplate placeholder.');
				break;
			case 'ag':
				boilerplate_toggle('Agriculture boilerplate placeholder.');
				break;
			case 'ced':
				boilerplate_toggle('Community and Economic Development boilerplate placeholder.');
				break;
			case 'family':
				boilerplate_toggle('Family and Home boilerplate placeholder.');
				break;
			case 'food':
				boilerplate_toggle('Food and Nutrition boilerplate placeholder.');
				break;
			case 'gardening':
				boilerplate_toggle('Gardening boilerplate placeholder.');
				break;
			case 'natural-resources':
				boilerplate_toggle('Natural Resources boilerplate placeholder.');
				break;
			default:
				$('#cahnrswp-program-boilerplate').removeClass('program-changed').html();
		}
	});
	var boilerplate_border_fade;
	function boilerplate_toggle(text) {
		$('#cahnrswp-program-boilerplate').stop().css('border-left-color', '#7ad03a').addClass('program-changed').html(text);
		window.clearTimeout(boilerplate_border_fade);
		boilerplate_border_fade = setTimeout(function(){
			$({alpha:1}).animate({alpha:0}, {
				duration: 1000,
				step: function(){
					$('#cahnrswp-program-boilerplate').css('border-left-color','rgba(122,208,58,'+this.alpha+')');
				}
			});
		}, 1500);
  }
});