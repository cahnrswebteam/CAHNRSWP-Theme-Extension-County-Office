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
			'height' => '70'
			// Could add many attributes for different embed settings (e.g. facebook data-small-header)
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['source'] ) || empty( $atts['url'] ) ) {
			return '';
		}

		$height = is_numeric( $atts['height'] ) ? esc_html( $atts['height'] ) : 70;

		ob_start();
		?>
			<div class="county-responsive-media" style="padding-bottom:<?php echo $height; ?>px;">

				<?php if ( 'facebook' == $atts['source'] ) : ?>
        <div class="fb-page" data-href="<?php echo esc_url( $atts['url'] ); ?>" data-tabs="timeline" data-width="500" data-adapt-container-width="true" data-height="<?php echo $height; ?>" data-small-header="false" data-hide-cover="false" data-show-facepile="true"></div>
				<?php endif; ?>

				<?php /*if ( 'twitter' == $atts['source'] ) : ?>
				
				<?php endif;*/ ?>

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
	public function editor( $atts ) {

		/*$defaults = array(
			'source' => '',
			'url'    => '',
			'height' => '70'
		);

		$atts = shortcode_atts( $defaults, $atts );

		$height = is_numeric( $atts['height'] ) ? esc_html( $atts['height'] ) : 70;

		ob_start();
		?>
			<div class="county-social-media-embed">

				<?php if ( 'facebook' == $atts['source'] ) : ?>
        <div class="fb-page" data-href="<?php echo esc_url( $atts['url'] ); ?>" data-tabs="timeline" data-width="500" data-adapt-container-width="true" data-height="<?php echo $height; ?>" data-small-header="false" data-hide-cover="false" data-show-facepile="true"></div>
        <script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js?ver=0.22.3#xfbml=1&amp;version=v2.5"></script><!-- perhaps not the best idea -->
				<?php endif; ?>

				<?php if ( 'twitter' == $atts['source'] ) : ?>
				
				<?php endif; ?>

			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();*/

		$html = ( $atts['source'] ) ? $atts['source'] : '<p>Add feed</p>';

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
			''         => '(select)',
			'facebook' => 'Facebook',
			'twitter'  => 'Twitter',
			// Other options?
		);

		$html = Forms_PB::select_field( $this->get_name_field('source'), $atts['source'], $social_media_sources, 'Channel' );

		$html .= Forms_PB::text_field( $this->get_name_field('url'), $atts['url'], 'URL' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('height'), $atts['height'], 'Height (in pixels)' );

		/*$html = $this->accordion_radio(
			$this->get_name_field('source'),
			'facebook',
			$atts['source'],
			'Facebook',
			$this->feed_options( $this->get_name_field(), 'fb', $atts ),
			'A feed from Facebook.'
		);

		$html .= $this->accordion_radio(
			$this->get_name_field('source'),
			'twitter',
			$atts['source'],
			'Twitter',
			$this->feed_options( $this->get_name_field(), 'tw', $atts ),
			'A feed from Twitter.'
		);*/

		return $html;

	}

	/**
	 * Feed options.
	 *
	 * @param $base_name Field base.
	 * @param $prefix    Attribute prefix.
	 * @param $settings  Shortcode attributes.
	 *
	 * @return string
	 */
	public static function facebook_options( $base_name, $prefix, $atts ) {

		$html = Forms_PB::text_field( $base_name . '[' . $prefix . '_url]', $atts[ $prefix . '_url'] , 'Site URL (Homepage)' , 'cpb-field-one-column' );

		$html .= Forms_PB::text_field( $base_name . '[' . $prefix . '_height]', $atts[ $prefix . '_height'] , 'Height (in pixels)');

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