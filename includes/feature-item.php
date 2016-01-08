<?php
/**
 * Display an item in the Showcase or Slideshow shortcode.
 *
 * @param string $image        Item image.
 * @param string $link         Item link.
 * @param string $title        Item title.
 * @param string $excerpt      Item excerpt.
 * @param bool   $target_blank Whether to include target="_blank" attribute.
 *
 */
function wsu_extension_county_feature_item( $image, $link, $title, $excerpt, $target_blank ) {
	?>
	<article class="county-responsive-media" style="background-image:url(<?php echo $image; ?>);">
		<a href="<?php echo esc_url( $link ); ?>"<?php if ( $target_blank ) { echo ' target="_blank"'; } ?>>
			<header class="article-header">
				<h2 class="article-title"><?php echo esc_html( $title ); ?></h2>
			</header>
			<?php if ( $excerpt ) : ?>
			<div class="article-summary">
				<?php echo wp_kses_post( $excerpt ); ?>
			</div>
			<?php endif; ?>
		</a>
	</article>
	<?php 
}