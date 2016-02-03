<?php
// Get program meta from this page or its parents if there is any.
$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
if ( 'templates/program.php' === $page_template ) {
	$program_contact_specialist = get_post_meta( get_the_ID(), '_cahnrswp_program_specialist', true );
	$program_contact_phone = get_post_meta( get_the_ID(), '_cahnrswp_program_phone', true );
	$program_contact_email = get_post_meta( get_the_ID(), '_cahnrswp_program_email', true );
	$program_icon = ( get_post_meta( get_the_ID(), '_cahnrswp_program_icon', true ) ) ? get_stylesheet_directory_uri() . '/program-icons/' . get_post_meta( get_the_ID(), '_cahnrswp_program_icon', true ) . '.png' : NULL;
} else {
	$parent_pages  = get_post_ancestors( get_the_ID() );
	if ( $parent_pages ) {
		foreach ( $parent_pages as $parent_page ) {
			$parent_page_template = get_post_meta( $parent_page, '_wp_page_template', true );
			if ( 'templates/program.php' !== $parent_page_template ) {
				continue;
			} else {
				$program_contact_specialist = get_post_meta( $parent_page, '_cahnrswp_program_specialist', true );
				$program_contact_phone = get_post_meta( $parent_page, '_cahnrswp_program_phone', true );
				$program_contact_email = get_post_meta( $parent_page, '_cahnrswp_program_email', true );
				$program_icon = ( get_post_meta( $parent_page, '_cahnrswp_program_icon', true ) ) ? get_stylesheet_directory_uri() . '/program-icons/' . get_post_meta( $parent_page, '_cahnrswp_program_icon', true ) . '.png' : NULL;
				break;
			}
		}
	}
}
?>

<?php if ( ! is_front_page() ) : ?>
<header class="article-header">
	<?php if ( $program_icon ) : ?><img src="<?php echo esc_url( $program_icon ); ?>" height="75" width="75" class="extension-program-icon" /><?php endif; ?>
	<h1 class="article-title"><?php the_title(); ?></h1>
	<?php if ( $program_contact_specialist || $program_contact_phone || $program_contact_email ) : ?>
	<p><?php if ( $program_contact_specialist ) { echo 'Program Contact: ' . esc_html( $program_contact_specialist ); } ?>
  <?php if ( $program_contact_phone || $program_contact_email ) : ?><br /><?php endif; ?>
	<?php if ( $program_contact_phone ) { echo esc_html( $program_contact_phone ); } ?>
  <?php if ( $program_contact_phone && $program_contact_email ) : ?> &bull; <?php endif; ?>
	<?php if ( $program_contact_email ) { echo '<a href="mailto:' . esc_attr( $program_contact_email ) . '">' . esc_html( $program_contact_email ) . '</a>'; } ?>
	</p>
	<?php endif; ?>
</header>
<?php endif; ?>

<?php the_content(); ?>