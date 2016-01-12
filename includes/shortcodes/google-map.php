<?php
class Item_County_Google_Map_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_google_map';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Google Map';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'Embed a Google Map.';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @return string
	 *
	 * @todo Some kind of indexing on container and map_data object.
	 */
	public function item( $atts ) {

		$defaults = array(
			'address' => '',
			'coords'  => '',
			'title'   => '',
			'desc'    => '',
			'zoom'    => 10
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['address'] ) && empty( $atts['coords'] ) ) {
			return '';
		}

		wp_enqueue_script( 'google_maps_api', '//maps.googleapis.com/maps/api/js?sensor=false', array(), false, true );
		wp_enqueue_script( 'google-map-embed', get_stylesheet_directory_uri() . '/js/google-map-embed.js', array( 'google_maps_api' ), false, true );

		$map_data = array( // check sanitizing
			'address' => esc_html( $atts['address'] ),
			'title'   => esc_html( $atts['title'] ),
			'desc'    => esc_html( $atts['desc'] ),
			'zoom'    => esc_html( $atts['zoom'] ),
		);

		if ( ! empty( $atts['coords'] ) ) {
			$map_data['coords'] = esc_html( $atts['coords'] );
		}

		wp_localize_script( 'google-map-embed', 'map_data', $map_data );

		ob_start();
		?>
		<div style="padding-bottom: 100%; position:relative; width: 100%;"><div id="county-google-map" style="height: 100%; position: absolute; width: 100%;"></div></div>
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

		ob_start();
		?>
		<p>A map.</p>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

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

		$html = Forms_PB::text_field( $this->get_name_field('address'), $atts['address'], 'Address' ); // or lat and long
		$html .= Forms_PB::text_field( $this->get_name_field('title'), $atts['title'], 'Marker info window title' );
		$html .= Forms_PB::text_field( $this->get_name_field('desc'), $atts['desc'], 'Marker info window description' );
		$html .= Forms_PB::select_field( $this->get_name_field('zoom'), $atts['zoom'], array_combine( range( 1, 21 ), range( 1, 21 ) ), 'Map zoom level<br />(1 = furthest, 21 = closest)' );

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

		$clean['address'] = ( ! empty( $atts['address'] ) ) ? sanitize_text_field( $atts['address'] ) : '';
		$clean['title'] = ( ! empty( $atts['title'] ) ) ? sanitize_text_field( $atts['title'] ) : '';
		$clean['desc'] = ( ! empty( $atts['desc'] ) ) ? sanitize_text_field( $atts['desc'] ) : '';
		$clean['zoom'] = ( ! empty( $atts['zoom'] ) ) ? (int) sanitize_text_field( $atts['zoom'] ) : '';

		return $clean;

	}

}