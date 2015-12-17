<article class="county-responsive-media" style="background-image:url(<?php echo $image; ?>);">
	<a href="<?php echo esc_url( $permalink ); ?>">
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