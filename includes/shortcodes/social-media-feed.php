<?php
class Item_County_Social_Media_Feed_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_social_media_feed';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Social Media Feed';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'A feed from a selected social media channel.';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function item( $atts ) {

		$defaults = array(
			'source' => '',
			'url'    => '',
			'height' => '200'
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['source'] ) || empty( $atts['url'] ) ) {
			return '';
		}

		$height = is_numeric( $atts['height'] ) ? esc_html( $atts['height'] ) : 200;

		ob_start();
		?>
			<?php if ( 'facebook' == $atts['source'] ) : ?>
			<iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo esc_url( $atts['url'] ); ?>&amp;width&amp;height=475&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="height:<?php echo $height; ?>px;" allowtransparency="true"></iframe>
			<?php endif; ?>
			<?php /*if ( 'twitter' == $atts['source'] ) : ?>
			
			<?php endif;*/ ?>
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

		$html = 'Social Media Feed';

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

		$social_media_sources = array(
			'facebook' => 'Facebook',
			'twitter'  => 'Twitter',
			// Other options?
		);

		$html = Forms_PB::select_field( $this->get_name_field('source'), $atts['source'], $social_media_sources, 'Channel' );

		$html .= Forms_PB::text_field( $this->get_name_field('url'), $atts['url'], 'URL' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('height'), $atts['height'], 'Height (in pixels)' );

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

		if ( ! empty( $atts['source'] ) ) {
			$clean['source'] = sanitize_text_field( $atts['source'] );
		}

		if ( ! empty( $atts['url'] ) ) {
			$clean['url'] = sanitize_text_field( $atts['url'] );
		}

		if ( ! empty( $atts['height'] ) ) {
			$clean['height'] = sanitize_text_field( $atts['height'] );
		}

		return $clean;

	}

}