<?php
class Item_County_People_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'wsuwp_people';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'People Directory';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'Shows a listing of personnel.';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @return string
	 */
	/*public function item( $atts ) {
		$defaults = array();
		$atts = shortcode_atts( $defaults, $atts );
		return $html;
	}*/

	/**
	 * Editor markup.
	 *
	 * @return string
	 */
	public function editor( $atts ) {

		ob_start();
		?>
		<p>Click to configure (in development).</p>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function form( $atts ) {

		$query  = Forms_PB::text_field( $this->get_name_field('university_organization_slug'), $atts['university_organization_slug'], 'University Organization' );
		$query .= Forms_PB::text_field( $this->get_name_field('tag'), $atts['tag'], 'Tag' );
		$query .= Forms_PB::text_field( $this->get_name_field('classification'), $atts['classification'], 'Classification' );
		$query .= Forms_PB::text_field( $this->get_name_field('university_category_slug'), $atts['university_category_slug'], 'University Category' );
		$query .= Forms_PB::text_field( $this->get_name_field('university_location_slug'), $atts['university_location_slug'], 'Location' );
		$query .= Forms_PB::text_field( $this->get_name_field('site_category_slug'), $atts['site_category_slug'], 'Category' );

		$display_options = array(
			'default' => 'Default',
			'az'      => 'A-Z list',
			'table'   => 'Table',
		);

		$display  = Forms_PB::select_field( $this->get_name_field('output'), $atts['output'], $display_options, 'Display' );
		$display .= Forms_PB::text_field( $this->get_name_field('actions'), $atts['actions'], 'Filtering tools' );
		$display .= Forms_PB::text_field( $this->get_name_field('head'), $atts['head'], 'Show unit leader first' );
		$display .= Forms_PB::text_field( $this->get_name_field('count'), $atts['count'], 'Limit number of people to...' );

		$html = array(
			'Query'   => $query,
			'Display' => $display,
		);

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return array
	 */
	public function clean( $atts ) {

		$clean = array();

		$clean['university_organization_slug'] = ( ! empty( $atts['university_organization_slug'] ) ) ? sanitize_text_field( $atts['university_organization_slug'] ) : '';
		$clean['tag'] = ( ! empty( $atts['tag'] ) ) ? sanitize_text_field( $atts['tag'] ) : '';
		$clean['classification'] = ( ! empty( $atts['classification'] ) ) ? sanitize_text_field( $atts['classification'] ) : '';
		$clean['university_category_slug'] = ( ! empty( $atts['university_category_slug'] ) ) ? sanitize_text_field( $atts['university_category_slug'] ) : '';
		$clean['university_location_slug'] = ( ! empty( $atts['university_location_slug'] ) ) ? sanitize_text_field( $atts['university_location_slug'] ) : '';
		$clean['site_category_slug'] = ( ! empty( $atts['site_category_slug'] ) ) ? sanitize_text_field( $atts['site_category_slug'] ) : '';
		$clean['output'] = ( ! empty( $atts['output'] ) ) ? sanitize_text_field( $atts['output'] ) : '';
		$clean['actions'] = ( ! empty( $atts['actions'] ) ) ? sanitize_text_field( $atts['actions'] ) : '';
		$clean['head'] = ( ! empty( $atts['head'] ) ) ? sanitize_text_field( $atts['head'] ) : '';
		$clean['count'] = ( ! empty( $atts['count'] ) ) ? sanitize_text_field( $atts['count'] ) : '';

		return $clean;

	}

}