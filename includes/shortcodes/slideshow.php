<?php
class County_Extension_Slideshow {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
		add_shortcode( 'county_slideshow', array( $this, 'display_county_slideshow' ) );
	}

	/**
	 * Enqueue scripts and styles for the slideshow.
	 */
	public function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && has_shortcode( $post->post_content, 'county_slideshow' ) ) {
			//wp_enqueue_style( 'cahnrswp-extension-county-slideshow', get_stylesheet_directory_uri() . '/css/slideshow.css' );
			//wp_enqueue_script( 'cahnrswp-extension-county-slideshow' get_stylesheet_directory_uri() . '/js/slideshow.js', array( 'jquery' ) );
		}
	}

	/**
	 * Display custom markup used for slideshows.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_county_slideshow( $atts ) {

		$defaults = array(
			'' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts[''] ) ) {
			return '';
		}
		
		ob_start();
		?>
		<div class="county-slideshow">

		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

new County_Extension_Slideshow();