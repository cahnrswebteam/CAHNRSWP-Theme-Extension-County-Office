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
	public $desc = 'Contact information based on the fields from the Spine and hours of operation';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'medium';

	/**
	 * Display custom markup for contact info.
	 *
	 * @param $atts    Shortcode attributes.
	 * @param $content Enclosed content.
	 *
	 * @return string
	 *
	 * @todo Maybe add an 'exclude' attribute (value="comma separated list of components").
	 */
	public function item( $atts, $content ) {

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
			<?php echo wp_kses_post( $content ); ?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Editor markup.
	 *
	 * @param $atts Shortcode attributes. 
	 *
	 * @return string
	 */
	public function editor() {

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
			<?php $empty = array( ' ', '&nbsp;' ); ?>
			<?php echo ( $this->content && ! in_array( $this->content, $empty ) ) ? $this->content : '<div class="cpb-empty">Click to add hours of operation</div>'; ?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @return string
	 */
	public function form() {

		$html = Forms_PB::wp_editor_field( $this->id, $this->content, false, 'cpb-field-one-column' );

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @return array
	 */
	public function clean() {

		$clean = array();

		return $clean;

	}

}