<?php
class County_Extension_Site_Search {

	public function __construct() {
		add_shortcode( 'county_site_search', array( $this, 'display_county_site_search' ) );
	}

	/**
	 * Display custom markup used for slideshows.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_county_site_search( $atts ) {

		$defaults = array(
			'' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		/*if ( empty( $atts[''] ) ) {
			return '';
		}*/
		
		ob_start();
		?>
		<form role="search" method="get" class="cahnrs-search" action="<?php echo home_url( '/' ); ?>">
			<?php /*<input type="hidden" name="post_type" value="impact">*/ ?>
			<label>
				<span class="screen-reader-text">Search for:</span>
				<input type="search" class="cahnrs-search-field" placeholder="Search" value="<?php echo get_search_query(); ?>" name="s" title="Search for:" />
			</label>
			<input type="submit" class="cahnrs-search-submit" value="$" />
		</form>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

new County_Extension_Site_Search();