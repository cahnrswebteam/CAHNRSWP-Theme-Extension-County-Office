<?php
class Item_County_Contact_Info_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_contact_info';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Contact Information';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'Contact information and hours of operation';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'large';

	/**
	 * Display custom markup for contact info.
	 *
	 * @param $atts    Shortcode attributes.
	 * @param $content Enclosed content.
	 *
	 * @return string
	 *
	 * @note Sorry for the inline styles.
	 *
	 * @todo Maybe add an 'exclude' attribute (value="comma separated list of components").
	 */
	public function item( $atts, $content ) {

		$defaults = array(
			'show_map' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		$address  = esc_html( spine_get_option( 'contact_streetAddress' ) );
		$locality = esc_html( spine_get_option( 'contact_addressLocality' ) );
		$zip_code = esc_html( spine_get_option( 'contact_postalCode' ) );

		if ( ! empty( $atts['show_map'] ) ) {
			wp_enqueue_script( 'google_maps_api', '//maps.googleapis.com/maps/api/js', array(), false, true );
			wp_enqueue_script( 'google-map-embed', get_stylesheet_directory_uri() . '/js/google-map-embed.js', array( 'google_maps_api' ), false, true );

			$map_address = $address . ' ' . $locality . ' ' . $zip_code;
			$marker_desc = '<div>' . $address . '<br />' . $locality . '<br />' . $zip_code . '</div><div>' . wpautop( wp_kses_post( $content ) ) . '</div>';

			$map_data = array( // check sanitizing
				'address' => $map_address,
				'title'   => esc_html( spine_get_option( 'contact_department' ) ),
				'desc'    => $marker_desc,
				//'zoom'    => 15,
			);

			wp_localize_script( 'google-map-embed', 'map_data', $map_data );
		}

		ob_start();
		?>
		<div class="county-contact" style="padding-bottom: 1rem;">
			<p style="padding-bottom: 0.5em;"><strong><?php echo esc_html( spine_get_option( 'contact_department' ) ); ?></strong></p>
			<p style="padding-bottom: 0.5em;"><?php echo $address; ?><br />
			<?php echo $locality; ?><br />
			<?php echo $zip_code; ?><br />
			<?php echo esc_html( spine_get_option( 'contact_telephone' ) ); ?><br />
			<a href="mailto:<?php echo esc_attr( spine_get_option( 'contact_email' ) ); ?>"><?php echo esc_html( spine_get_option( 'contact_email' ) ); ?></a><br />
			<?php $contact_point = spine_get_option( 'contact_ContactPoint' ); ?>
			<?php if ( ! empty( $contact_point ) ) : ?>
				<a href="<?php echo esc_url( $contact_point ); ?>"><?php echo esc_html( spine_get_option( 'contact_ContactPointTitle' ) ); ?></a>
			<?php endif; ?></p>
			<?php echo wpautop( wp_kses_post( $content ) ); ?>
			<?php if ( ! empty( $atts['show_map'] ) ) : ?>
			<div style="margin-bottom: 1rem; padding-bottom: 100%; position:relative; width: 100%;"><div id="county-google-map" style="height: 100%; position: absolute; width: 100%;"></div></div>
			<?php endif; ?>
		</div>
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
		<div class="county-contact">
			<p style="margin-bottom: 0.5em;"><strong><?php echo esc_html( spine_get_option( 'contact_department' ) ); ?></strong></p>
			<p style="margin: 0 0 0.5em;"><?php echo esc_html( spine_get_option( 'contact_streetAddress' ) ); ?><br />
			<?php echo esc_html( spine_get_option( 'contact_addressLocality' ) ); ?><br />
			<?php echo esc_html( spine_get_option( 'contact_postalCode' ) ); ?><br />
			<?php echo esc_html( spine_get_option( 'contact_telephone' ) ); ?><br />
			<a href="mailto:<?php echo esc_attr( spine_get_option( 'contact_email' ) ); ?>"><?php echo esc_html( spine_get_option( 'contact_email' ) ); ?></a><br />
			<?php $contact_point = spine_get_option( 'contact_ContactPoint' ); ?>
			<?php if ( ! empty( $contact_point ) ) : ?>
				<a href="<?php echo esc_url( $contact_point ); ?>"><?php echo esc_html( spine_get_option( 'contact_ContactPointTitle' ) ); ?></a><br />
			<?php endif; ?></p>
			<?php $empty = array( ' ', '&nbsp;' ); ?>
			<?php echo ( $this->content && ! in_array( $this->content, $empty ) ) ? $this->content : '<p class="cpb-empty">(Click to add hours of operation)</p>'; ?>
		</div>
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

		$html  = '<p>Information from the "Contact Information" fields from "Appearance" > "Customize" will be displayed.</p>';
		$html .= '<p>Please input office hours below.</p>';
		$html .= Forms_PB::checkbox_field( $this->get_name_field('show_map'), 1, $atts['show_map'], 'Include Google Map' );
		$html .= Forms_PB::wp_editor_field( $this->id, $this->content, false, 'cpb-field-one-column' );

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

		$clean['show_map'] = ( ! empty( $atts['show_map'] ) ) ? 1 : '';

		return $clean;

	}

}