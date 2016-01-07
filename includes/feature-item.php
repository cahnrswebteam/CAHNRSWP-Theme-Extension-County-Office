<?php function wsu_extension_county_feature_item( $image, $link, $title, $excerpt ) { ?>
<article class="county-responsive-media" style="background-image:url(<?php echo $image; ?>);">
	<a href="<?php echo esc_url( $link ); ?>">
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
<?php }