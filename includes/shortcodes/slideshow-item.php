<article class="county-responsive-media" style="background-image:url(<?php echo $image; ?>);">
	<a href="<?php echo $permalink; ?>">
		<header class="article-header">
			<h2 class="article-title"><?php echo $title; ?></h2>
		</header>
		<?php if ( $excerpt ) : ?>
		<div class="article-summary">
			<?php echo $excerpt; ?>
		</div>
		<?php endif; ?>
	</a>
</article>