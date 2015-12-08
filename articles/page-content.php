<?php
	//$hide_title = get_post_meta( get_the_ID(), '_cahnrswp_hide_title', true );
	$program_contact_specialist = get_post_meta( get_the_ID(), '_cahnrswp_program_specialist', true );
	$program_contact_phone = get_post_meta( get_the_ID(), '_cahnrswp_program_phone', true );
	$program_contact_email = get_post_meta( get_the_ID(), '_cahnrswp_program_email', true );
	$program_icon = get_post_meta( get_the_ID(), '_cahnrswp_program_icon', true );
?>
<?php if ( ! is_front_page() /*&& ! $hide_title*/ ) : ?>
<header class="article-header">
  <?php if ( $program_icon ) : ?><img src="<?php echo esc_url( $program_icon ); ?>" height="77" width="76" class="extension-program-icon" /><?php endif; ?>
  <h1 class="article-title"><?php the_title(); ?></h1>
  <?php if ( $program_contact_specialist || $program_contact_phone || $program_contact_email ) : ?>
  <p><?php if ( $program_contact_specialist ) { echo 'Program Contact: ' . esc_html( $program_contact_specialist ); } ?>
  <?php if ( $program_contact_phone ) { echo ' &bull; ' . esc_html( $program_contact_phone ); } ?>
  <?php if ( $program_contact_email ) { echo ' &bull; <a href="mailto:' . esc_attr( $program_contact_email ) . '">' . esc_html( $program_contact_email ) . '</a>'; } ?>
  </p>
  <?php endif; ?>
</header>
<?php endif; ?>

<?php the_content(); ?>