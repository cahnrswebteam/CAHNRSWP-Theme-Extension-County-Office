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
	 * @param array  $atts    Shortcode attributes.
	 * @param string $content Enclosed content.
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

			$map_data = array(
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
			<div style="margin-bottom: 1rem; padding-bottom: 50%; padding-bottom: calc(50% - 1rem); position:relative; width: 50%; width: calc(50% - 1rem);"><div id="county-google-map" style="height: 100%; position: absolute; width: 100%;"></div></div>
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
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function editor( $atts ) {

		$defaults = array(
			'show_map' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

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
      <?php if ( ! empty( $atts['show_map'] ) ) : ?>
			<div style="background: #e8debf; margin-top: 12px; padding-bottom: calc(50% - 1rem); position: relative; width: calc(50% - 1rem);">
      	<div style="position: absolute; margin: -40px auto 0 auto; top: 50%; right: 0; left: 0; width: 22px; height: 40px; background-repeat: no-repeat; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAoCAYAAAD6xArmAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozMjJFNkY3OUI4QkExMUU1OTU5MUYzQ0Q3N0E2Q0M0MyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozMjJFNkY3QUI4QkExMUU1OTU5MUYzQ0Q3N0E2Q0M0MyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjMyMkU2Rjc3QjhCQTExRTU5NTkxRjNDRDc3QTZDQzQzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjMyMkU2Rjc4QjhCQTExRTU5NTkxRjNDRDc3QTZDQzQzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+UDSUZAAAA/BJREFUeNqsl09ME3kUx1+HwbbSSisFhBZFqREEFZfAqmjUJWw2UdGoFzV6WA/GmGy8GC/Gg3ryIjExe9KDBxMTD7sm60GjmBA0aIysfyHprjVSa22xU2eaTrdTx/d+LiWF35Rp8Zv8yI/fe+/T3/zeb95rLbquA0/hZzfF+NDfGenFa0iNvoVsWmXrZVYb2JuXgKutBdzda8rrVvdpvHgLD/zkzHF94v4QVFU4wePyQK3DBuV2K7NlUmmIKCrEpBhMJGWo2tINHaf7LQXBoUc3qgMXrnx0YGBrY2MOZiT6kBeBN5B02MF/4tcab9fe6Azw+6d/2kZP9ae8uEt/oxeKUSAYghDuvvnccXv92p1qHvjBoT69StXA7ysOOqlRhCccImy4epMdi0B/Xv1+VtfD8ZKhpGZ8Sj0cZawcOHT9FnQ0+mCu6sC8EIskjl09/8pZXg5gs0Mmk+VHaBqMJeJsuqLSjVEi3w8ZxCKmODE80uJ1uwx38TIShssPR0BW0+x/p80KhzvbodVbx/WvRlYEmQJd/toKK9vV9BGRFOgfGM5BSTTvHxxmNl6MF1nEFNgbZfBog8E3hk9iaEMWMQU9+8UwWFLSJdmIKVrs4rfH4GiRqwJgHIxtWoabaGIK1tpqiCT5n77d52PJmi5aIxtPxCKmUNniByUl4yfBzIHndXJ9J/g9VblAmtMaywsnRpFlIKbo7u74OXBn8HaTx8PdAd2Yk+vap44rl2j+8X1QJPAjk9WKQawT9UoKvJWuOb15oZgE79122IT1gr3SDfv6jgSjsf/vIpQ4NAhijSZWfnU7tl+v/DcMTZ7Sdv1PLAaJZQ2w4dK1qepGWnxwT0MorUAWs2rBklHMoJgQvhTEmOTlwL51e8brt/fC8zgVm2xRYyQeBYolhmHPu7+rV2/IauBzOk0dwTher3dlImz5405e3xOmOy45uu/AW/kzaOrsiSQf8qUYU1165MxvujbwGFZVF07k86gE4tZOaD99cUaXFrh1YHdvWUIQQFJVQ2gMOzT5kC/Pzge37fhSt/cXCMgKXk8LdwQlGciHfE1/YZnUvW1b9VY0O2z59VpKYavCLf3014DFKFYodIY1PT/Cu1RyRtLCapLZCqkg2L2xq+YTp8HSGtlKBnu7dkfnL1uMiZpK4oeUArRGtpLBpIXrf4Cols41y7iaZWuzaVbwgram1oSKLUjX2EhgO6K12eIK3oq82/FfmuVubJ614G0wvWOSa6UfYrhTCQfNzcgUuGLV8tcynq+kZdn8u4EdK5ZuTlCj1HQ2NxNj6oxJd3u6mWPP3SGLGX/RbOuhHzTFyDTYavD1YO5g5/yiwF8FGABGcgx/yK0nXgAAAABJRU5ErkJggg==);
"></div>
      </div>
			<?php endif; ?>
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

		$html  = '<p>Information from the "Contact Information" fields from "Appearance" > "Customize" will be displayed.</p>';
		$html .= Forms_PB::checkbox_field( $this->get_name_field('show_map'), 1, $atts['show_map'], 'Include Google Map' );
		$html .= '<p>Please input office hours below.</p>';
		$html .= Forms_PB::wp_editor_field( $this->id, $this->content, false, 'cpb-field-one-column' );

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

		$clean['show_map'] = ( ! empty( $atts['show_map'] ) ) ? 1 : '';

		return $clean;

	}

}