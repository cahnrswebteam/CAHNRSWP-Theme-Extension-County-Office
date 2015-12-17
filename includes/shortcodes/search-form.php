<?php
class Item_County_Site_Search_Form_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_site_search_form';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Site Search Form';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = '';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @return string
	 */
	public function item() {

		ob_start();
		?>
		<form role="search" method="get" class="cahnrs-search" action="<?php echo home_url( '/' ); ?>">
			<label>
				<span class="screen-reader-text">Search for:</span>
				<input type="search" class="cahnrs-search-field" placeholder="Search" value="<?php echo get_search_query(); ?>" name="s" title="Search for:" />
			</label>
			<input type="submit" class="cahnrs-search-submit" value="$" />
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Editor markup.
	 *
	 * @return string
	 */
	public function editor() {

		$html = 'Search form';

		return $html;

	}

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @param $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function form( $atts ) {

		$html = Forms_PB::text_field( $this->get_name_field('label'), $atts['label'], 'Label' );

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return array
	 */
	public function clean( $atts ) {

		$clean = array();

		$clean['label'] = ( ! empty( $atts['label'] ) ) ? sanitize_text_field( $atts['label'] ) : '';

		return $clean;

	}

}