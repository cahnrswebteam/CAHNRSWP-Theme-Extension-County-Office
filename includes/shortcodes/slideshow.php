<?php
class County_Extension_Slideshow {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
		add_shortcode( 'county_slideshow', array( $this, 'display_county_slideshow' ) );
	}

	/**
	 * Enqueue scripts and styles for the slideshow.
	 */
	public function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && has_shortcode( $post->post_content, 'county_slideshow' ) ) {
			wp_enqueue_style( 'cahnrswp-extension-county-slideshow', get_stylesheet_directory_uri() . '/css/slideshow.css' );
			wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
		}
	}

	/**
	 * Display custom markup used for slideshows.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_county_slideshow( $atts ) {

		$defaults = array(
			'source'    => '',
			'count'     => 5,
			'post_type' => '',
			'taxonomy'  => '',
			'terms'     => '',
			'items'     => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		/*if ( empty( $atts[''] ) ) {
			return '';
		}*/

		ob_start();
		?>
		<div class="county-slideshow cycle-slideshow" data-cycle-log="false" data-cycle-slides=".county-responsive-media" data-cycle-swipe="true" data-cycle-timeout="6000" data-cycle-fx="fade">
			<?php
				if ( 'feed' === $atts['source'] ) {
					$slideshow_query_args = array(
						'posts_per_page' => $atts['count'],
					);
					if ( $atts['post_type'] ) {
						$slideshow_query_args['post_type'] = $atts['post_type'];
					}
					if ( 'category' === $atts['taxonomy'] ) {
						$slideshow_query_args['category_name'] = $atts['terms'];
					} elseif ( 'tag' === $atts['taxonomy'] ) {
						$slideshow_query_args['tag'] = $atts['terms'];
					}
					$slideshow_query = new WP_Query( $slideshow_query_args );
					if ( $slideshow_query->have_posts() ) :
						while ( $slideshow_query->have_posts() ) : $slideshow_query->the_post();
							// Only show posts that have a featured image.
							if ( has_post_thumbnail() ) {
								set_query_var( 'image', wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );
								set_query_var( 'permalink', get_the_permalink() );
								set_query_var( 'title', get_the_title() );
								//set_query_var( 'excerpt', '<p>' . get_the_excerpt() . '</p>' );
								get_template_part( 'includes/shortcodes/slideshow-item' );
							}
						endwhile;
					endif;
					wp_reset_postdata();
				} elseif ( 'manual' === $atts['source'] ) {
					$items = json_decode( $atts['items'], true );
					if ( is_array( $items ) ) {
						foreach ( $items as $item ) {
							if ( $item['img'] ) {
								set_query_var( 'image', $item['img'] );
								set_query_var( 'permalink', $item['link'] );
								set_query_var( 'title', $item['title'] );
								//set_query_var( 'excerpt', '<p>' . $item['excerpt'] . '</p>' );
								get_template_part( 'includes/shortcodes/slideshow-item' );
							}
						}
					}
				}
			?>
			<div class="cycle-pager"></div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

new County_Extension_Slideshow();