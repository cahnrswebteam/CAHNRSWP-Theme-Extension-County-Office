<?php
class County_Extension_Contact {

	public function __construct() {
		add_shortcode( 'county_contact', array( $this, 'display_county_contact' ) );
	}

	/**
	 * Display custom markup used for slideshows.
	 *
	 * @param $atts
	 *
	 * @return string
	 *
	 * @todo Maybe add an 'exclude' attribute (value="comma separated list of components").
	 */
	public function display_county_contact( $atts, $content = null ) {

		/*$defaults = array(
			'' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts[''] ) ) {
			return '';
		}*/
		
		ob_start();
		?>
		<div class="county-contact">
			<p class="unit-name"><?php echo esc_html( spine_get_option( 'contact_department' ) ); ?></p>
			<p class="unit-address"><?php echo esc_html( spine_get_option( 'contact_streetAddress' ) ); ?></p>
			<p class="unit-locality"><?php echo esc_html( spine_get_option( 'contact_addressLocality' ) ); ?></p>
			<p class="unit-zip"><?php echo esc_html( spine_get_option( 'contact_postalCode' ) ); ?></p>
			<p class="unit-telephone"><?php echo esc_html( spine_get_option( 'contact_telephone' ) ); ?></p>
			<p class="unit-email"><a href="mailto:<?php echo esc_attr( spine_get_option( 'contact_email' ) ); ?>"><?php echo esc_html( spine_get_option( 'contact_email' ) ); ?></a></p>
			<?php $contact_point = spine_get_option( 'contact_ContactPoint' ); ?>
			<?php if ( ! empty( $contact_point ) ) : ?>
      <p class="unit-contact"><a href="<?php echo esc_url( $contact_point ); ?>"><?php echo esc_html( spine_get_option( 'contact_ContactPointTitle' ) ); ?></a></p>
			<?php endif; ?>
      <p class="unit-hours"><?php echo wp_kses_post( $content ); ?></p>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

new County_Extension_Contact();