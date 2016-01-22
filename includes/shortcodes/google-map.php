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
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 *
	 * @todo Some kind of indexing on container and map_data object?
	 */
	public function item( $atts ) {

		$defaults = array(
			'address' => '',
			'coords'  => '',
			'title'   => '',
			'desc'    => '',
			//'zoom'    => 10
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['address'] ) && empty( $atts['coords'] ) ) {
			return '';
		}

		wp_enqueue_script( 'google_maps_api', '//maps.googleapis.com/maps/api/js', array(), false, true );
		wp_enqueue_script( 'google-map-embed', get_stylesheet_directory_uri() . '/js/google-map-embed.js', array( 'google_maps_api' ), false, true );

		$map_data = array( // check sanitizing
			'address' => esc_html( $atts['address'] ),
			'title'   => esc_html( $atts['title'] ),
			'desc'    => wpautop( wp_kses_post( $atts['desc'] ) ),
			//'zoom'    => esc_html( $atts['zoom'] ),
		);

		if ( ! empty( $atts['coords'] ) ) {
			$map_data['coords'] = esc_html( $atts['coords'] );
		}

		wp_localize_script( 'google-map-embed', 'map_data', $map_data );

		ob_start();
		?>
		<div style="margin-bottom: 2rem; padding-bottom: 100%; position:relative; width: 100%;"><div id="county-google-map" style="height: 100%; position: absolute; width: 100%;"></div></div>
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
			'address' => '',
			'coords'  => '',
			'title'   => '',
			'desc'    => '',
			//'zoom'    => 10
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['address'] ) && empty( $atts['coords'] ) ) {
			return '<p>Click to configure map</p>';
		}

		ob_start();
		?>
		<div style="background: #e8debf; margin-top: 12px; padding-bottom: 100%; position: relative; width: 100%;">
      	<div style="position: absolute; margin: -40px auto 0 auto; top: 50%; right: 0; left: 0; width: 22px; height: 40px; background-repeat: no-repeat; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAoCAYAAAD6xArmAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozMjJFNkY3OUI4QkExMUU1OTU5MUYzQ0Q3N0E2Q0M0MyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozMjJFNkY3QUI4QkExMUU1OTU5MUYzQ0Q3N0E2Q0M0MyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjMyMkU2Rjc3QjhCQTExRTU5NTkxRjNDRDc3QTZDQzQzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjMyMkU2Rjc4QjhCQTExRTU5NTkxRjNDRDc3QTZDQzQzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+UDSUZAAAA/BJREFUeNqsl09ME3kUx1+HwbbSSisFhBZFqREEFZfAqmjUJWw2UdGoFzV6WA/GmGy8GC/Gg3ryIjExe9KDBxMTD7sm60GjmBA0aIysfyHprjVSa22xU2eaTrdTx/d+LiWF35Rp8Zv8yI/fe+/T3/zeb95rLbquA0/hZzfF+NDfGenFa0iNvoVsWmXrZVYb2JuXgKutBdzda8rrVvdpvHgLD/zkzHF94v4QVFU4wePyQK3DBuV2K7NlUmmIKCrEpBhMJGWo2tINHaf7LQXBoUc3qgMXrnx0YGBrY2MOZiT6kBeBN5B02MF/4tcab9fe6Azw+6d/2kZP9ae8uEt/oxeKUSAYghDuvvnccXv92p1qHvjBoT69StXA7ysOOqlRhCccImy4epMdi0B/Xv1+VtfD8ZKhpGZ8Sj0cZawcOHT9FnQ0+mCu6sC8EIskjl09/8pZXg5gs0Mmk+VHaBqMJeJsuqLSjVEi3w8ZxCKmODE80uJ1uwx38TIShssPR0BW0+x/p80KhzvbodVbx/WvRlYEmQJd/toKK9vV9BGRFOgfGM5BSTTvHxxmNl6MF1nEFNgbZfBog8E3hk9iaEMWMQU9+8UwWFLSJdmIKVrs4rfH4GiRqwJgHIxtWoabaGIK1tpqiCT5n77d52PJmi5aIxtPxCKmUNniByUl4yfBzIHndXJ9J/g9VblAmtMaywsnRpFlIKbo7u74OXBn8HaTx8PdAd2Yk+vap44rl2j+8X1QJPAjk9WKQawT9UoKvJWuOb15oZgE79122IT1gr3SDfv6jgSjsf/vIpQ4NAhijSZWfnU7tl+v/DcMTZ7Sdv1PLAaJZQ2w4dK1qepGWnxwT0MorUAWs2rBklHMoJgQvhTEmOTlwL51e8brt/fC8zgVm2xRYyQeBYolhmHPu7+rV2/IauBzOk0dwTher3dlImz5405e3xOmOy45uu/AW/kzaOrsiSQf8qUYU1165MxvujbwGFZVF07k86gE4tZOaD99cUaXFrh1YHdvWUIQQFJVQ2gMOzT5kC/Pzge37fhSt/cXCMgKXk8LdwQlGciHfE1/YZnUvW1b9VY0O2z59VpKYavCLf3014DFKFYodIY1PT/Cu1RyRtLCapLZCqkg2L2xq+YTp8HSGtlKBnu7dkfnL1uMiZpK4oeUArRGtpLBpIXrf4Cols41y7iaZWuzaVbwgram1oSKLUjX2EhgO6K12eIK3oq82/FfmuVubJ614G0wvWOSa6UfYrhTCQfNzcgUuGLV8tcynq+kZdn8u4EdK5ZuTlCj1HQ2NxNj6oxJd3u6mWPP3SGLGX/RbOuhHzTFyDTYavD1YO5g5/yiwF8FGABGcgx/yK0nXgAAAABJRU5ErkJggg==);
"></div>
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

		$html  = Forms_PB::text_field( $this->get_name_field('address'), $atts['address'], 'Address', 'cpb-field-one-column' );
		// or lat and long
		$html .= Forms_PB::text_field( $this->get_name_field('title'), $atts['title'], 'Marker Title' );
		$html .= Forms_PB::text_field( $this->get_name_field('desc'), $atts['desc'], 'Marker Description' );
		//$html .= Forms_PB::wp_editor_field( $this->id, $this->content, false, 'cpb-field-one-column' ); for description?
		//$html .= Forms_PB::select_field( $this->get_name_field('zoom'), $atts['zoom'], array_combine( range( 1, 21 ), range( 1, 21 ) ), 'Map zoom level<br />(1 = furthest, 21 = closest)' );

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

		$clean['address'] = ( ! empty( $atts['address'] ) ) ? sanitize_text_field( $atts['address'] ) : '';
		$clean['title'] = ( ! empty( $atts['title'] ) ) ? sanitize_text_field( $atts['title'] ) : '';
		$clean['desc'] = ( ! empty( $atts['desc'] ) ) ? sanitize_text_field( $atts['desc'] ) : '';
		//$clean['zoom'] = ( ! empty( $atts['zoom'] ) ) ? (int) sanitize_text_field( $atts['zoom'] ) : '';

		return $clean;

	}

}