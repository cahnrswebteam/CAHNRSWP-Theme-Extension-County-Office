<?php

class County_Extension_Landing_Page_Showcase {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 21 );
		add_shortcode( 'landing_page_showcase', array( $this, 'display_landing_page_showcase' ) );
	}

	/**
	 * Enqueue scripts and styles for the landing page showcase.
	 */
	public function wp_enqueue_scripts() {
		$post = get_post();
		if ( is_singular() && is_front_page() && has_shortcode( $post->post_content, 'landing_page_showcase' ) ) {
			wp_enqueue_style( 'cahnrswp-extension-county-showcase', get_stylesheet_directory_uri() . '/css/showcase.css' );
			//wp_enqueue_script( 'cahnrswp-extension-county-shocase', get_stylesheet_directory_uri() . '/js/showcase.js', array( 'jquery' ) );
		}
	}

	/**
	 * Display custom markup used for the landing page showcase.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_landing_page_showcase( $atts ) {

		$defaults = array(
			'feature_source'    => '',
			'feature_post_type' => '',
			'feature_taxonomy'  => '',
			'feature_terms'     => ''
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
								if ( has_post_thumbnail() ) {
									$feature_image_src = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
								$feature_permalink = get_the_permalink();
								$feature_title = get_the_title();
								$feature_excerpt = '<p>' . get_the_excerpt() . '</p>';
							endwhile;
						endif;
						wp_reset_postdata();
					}/* elseif ( 'post' === $atts['feature_source'] ) {
						or whatever
					}*/

					if ( isset( $feature_title ) ) :
				?>
				<article class="county-responsive-media"<?php echo ' style="background-image:url(' . $feature_image_src . ');"'; ?>>
					<a href="<?php echo $feature_permalink; ?>">
						<header class="article-header">
							<h2 class="article-title"><?php echo $feature_title; ?></h2>
						</header>
						<div class="article-summary">
							<?php echo $feature_excerpt ?>
						</div>
					</a>
				</article>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<div class="syndicated">

				<?php
					// Probably use WP API for this. RSS would be viable too, I suppose.
					$syndicated_image_src = 'http://m1.wpdev.cahnrs.wsu.edu/extension-property/wp-content/uploads/sites/18/2015/05/palouse-night-1188x891.jpg';
					$syndicated_permalink = '#';
					$syndicated_title = 'Syndicated Content';

					if ( isset( $syndicated_title ) ) :
				?>
				<article class="county-responsive-media" style="background-image:url(<?php echo $syndicated_image_src; ?>);">
					<a href="<?php echo $syndicated_permalink; ?>">
						<header class="article-header">
							<h2 class="article-title"><?php echo $syndicated_title; ?></h2>
						</header>
					</a>
				</article>
				<?php endif; ?>

				<?php
					$additional_syndicated_image_src = 'http://m1.wpdev.cahnrs.wsu.edu/cahnrs-property/wp-content/uploads/sites/19/2015/06/palouse-1188x891.jpg';
					$additional_syndicated_permalink = '#';
					$additional_syndicated_title = 'Syndicated Content';

					if ( isset( $additional_syndicated_title ) ) :
				?>
				<article class="county-responsive-media" style="background-image:url(<?php echo $additional_syndicated_image_src; ?>);">
					<a href="<?php echo $additional_syndicated_permalink; ?>">
						<header class="article-header">
							<h2 class="article-title"><?php echo $additional_syndicated_title; ?></h2>
						</header>
					</a>
				</article>
				<?php endif; ?>

			</div>

		</section>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}

new County_Extension_Landing_Page_Showcase();