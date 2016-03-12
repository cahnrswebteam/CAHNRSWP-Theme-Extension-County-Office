<?php
class Item_County_Showcase_PB extends CPB_Item {

	/**
	 * @var string Shortcode tag.
	 */
	protected $slug = 'county_showcase';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	protected $name = 'Showcase';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	protected $desc = "A triptych of featured content for your site's homepage";

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	protected $form_size = 'medium';

	/**
	 * Construct.

	protected function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
	} */

	/**
	 * Enqueue scripts and styles for the landing page showcase.

	protected function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && is_front_page() && has_shortcode( $post->post_content, 'county_showcase' ) ) {
			wp_enqueue_style( 'cahnrswp-extension-county-showcase', get_stylesheet_directory_uri() . '/css/showcase.css' );
			//wp_enqueue_script( 'cahnrswp-extension-county-shocase', get_stylesheet_directory_uri() . '/js/showcase.js', array( 'jquery' ) );
		}
	} */

	/**
	 * Display markup.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	protected function item( $settings , $content ) {

		$defaults = array(
			'feature_source'         => '',
			'feature_post_type'      => '',
			'feature_taxonomy'       => '',
			'feature_terms'          => '',
			'items'                  => '',
			'feature_feed_url'       => '',
			'feature_feed_post_type' => '',
			'feature_feed_taxonomy'  => '',
			'feature_feed_term'     => '',
			'second_feed_url'        => '',
			'second_feed_post_type'  => '',
			'second_feed_taxonomy'   => '',
			'second_feed_term'      => '',
			'third_feed_url'         => '',
			'third_feed_post_type'   => '',
			'third_feed_taxonomy'    => '',
			'third_feed_term'       => '',
		);

		$atts = shortcode_atts( $defaults, $settings );

		if ( ! is_front_page() && ( empty( $atts['feature_source'] ) || empty( $atts['second_feed_url'] ) || empty( $atts['third_feed_url'] ) ) ) {
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
								// Only show manuals slides that have an image.
								if ( $item['img'] ) {
									$excerpt = '<p>' . $item['excerpt'] . '</p>';
									wsu_extension_county_feature_item( $item['img']['img_src'], $item['link'], $item['title'], $excerpt, false );
								}
							}
						}
					} elseif ( 'remote_feed' === $atts['feature_source'] ) {
						$this->remote_query( $atts['feature_feed_url'], $atts['feature_feed_post_type'], $atts['feature_feed_taxonomy'], $atts['feature_feed_term'], true );
					}
				?>
			</div>
			<?php endif; ?>

			<?php if ( ! wp_is_mobile() ) : ?>
			<div class="syndicated">

				<?php
					if ( $atts['second_feed_url'] ) {
						$this->remote_query( $atts['second_feed_url'], $atts['second_feed_post_type'], $atts['second_feed_taxonomy'], $atts['second_feed_term'], false );
					}
				?>

				<?php
					if ( $atts['second_feed_url'] ) {
						$this->remote_query( $atts['third_feed_url'], $atts['third_feed_post_type'], $atts['third_feed_taxonomy'], $atts['third_feed_term'], false );
					}
				?>

			</div>
			<?php endif; ?>

		</section>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Retrieve syndicated content.
	 *
	 * @param string $url       Site from which to request the JSON.
	 * @param string $post_type Type of content to retrieve.
	 * @param string $taxonomy  Taxonomy to filter by.
	 * @param string $term      Taxonomy term to filter by.
	 * @param bool   $exerpt    Include the excerpt
	 *
	 * @return string
	 */
	protected function remote_query( $url, $post_type, $taxonomy, $term, $excerpt ) {

		$request_url = esc_url( $url . '/wp-json/posts/' );
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

				$image = $post->featured_image->attachment_meta->sizes->medium->url; // API v2: to come...
				$link = esc_html( $post->link );
				$title = esc_html( $post->title ); // API v2: $post->title->rendered
				$excerpt = ( $excerpt ) ? $post->excerpt : '';

				wsu_extension_county_feature_item( $image, $link, $title, $excerpt, true ); // Function at /includes/feature-item.php.

			}

		}

	}

	/**
	 * Editor markup.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	//protected function editor( $atts ) {

		/* somehow flawed
		if ( get_the_ID() != get_option( 'page_on_front' ) ) {
			return '<p>The showcase will only display on the home page.</p>';
		}*/

		/*$defaults = array(
			'feature_source'  => '',
			'second_feed_url' => '',
			'third_feed_url'  => '',
		);

		$atts = shortcode_atts( $defaults, $atts );
		
		if ( empty( $atts['feature_source'] ) || empty( $atts['second_feed_url'] ) || empty( $atts['third_feed_url'] ) ) {
			return '<p>Click to configure features</p>';
		}
		// Just a placeholder for now.
		ob_start();
		?>
		<section class="landing-page-showcase">
			<div class="featured">
				<article class="county-responsive-media" style="background-image:url(http://m1.wpdev.cahnrs.wsu.edu/county/wp-content/uploads/sites/21/2015/12/growing-groceries.jpg);">
					<header class="article-header">
						<h2 class="article-title">Growing Groceries</h2>
					</header>
					<div class="article-summary">
						<p>Workshops February 12 - March 18, 2016</p>
					</div>
				</article>
			</div>
			<div class="syndicated">
				<article class="county-responsive-media" style="background-image:url(http://cahnrs.wsu.edu/wp-content/uploads/2015/12/Davenport-Snow-Fence-crop-792x422.jpg);">
					<header class="article-header">
						<h2 class="article-title">Living snow fence thrives, surprises in Washington’s drylands</h2>
					</header>
				</article>
				<article class="county-responsive-media" style="background-image:url(http://cahnrs.wsu.edu/wp-content/uploads/2016/01/PRD-in-grape-vines-792x445.jpeg);">
					<header class="article-header">
						<h2 class="article-title">Research helps growers conserve water, improve white wines</h2>
					</header>
				</article>
			</div>
		</section>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}*/

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	protected function form( $settings , $content ) {
		
		$feature_feed = array(
			'name'    => $this->get_input_name( 'feature_source' ),
			'value'   => 'feed',
			'selected' => $settings['feature_source'],
			'title'   => 'Feed',
			'desc'    => 'Most recent post or page, optionally based on categories or tags.',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
			
		$feature_manual = array(
			'name'    => $this->get_input_name( 'feature_source' ),
			'value'   => 'manual',
			'selected' => $settings['feature_source'],
			'title'   => 'Manual',
			'desc'    => 'Most recent post or page, optionally based on categories or tags.',
			'form'    => $this->form_fields->get_manual_feature( $this->get_input_name() , $settings ), 
			);
		
		$remote_feed = array(
			'name'    => $this->get_input_name( 'feature_source' ),
			'value'   => 'remote_feed',
			'selected' => $settings['feature_source'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Content from another site.',
			'form'    => $this->syndicated_content( $this->get_input_name(), 'feature_feed', $settings ),
			);
		
		$feature_html = $this->form_fields->multi_form( array( $feature_feed , $feature_manual , $remote_feed ) );
		
		$second_feature = '<p>Content for the top right feature.</p>';
		$second_feature .= $this->syndicated_content( $this->get_input_name(), 'second_feed', $settings );
		
		$third_feature = '<p>Content for the bottom right feature.</p>';
		$third_feature .= $this->syndicated_content( $this->get_input_name(), 'third_feed', $settings );
		
		
		
		
		
		
		
		
		
		

		/*$feature = $this->accordion_radio(
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
			'remote_feed',
			$atts['feature_source'],
			'Feed (Another Site)',
			$this->syndicated_content( $this->get_name_field(), 'feature_feed', $atts ),
			'Content from another site.'
		);*/

		/*$second_feature = '<p>Content for the top right feature.</p>';
		$second_feature .= $this->syndicated_content( $this->get_name_field(), 'second_feed', $atts );

		$third_feature = '<p>Content for the bottom right feature.</p>';
		$third_feature .= $this->syndicated_content( $this->get_name_field(), 'third_feed', $atts );*/

		$html = array(
			'Main'         => $feature_html,
			'Top Right'    => $second_feature,
			'Bottom Right' => $third_feature,
		);

		return $html;

	}

	/**
	 * Syndicated features pagebuilder GUI.
	 *
	 * @param string $base_name Field base.
	 * @param string $prefix    Attribute prefix.
	 * @param array  $atts      Shortcode attributes.
	 *
	 * @return string
	 *
	 * @todo Probably offer up a select field with a limited number of sites instead of a text input field for URL.
	 */
	protected function syndicated_content( $base_name, $prefix, $atts ) {

		$html  = $this->form_fields->text_field( $base_name . '[' . $prefix . '_url]', $atts[ $prefix . '_url'], 'Site URL (Homepage)', 'cpb-field-one-column' );
		$html .= $this->form_fields->text_field( $base_name . '[' . $prefix . '_post_type]', $atts[ $prefix . '_post_type'], 'Post Type (slug)');
		$html .= $this->form_fields->text_field( $base_name . '[' . $prefix . '_taxonomy]', $atts[ $prefix . '_taxonomy'], 'Feed By (slug)');
		$html .= $this->form_fields->text_field( $base_name . '[' . $prefix . '_term]', $atts[ $prefix . '_term'], 'Term (Name)');

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return array
	 */
	protected function clean( $atts ) {
		
		//var_dump( $atts );

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
		
		if ( ! empty( $atts['items'] ) ){
			
			$clean['items'] = $atts['items'];
			
		} // end if

		/*if ( ! empty( $atts['items'] ) ) {
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
		}*/

		if ( ! empty( $atts['feature_feed_url'] ) ) {
			$clean['feature_feed_url'] = sanitize_text_field( $atts['feature_feed_url'] );
		}

		if ( ! empty( $atts['feature_feed_post_type'] ) ) {
			$clean['feature_feed_post_type'] = sanitize_text_field( $atts['feature_feed_post_type'] );
		}

		if ( ! empty( $atts['feature_feed_taxonomy'] ) ) {
			$clean['feature_feed_taxonomy'] = sanitize_text_field( $atts['feature_feed_taxonomy'] );
		}

		if ( ! empty( $atts['feature_feed_term'] ) ) {
			$clean['feature_feed_term'] = sanitize_text_field( $atts['feature_feed_term'] );
		}

		if ( ! empty( $atts['second_feed_url'] ) ) {
			$clean['second_feed_url'] = sanitize_text_field( $atts['second_feed_url'] );
		}

		if ( ! empty( $atts['second_feed_post_type'] ) ) {
			$clean['second_feed_post_type'] = sanitize_text_field( $atts['second_feed_post_type'] );
		}

		if ( ! empty( $atts['second_feed_taxonomy'] ) ) {
			$clean['second_feed_taxonomy'] = sanitize_text_field( $atts['second_feed_taxonomy'] );
		}

		if ( ! empty( $atts['second_feed_term'] ) ) {
			$clean['second_feed_term'] = sanitize_text_field( $atts['second_feed_term'] );
		}

		if ( ! empty( $atts['third_feed_url'] ) ) {
			$clean['third_feed_url'] = sanitize_text_field( $atts['third_feed_url'] );
		}

		if ( ! empty( $atts['third_feed_post_type'] ) ) {
			$clean['third_feed_post_type'] = sanitize_text_field( $atts['third_feed_post_type'] );
		}

		if ( ! empty( $atts['third_feed_taxonomy'] ) ) {
			$clean['third_feed_taxonomy'] = sanitize_text_field( $atts['third_feed_taxonomy'] );
		}

		if ( ! empty( $atts['third_feed_term'] ) ) {
			$clean['third_feed_term'] = sanitize_text_field( $atts['third_feed_term'] );
		}

		return $clean;

	}

}