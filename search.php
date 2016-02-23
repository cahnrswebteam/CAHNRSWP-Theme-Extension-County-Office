<?php get_header(); ?>

<main class="spine-search-index">

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<form role="search" method="get" class="cahnrs-search" action="<?php echo home_url( '/' ); ?>">
				<label>
					<span class="screen-reader-text">Search for:</span>
					<input type="search" class="cahnrs-search-field" placeholder="Search" value="<?php echo get_search_query(); ?>" name="s" title="Search for:" />
				</label>
				<input type="submit" class="cahnrs-search-submit" value="$" />
			</form>

			<p><?php echo $wp_query->found_posts; ?> Results</p>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="article-header">
						<hgroup>
							<h2 class="article-title">
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h2>
						</hgroup>
						<hgroup class="source">
							<time class="article-date" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
							<cite class="article-author" role="author"><?php the_author_posts_link(); ?></cite>
						</hgroup>
					</header>
				</article>

			<?php endwhile; // end of the loop. ?>

		</div><!--/column-->

	</section>

	<?php
		/* @type WP_Query $wp_query */
		global $wp_query;
		$big = 99164;
		$args = array(
			'base'         => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'       => 'page/%#%',
			'total'        => $wp_query->max_num_pages, // Provide the number of pages this query expects to fill.
			'current'      => max( 1, get_query_var('paged') ), // Provide either 1 or the page number we're on.
		);
	?>
	<footer class="main-footer archive-footer">
		<section class="row single pager prevnext gutter">
			<div class="column one">
				<?php echo paginate_links( $args ); ?>
			</div>
		</section>
	</footer>

	<?php get_template_part( 'parts/footers' ); ?>

</main>
<?php

get_footer();