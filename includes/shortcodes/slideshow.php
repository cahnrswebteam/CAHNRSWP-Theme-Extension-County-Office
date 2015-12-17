<?php
class Item_County_Slideshow_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_slideshow';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Slideshow';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = "A slideshow";

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
		if ( is_singular() && has_shortcode( $post->post_content, 'county_slideshow' ) ) {
			wp_enqueue_style( 'cahnrswp-extension-county-slideshow', get_stylesheet_directory_uri() . '/css/slideshow.css' );
			wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
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
			'source'         => '',
			'post_type'      => '',
			'taxonomy'       => '',
			'terms'          => '',
			'posts_per_page' => '3',
			'items'          => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['source'] ) ) {
			return '';
		}

		$count = is_numeric( $atts['posts_per_page'] ) ? esc_html( $atts['posts_per_page'] ) : 3;

		ob_start();
		?>
		<div class="county-slideshow cycle-slideshow" data-cycle-log="false" data-cycle-slides=".county-responsive-media" data-cycle-swipe="true" data-cycle-timeout="6000" data-cycle-fx="fade">

			<?php
				if ( 'feed' === $atts['source'] ) {
					$county_feature_query_args = array(
						'posts_per_page' => $count,
					);
					if ( $atts['post_type'] ) {
						$county_feature_query_args['post_type'] = $atts['post_type'];
					}
					if ( 'category' === $atts['taxonomy'] ) {
						$county_feature_query_args['category_name'] = $atts['terms'];
					} elseif ( 'tag' === $atts['taxonomy'] ) {
						$county_feature_query_args['tag'] = $atts['terms'];
					}
					$county_feature_query = new WP_Query( $county_feature_query_args );
					if ( $county_feature_query->have_posts() ) :
						while ( $county_feature_query->have_posts() ) : $county_feature_query->the_post();
							// Only show posts that have a featured image.
							if ( has_post_thumbnail() ) {
								$image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								$permalink = get_the_permalink();
								$title = get_the_title();
								//$excerpt = '<p>' . get_the_excerpt() . '</p>';
								include( __DIR__ . '/feature-item.php' );
								unset( $image, $permalink, $title/*, $excerpt*/ );
							}
						endwhile;
					endif;
					wp_reset_postdata();
				} /*elseif ( 'manual' === $atts['feature_source'] ) {
					$items = json_decode( $atts['items'], true );
					if ( is_array( $items ) ) {
						foreach ( $items as $item ) {
							if ( $item['img'] ) {
								$image = $item['img'];
								$permalink = $item['link'];
								$title = $item['title'];
								//$excerpt = '<p>' . $item['excerpt'] . '</p>';
								include( __DIR__ . '/feature-item.php' );
								unset( $image, $permalink, $title/*, $excerpt*//* );
							}
						}
					}
				}*/
			?>

			<div class="cycle-pager"></div>

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

		$html = 'Slideshow';

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

		$html .= $this->accordion_radio(
			$this->get_name_field('source'),
			'feed',
			$atts['source'],
			'Feed',
			Forms_PB::local_feed( $this->get_name_field(), $atts ),
			'Most recent posts or pages, optionally based on categories or tags.'
		);

		/*$html .= $this->accordion_radio(
			$this->get_name_field('source'),
			'cherry',
			$atts['source'],
			'Cherry Pick',
			'',//Forms_PB::local_feed( $this->get_name_field(), $atts ),
			'Select individual posts or pages.'
		);*/ // Quite possibly made redundant by the manual option

		/*$html .= $this->accordion_radio(
			$this->get_name_field('source'),
			'manual',
			$atts['source'],
			'Manual',
			'',//Forms_PB::manual_feature( $this->get_name_field(), $atts ),
			'Build your own slideshow by setting the image, URL, title, and additional text for each slide.'
		);*/

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

		if ( ! empty( $atts['post_type'] ) ) {
			$clean['post_type'] = sanitize_text_field( $atts['post_type'] );
		}

		if ( ! empty( $atts['taxonomy'] ) ) {
			$clean['taxonomy'] = sanitize_text_field( $atts['taxonomy'] );
		}

		if ( ! empty( $atts['terms'] ) ) {
			$clean['terms'] = sanitize_text_field( $atts['terms'] );
		}

		if ( ! empty( $atts['posts_per_page'] ) ) {
			$clean['posts_per_page'] = sanitize_text_field( $atts['posts_per_page'] );
		}

		/*if ( ! empty( $atts['items'] ) ) {
			
		}*/

		return $clean;

	}

}