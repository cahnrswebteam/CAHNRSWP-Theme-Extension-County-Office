<?php
class Item_County_Showcase_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_showcase';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Showcase';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = "A triptych of featured content for your site's homepage";

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'medium';

	/**
	 * Construct.

	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
	} */

	/**
	 * Enqueue scripts and styles for the landing page showcase.

	public function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && is_front_page() && has_shortcode( $post->post_content, 'county_showcase' ) ) {
			wp_enqueue_style( 'cahnrswp-extension-county-showcase', get_stylesheet_directory_uri() . '/css/showcase.css' );
			//wp_enqueue_script( 'cahnrswp-extension-county-shocase', get_stylesheet_directory_uri() . '/js/showcase.js', array( 'jquery' ) );
		}
	} */

	/**
	 * Display markup.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function item( $atts ) {

		$defaults = array(
			'feature_source'          => '',
			'feature_post_type'       => '',
			'feature_taxonomy'        => '',
			'feature_terms'           => '',
			'items'                   => '',
			'second_source'           => '',
			'second_source_url'       => '',
			'second_source_post_type' => '',
			'second_source_taxonomy'  => '',
			'second_source_terms'     => '',
			'third_source'            => '',
			'third_source_url'        => '',
			'third_source_post_type'  => '',
			'third_source_taxonomy'   => '',
			'third_source_terms'      => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( ! is_front_page() ) {
			return '';
		}

		ob_start();
		?>
		<section class="landing-page-showcase">

			<?php if ( $atts['feature_source'] ) : ?>
			<div class="featured">
				<?php
					if ( 'feed' === $atts['feature_source'] ) {
						$county_feature_query_args = array(
							'posts_per_page' => 1,
						);
						if ( $atts['feature_post_type'] ) {
							$county_feature_query_args['post_type'] = $atts['feature_post_type'];
						}
						if ( 'category' === $atts['feature_taxonomy'] ) {
							$county_feature_query_args['category_name'] = $atts['feature_terms'];
						} elseif ( 'tag' === $atts['feature_taxonomy'] ) {
							$county_feature_query_args['tag'] = $atts['feature_terms'];
						}
						$county_feature_query = new WP_Query( $county_feature_query_args );
						if ( $county_feature_query->have_posts() ) :
							while ( $county_feature_query->have_posts() ) : $county_feature_query->the_post();
								// Only show posts that have a featured image.
								if ( has_post_thumbnail() ) {
									$image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
									$excerpt = '<p>' . get_the_excerpt() . '</p>';
									wsu_extension_county_feature_item( $image, get_the_permalink(), get_the_title(), $excerpt, false );
								}
							endwhile;
						endif;
						wp_reset_postdata();
					} elseif ( 'manual' === $atts['feature_source'] ) {
						$items = json_decode( $atts['items'], true );
						if ( is_array( $items ) ) {
							foreach ( $items as $item ) {
								if ( $item['img'] ) {
									$excerpt = '<p>' . $item['excerpt'] . '</p>';
									wsu_extension_county_feature_item( $item['img']['img_src'], $item['link'], $item['title'], $excerpt, false );
								}
							}
						}
					}
				?>
			</div>
			<?php endif; ?>

			<div class="syndicated">

				<?php
					if ( $atts['second_source_url'] ) {
						$this->remote_query( $atts['second_source_url'], $atts['second_source_post_type'], $atts['second_source_taxonomy'], $atts['second_source_terms'] );
					}
				?>

				<?php
					if ( $atts['second_source_url'] ) {
						$this->remote_query( $atts['third_source_url'], $atts['third_source_post_type'], $atts['third_source_taxonomy'], $atts['third_source_terms'] );
					}
				?>

			</div>

		</section>
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

		$html = 'Showcase';

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

		$feature = $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'feed',
			$atts['feature_source'],
			'Feed',
			Forms_PB::local_feed( $this->get_name_field(), $atts ),
			'Most recent post or page, optionally based on categories or tags.'
		);

		$feature .= $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'manual',
			$atts['feature_source'],
			'Manual',
			Forms_PB::manual_feature( $this->get_name_field(), $atts ),
			'Build your own feature.'
		);

		$feature .= $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'remote_feed' , 
			$atts['feature_source'] , 
			'Feed (Another Site)' , 
			Forms_PB::remote_feed( $this->get_name_field() , $atts ),
			'Content from another site.' 
		);

		$second_feature = '<p>Content for the top right feature.</p>';
		$second_feature .= $this->syndicated_content( $this->get_name_field(), 'second_source', $atts );

		$third_feature = '<p>Content for the bottom right feature.</p>';
		$third_feature .= $this->syndicated_content( $this->get_name_field(), 'third_source', $atts );

		$html = array(
			'Main'         => $feature,
			'Top Right'    => $second_feature,
			'Bottom Right' => $third_feature,
		);

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

		if ( ! empty( $atts['feature_source'] ) ) {
			$clean['feature_source'] = sanitize_text_field( $atts['feature_source'] );
		}

		if ( ! empty( $atts['post_type'] ) ) {
			$clean['post_type'] = sanitize_text_field( $atts['post_type'] );
		}

		if ( ! empty( $atts['taxonomy'] ) ) {
			$clean['taxonomy'] = sanitize_text_field( $atts['taxonomy'] );
		}

		if ( ! empty( $atts['terms'] ) ) {
			$clean['terms'] = sanitize_text_field( $atts['terms'] );
		}

		if ( ! empty( $atts['items'] ) ) {
			if ( is_array( $atts['items'] ) ) {
				$items = '{';
				$index = 0;
				foreach ( $atts['items'] as $item ) {
					$items .= "'" . $index++ . "':{";
					if ( ! empty( $item['img']['img_src'] ) ) {
						$items .= "'img':{'img_src':'" . sanitize_text_field( $item['img']['img_src'] ) . "','img_id':'" . sanitize_text_field( $item['img']['img_id'] ) . "'},";
					}
					if ( ! empty( $item['link'] ) ) {
						$items .= "'link':'" . sanitize_text_field( $item['link'] ) . "',";
					}
					if ( ! empty( $item['title'] ) ) {
						$items .= "'title':'" . sanitize_text_field( $item['title'] ) . "',";
					}
					if ( ! empty( $item['excerpt'] ) ) {
						$items .= "'excerpt':'" . sanitize_text_field( $item['excerpt'] ) . "'";
					}
					$items = rtrim( $items, ',' );
					$items .= '},';
				}
				$items = rtrim( $items, ',' );
				$items .= '}';
				$clean['items'] = sanitize_text_field( $items );
			} else {
				$clean['items'] = str_replace( "'", '"', $atts['items'] );
			}
		}

		if ( ! empty( $atts['second_source_url'] ) ) {
			$clean['second_source_url'] = sanitize_text_field( $atts['second_source_url'] );
		}

		if ( ! empty( $atts['second_source_post_type'] ) ) {
			$clean['second_source_post_type'] = sanitize_text_field( $atts['second_source_post_type'] );
		}

		if ( ! empty( $atts['second_source_taxonomy'] ) ) {
			$clean['second_source_taxonomy'] = sanitize_text_field( $atts['second_source_taxonomy'] );
		}

		if ( ! empty( $atts['second_source_terms'] ) ) {
			$clean['second_source_terms'] = sanitize_text_field( $atts['second_source_terms'] );
		}

		if ( ! empty( $atts['third_source_url'] ) ) {
			$clean['third_source_url'] = sanitize_text_field( $atts['third_source_url'] );
		}

		if ( ! empty( $atts['third_source_post_type'] ) ) {
			$clean['third_source_post_type'] = sanitize_text_field( $atts['third_source_post_type'] );
		}

		if ( ! empty( $atts['third_source_taxonomy'] ) ) {
			$clean['third_source_taxonomy'] = sanitize_text_field( $atts['third_source_taxonomy'] );
		}

		if ( ! empty( $atts['third_source_terms'] ) ) {
			$clean['third_source_terms'] = sanitize_text_field( $atts['third_source_terms'] );
		}

		return $clean;

	}

	/**
	 * Remote Feed pagebuilder GUI.
	 *
	 * @param $base_name Field base.
	 * @param $prefix    Attribute prefix.
	 * @param $settings  Shortcode attributes.
	 *
	 * @return string
	 *
	 * @todo Probably offer up a select field with a limited number of sites instead of a text input field for URL.
	 */
	public static function syndicated_content( $base_name, $prefix, $atts ) {

		$html = Forms_PB::text_field( $base_name . '[' . $prefix . '_url]', $atts[ $prefix . '_url'] , 'Site URL (Homepage)' , 'cpb-field-one-column' );

		$html .= Forms_PB::text_field( $base_name . '[' . $prefix . '_post_type]', $atts[ $prefix . '_post_type'] , 'Post Type (slug)');

		$html .= Forms_PB::text_field( $base_name . '[' . $prefix . '_taxonomy]', $atts[ $prefix . '_taxonomy'] , 'Feed By (slug)');

		$html .= Forms_PB::text_field( $base_name . '[' . $prefix . '_terms]', $atts[ $prefix . '_terms'] , 'Terms (Name)');

		return $html;

	}

	/**
	 * Retrieve syndicated content.
	 *
	 * @param string $url       Site from which to request the JSON.
	 * @param string $post_type Type of content to retrieve.
	 * @param string $taxonomy  Taxonomy to filter by.
	 * @param string $term      Taxonomy term to filter by.
	 *
	 * @return string
	 */
	public function remote_query( $url, $post_type, $taxonomy, $term ) {

		$request_url = esc_url( $url . 'wp-json/posts/' );
		//$request_url = esc_url( $url . 'wp-json/wp/v2/' . sanitize_key( $post_type ) ); // What the API v2 url might look like.

		$request_url = add_query_arg( 'filter[posts_per_page]', 1, $request_url );

		if ( $post_type ) {
			$request_url = add_query_arg( 'type', sanitize_key( $post_type ), $request_url );
		}

		if ( $taxonomy && $term ) {
			$request_url = add_query_arg(
				array(
					'filter[taxonomy]' => sanitize_key( $taxonomy ),
					'filter[term]' => sanitize_key( $term ),
				),
				$request_url
			);
		}

		$response = wp_remote_get( $request_url );

		$data = wp_remote_retrieve_body( $response );

		if ( ! empty( $data ) ) {

			$posts = json_decode( $data );

			foreach( $posts as $post ) {

				$image = $post->featured_image->attachment_meta->sizes->{'spine-medium_size'}->url; // API v2: to come...
				$link = esc_html( $post->link );
				$title = esc_html( $post->title ); // API v2: $post->title->rendered

				wsu_extension_county_feature_item( $image, $link, $title, '', true );

			}

		}

	}

}