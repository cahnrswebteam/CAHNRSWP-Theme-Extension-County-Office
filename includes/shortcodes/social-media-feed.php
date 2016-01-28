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
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function item( $atts ) {
		return $this->county_social_media_feed_display( $atts, 'public' );
	}

	/**
	 * Editor markup.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function editor( $atts ) {
		return $this->county_social_media_feed_display( $atts, 'editor' );
	}

	/**
	 * Output for display and editor views.
	 *
	 * @param array  $atts Shortcode attributes/field values.
	 * @param string $view Front or back end.
	 *
	 * @return string
	 */
	public function county_social_media_feed_display( $atts, $view ) {

		$defaults = array(
			'source'        => '',
			'url'           => '',
			'fb_height'     => '600',
			'small_header'  => 'false',
			'hide_cover'    => 'false',
			'facepile'      => 'true',
			'user'          => '',
			't_height'      => '600',
			'theme'         => '',
			'replies'       => '',
			'expand_photos' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['source'] ) || ( empty( $atts['url'] )/* && empty( $atts['user'] )*/ ) ) {
			return ( 'public' === $view ) ? '' : '<p>Click to configure feed</p>';
		}

		$fb_height = ( is_numeric( $atts['fb_height'] ) ) ? esc_html( $atts['fb_height'] ) : 600;
		$t_height = ( is_numeric( $atts['t_height'] ) ) ? esc_html( $atts['t_height'] ) : 600;

		ob_start();
		?>
			<div class="county-responsive-media" style="padding-bottom:<?php echo $fb_height; ?>px;">

				<?php if ( 'facebook' == $atts['source'] ) : ?>
        <div class="fb-page" data-href="<?php echo esc_url( $atts['url'] ); ?>" data-tabs="timeline" data-width="500" data-adapt-container-width="true" data-height="<?php echo $fb_height; ?>" data-small-header="<?php echo esc_attr( $atts['small_header'] ); ?>" data-hide-cover="<?php echo esc_attr( $atts['hide_cover'] ); ?>" data-show-facepile="<?php echo esc_attr( $atts['facepile'] ); ?>"></div>
        	<?php if ( 'editor' === $view ) : ?>
        	<!--<script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js?ver=0.22.3#xfbml=1&amp;version=v2.5"></script>--><!-- probably a terrible idea -->
          <?php endif; ?>
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
	 * Pagebuilder GUI fields.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function form( $atts ) {

		$html = $this->accordion_radio(
			$this->get_name_field('source'),
			'facebook',
			$atts['source'],
			'Facebook',
			$this->facebook_options( $this->get_name_field(), $atts ),
			'A feed from Facebook.'
		);

		$html .= $this->accordion_radio(
			$this->get_name_field('source'),
			'twitter',
			$atts['source'],
			'Twitter',
			$this->twitter_options( $this->get_name_field(), $atts ),
			'A feed from Twitter.'
		);

		return $html;

	}

	/**
	 * Facebook options.
	 *
	 * @param string $base_name Field base.
	 * @param string $prefix    Attribute prefix.
	 * @param array $settings  Shortcode attributes.
	 *
	 * @return string
	 */
	public static function facebook_options( $base_name, $atts ) {

		$html  = Forms_PB::text_field( $base_name . '[url]', $atts['url'] , 'Facebook Page URL' , 'cpb-field-one-column' );
		$html .= '<p style="font-size: 1.2rem; font-weight: bold; margin: 0 0 1rem;">Display Options</p>';
		$html .= Forms_PB::text_field( $base_name . '[fb_height]', $atts['fb_height'] , 'Height (in pixels)');
		$html .= Forms_PB::checkbox_field( $base_name . '[small_header]', 'true', $atts['small_header'], 'Small Header' );
		$html .= Forms_PB::checkbox_field( $base_name . '[hide_cover]', 'true', $atts['hide_cover'], 'Hide Cover Image' );
		$html .= Forms_PB::checkbox_field( $base_name . '[facepile]', 'false', $atts['facepile'], 'Hide Friends' );

		return $html;

	}

	/**
	 * Twitter options.
	 *
	 * @param string $base_name Field base.
	 * @param string $prefix    Attribute prefix.
	 * @param array  $settings  Shortcode attributes.
	 *
	 * @return string
	 */
	public static function twitter_options( $base_name, $atts ) {

		$twitter_themes = array(
			''   => '(select)',
			'1'  => 'Option 1',
			'2'  => 'Option 2',
		);

		$html  = Forms_PB::text_field( $base_name . '[user]', $atts['user'] , 'Twitter Username' , 'cpb-field-one-column' );
		$html .= '<p style="font-size: 1.2rem; font-weight: bold; margin: 0 0 1rem;">Display Options</p>';
		$html .= Forms_PB::text_field( $base_name . '[t_height]', $atts['t_height'] , 'Height (in pixels)');
		$html .= Forms_PB::select_field( $base_name . '[theme]', $atts['theme'], $twitter_themes, 'Theme' );
		$html .= Forms_PB::checkbox_field( $base_name . '[replies]', 'true', $atts['replies'], "Show replies" );
		$html .= Forms_PB::checkbox_field( $base_name . '[expand_photos]', 'false', $atts['expand_photos'], 'Auto expand photos' );

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

		if ( ! empty( $atts['source'] ) ) {
			$clean['source'] = sanitize_text_field( $atts['source'] );
			// Facebook.
			if ( 'facebook' === $atts['source'] ) {
				if ( ! empty( $atts['url'] ) ) {
					$clean['url'] = sanitize_text_field( $atts['url'] );
				}
				$clean['fb_height'] = ( ! empty( $atts['fb_height'] ) && is_numeric( $atts['fb_height'] ) ) ? (int) sanitize_text_field( $atts['fb_height'] ) : '600';
				$clean['small_header'] = ( ! empty( $atts['small_header'] ) ) ? 'true' : '';
				$clean['hide_cover']   = ( ! empty( $atts['hide_cover'] ) ) ? 'true' : '';
				$clean['facepile']     = ( ! empty( $atts['facepile'] ) ) ? 'false' : '';
			}
			// Twitter
			if ( 'twitter' === $atts['source'] ) {
				if ( ! empty( $atts['user'] ) ) {
					$clean['user'] = sanitize_text_field( $atts['user'] );
				}
				$clean['t_height'] = ( ! empty( $atts['t_height'] ) && is_numeric( $atts['t_height'] ) ) ? (int) sanitize_text_field( $atts['t_height'] ) : '600';
				$clean['theme']         = ( ! empty( $atts['theme'] ) ) ? '' : ''; // find a default
				$clean['replies']       = ( ! empty( $atts['replies'] ) ) ? 'true' : '';
				$clean['expand_photos'] = ( ! empty( $atts['expand_photos'] ) ) ? 'false' : '';
			}
			
		}

		return $clean;

	}

}