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
			'feature_source'    => '',
			'feature_post_type' => '',
			'feature_taxonomy'  => '',
			'feature_terms'     => '',
			'feature_items'     => '',
			'second_source'     => '',
			'third_source'      => '',
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
				<?php // maybe a slideshow... maybe not.
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
									$permalink = get_the_permalink();
									$title = get_the_title();
									$excerpt = '<p>' . get_the_excerpt() . '</p>';
									include( __DIR__ . '/feature-item.php' );
									unset( $image, $permalink, $title, $excerpt );
								}
							endwhile;
						endif;
						wp_reset_postdata();
					} elseif ( 'manual' === $atts['feature_source'] ) {
						$items = json_decode( $atts['items'], true );
						if ( is_array( $items ) ) {
							foreach ( $items as $item ) {
								if ( $item['img'] ) {
									$image = $item['img'];
									$permalink = $item['link'];
									$title = $item['title'];
									$excerpt = '<p>' . $item['excerpt'] . '</p>';
									include( __DIR__ . '/feature-item.php' );
									unset( $image, $permalink, $title, $excerpt );
								}
							}
						}
					}
				?>
			</div>
			<?php endif; ?>

			<div class="syndicated">

				<?php
					// Probably use WP API for this. RSS would be viable too, I suppose.
					$syndicated_image_src = 'http://m1.wpdev.cahnrs.wsu.edu/extension-property/wp-content/uploads/sites/18/2015/05/palouse-night-1188x891.jpg';
					$syndicated_permalink = '#';
					$syndicated_title = 'Syndicated Content';
					$image = $syndicated_image_src;
					$permalink = $syndicated_permalink;
					$title = $syndicated_title;
					include( __DIR__ . '/feature-item.php' );
					unset( $image, $permalink, $title );
				?>

				<?php
					$additional_syndicated_image_src = 'http://m1.wpdev.cahnrs.wsu.edu/cahnrs-property/wp-content/uploads/sites/19/2015/06/palouse-1188x891.jpg';
					$additional_syndicated_permalink = '#';
					$additional_syndicated_title = 'Syndicated Contentoo';
					$image = $additional_syndicated_image_src;
					$permalink = $additional_syndicated_permalink;
					$title = $additional_syndicated_title;
					include( __DIR__ . '/feature-item.php' );
					unset( $image, $permalink, $title );
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

		$feature .= $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'feed',
			$atts['feature_source'],
			'Feed',
			Forms_PB::local_feed( $this->get_name_field(), $atts ),
			'Most recent post or page, optionally based on categories or tags.'
		);

		$feature .= $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'cherry',
			$atts['feature_source'],
			'Cherry Pick',
			'',//Forms_PB::local_feed( $this->get_name_field(), $atts ),
			'Select an individual post or page.'
		);

		$feature .= $this->accordion_radio(
			$this->get_name_field('feature_source'),
			'manual',
			$atts['feature_source'],
			'Manual',
			//Forms_PB::manual_feature( $this->get_name_field(), $atts ),
			Forms_PB::insert_media( $this->get_name_field(), $atts ),
			'Build your own feature by setting the image, URL, title, and additional text.'
		);

		$second_feature = $this->accordion_radio(
			$this->get_name_field( 'second_source' ),
			'remote_feed',
			$atts['second_source'],
			'Feed (Another Site)',
			Forms_PB::remote_feed( $this->get_name_field(), $atts ),
			'Most recent posts from another site.'
		);

		$third_feature = $this->accordion_radio(
			$this->get_name_field( 'third_source' ),
			'remote_feed',
			$atts['third_source'],
			'Feed (Another Site)',
			Forms_PB::remote_feed( $this->get_name_field(), $atts ),
			'Most recent posts from another site.'
		);

		$html = array(
			'Main Feature'   => $feature,
			//'Second Feature' => $second_feature,
			//'Third Feature'  => $third_feature,
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
			$clean['items'] = $atts['items'];
			/*$clean['items'] = array();
			$item_index = 0;
			foreach ( $atts['items'] as $manual_item ) {
				$test_array['items'][ $item_index ][ $manual_item ] = sanitize_text_field( $atts[ $manual_item ] );
				$item_index++;
			}
			print_r($test_array);*/
		}

		return $clean;

	}

}