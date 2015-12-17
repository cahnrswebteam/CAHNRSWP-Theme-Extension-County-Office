<?php

include_once( __DIR__ . '/includes/customizer.php' ); // Include CAHNRS customizer functionality.
include_once( __DIR__ . '/includes/widgets/county-actions.php' ); // Set up the widget used to display the actions footer area.

/**
 * Set up a theme hook for the site header.
 */
function cahnrswp_site_header() {
	do_action( 'cahnrswp_site_header' );
}

class WSU_Extension_Property_Theme {

	public function __construct() {
		add_action( 'init', array( $this, 'remove_header_meta' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'additional_scripts' ), 21 );
		add_action( 'cahnrswp_site_header', array( $this, 'cahnrswp_default_header' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ), 1 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 1 );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
		add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
		add_filter( 'mce_buttons_2', array( $this, 'mce_buttons_2' ) );
		add_filter( 'theme_page_templates', array( $this, 'theme_page_templates' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_filter( 'cwpb_register_items', array( $this, 'county_pagebuilder_items' ) );
	}

	/**
 	 * Remove certain things Wordpress adds to the header.
 	 */
	public function remove_header_meta() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'wp_head', 'rel_canonical');
		remove_action( 'wp_head', 'wp_generator' );
	}

	/**
	 * Enqueue scripts and styles required for front end pageviews.
	 */
	public function enqueue_scripts() {
		$cahnrs_tooling = spine_get_option( 'cahnrs_tooling' );
		if ( 'develop' !== $cahnrs_tooling && 'disable' !== $cahnrs_tooling && 0 === absint( $cahnrs_tooling ) ) {
			$cahnrs_tooling = 0;
		}
		if ( 'disable' !== $cahnrs_tooling ) {
			wp_enqueue_style( 'cahnrs', 'http://repo.wsu.edu/cahnrs/' . $cahnrs_tooling . '/cahnrs.min.css', array( 'spine-theme' ) );
			wp_enqueue_style( 'spine-theme-child', get_stylesheet_directory_uri() . '/style.css', array( 'cahnrs' ) );
			wp_enqueue_script( 'cahnrs', 'http://repo.wsu.edu/cahnrs/' . $cahnrs_tooling . '/cahnrs.min.js', array( 'jquery' ) );
		}
	}

	/**
	 * Dequeue Spine Bookmark stylesheet (only a precaution).
	 * Enqueue styles and scripts for shortcodes.
	 */
	public function additional_scripts() {
		wp_dequeue_style( 'spine-theme-extra' );
		$post = get_post();
		if ( is_singular() ) {
			if ( is_front_page() && has_shortcode( $post->post_content, 'county_showcase' ) ) {
				wp_enqueue_style( 'cahnrswp-extension-county-showcase', get_stylesheet_directory_uri() . '/css/showcase.css' );
			}
			if ( has_shortcode( $post->post_content, 'county_slideshow' ) ) {
				wp_enqueue_style( 'cahnrswp-extension-county-slideshow', get_stylesheet_directory_uri() . '/css/slideshow.css' );
				wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
			}
			if ( has_shortcode( $post->post_content, 'county_social_media_feed' ) ) {
				wp_enqueue_script( 'facebook-page-plugin', '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5', array(), spine_get_script_version(), true );
			}
		}
	}

	/**
	 * Add the default header via hook.
	 */
	public function cahnrswp_default_header() {
		get_template_part( 'parts/default-header' );
	}

	/**
	 * Enqueue scripts and styles for the admin interface.
	 */
	public function admin_enqueue_scripts( $hook ) {
		$screen = get_current_screen();
		if ( ( 'post-new.php' === $hook || 'post.php' === $hook ) ) {
			wp_enqueue_style( 'county-pagebuilder-items', get_stylesheet_directory_uri() . '/css/admin-pagebuilder-items.css' );
			if ( 'page' === $screen->post_type ) {
				wp_enqueue_style( 'program-info', get_stylesheet_directory_uri() . '/css/admin-program-info.css' );
				wp_enqueue_script( 'program-info', get_stylesheet_directory_uri() . '/js/admin-program-info.js', array( 'jquery' ) );
			}
		}
	}

	/**
	 * Add a metabox context after the title.
	 *
	 * @param WP_Post $post
	 */
	public function edit_form_after_title( $post ) {
		do_meta_boxes( get_current_screen(), 'after_title', $post );
	}

	/**
	 * Add custom meta boxes. (Restrict to displaying only when PB is not active for posts?)
	 *
	 * @param string $post_type The slug of the current post type.
	 */
	public function add_meta_boxes( $post_type ) {
		add_meta_box(
			'cahnrswp_post_sidebar',
			'Post Sidebar',
			array( $this, 'cahnrswp_post_sidebar' ),
			'post',
			'side',
			'default'
		);
		// This works.
		/*$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
		$template = get_post_meta( $post_id, '_wp_page_template', TRUE );
		if ( 'templates/program.php' === $template ) {*/
			add_meta_box(
				'cahnrswp_county_program_info',
				'Program Information',
				array ( $this, 'cahnrswp_county_program_info' ),
				'page',
				'after_title',
				'default'
			);
		//}
	}

	/**
	 * Post sidebar selection markup.
	 */
	public function cahnrswp_post_sidebar( $post ) {
		wp_nonce_field( 'cahnrswp_sidebar', 'cahnrswp_sidebar_nonce' );
		$sidebar = get_post_meta( $post->ID, '_cahnrswp_sidebar', true );
		?><select name="_cahnrswp_sidebar">
			<option value="">select</option>
			<?php
			global $wp_registered_sidebars;
			if ( empty( $wp_registered_sidebars ) ) {
				return	$post_id;
			}
			$value = get_post_meta( $post->ID, '_cahnrswp_sidebar', true );
			foreach ( $wp_registered_sidebars as $sidebar ) : ?>
				<option value="<?php echo $sidebar['id']; ?>" <?php selected( $value, $sidebar['id'] ); ?>><?php echo $sidebar['name']; ?></option>
    	<?php endforeach; ?>
		</select><?php
	}

	/**
	 * Program contact information and icon markup.
	 *
	 * @todo Investigate ways to limit to program pages...
	 */
	public function cahnrswp_county_program_info( $post ) {
		wp_nonce_field( 'cahnrswp_program_info', 'cahnrswp_program_info_nonce' );
		$program_icons = array(
			'4-H' => get_stylesheet_directory_uri() . '/program-icons/4-h.png',
		);
		$program_contact_name = get_post_meta( $post->ID, '_cahnrswp_program_specialist', true );
		$program_contact_phone = get_post_meta( $post->ID, '_cahnrswp_program_phone', true );
		$program_contact_email = get_post_meta( $post->ID, '_cahnrswp_program_email', true );
		$program_icon = get_post_meta( $post->ID, '_cahnrswp_program_icon', true );
		?>
    <select name="_cahnrswp_program_icon" class="cahnrswp-program-icon">
			<option value="">(Icon)</option>
			<?php foreach ( $program_icons as $name => $url ) : ?>
			<option value="<?php echo $url; ?>" <?php selected( $program_icon, $url ); ?>><?php echo $name; ?></option>
    	<?php endforeach; ?>
		</select>
		<p><strong>Specialist Contact Information</strong></p>
		<div class="cahnrswp-program-contact-info">
			<p>
				<label for="cahnrswp-program-specialist">Name, Title</label>
				<input type="text" name="_cahnrswp_program_specialist" id="cahnrswp-program-specialist" class="widefat" value="<?php echo $program_contact_name; ?>" />
			</p>
			<p>
				<label for="cahnrswp-program-phone">Phone</label>
				<input type="text" name="_cahnrswp_program_phone" id="cahnrswp-program-phone" class="widefat" value="<?php echo $program_contact_phone; ?>" />
			</p>
			<p>
				<label for="cahnrswp-program-email">Email</label>
				<input type="text" name="_cahnrswp_program_email" id="cahnrswp-program-email" class="widefat" value="<?php echo $program_contact_email; ?>" />
			</p>
		</div>
		<?php
	}

	/**
	 * Save custom data.
	 *
	 * @param int $post_id
	 *
	 * @return mixed
	 */
	public function save_post( $post_id, $post ) {
		// Bail if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Bail if user doesn't have adequate permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		if ( 'post' == $post->post_type ) {
			// Check nonce.
			if ( ! isset( $_POST['cahnrswp_sidebar_nonce'] ) ) {
				return $post_id;
			}
			$nonce = $_POST['cahnrswp_sidebar_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'cahnrswp_sidebar' ) ) {
				return $post_id;
			}
			// Sanitize and save post sidebar selection.
			if ( isset( $_POST['_cahnrswp_sidebar'] ) ) {
				update_post_meta( $post_id, '_cahnrswp_sidebar', sanitize_text_field( $_POST['_cahnrswp_sidebar'] ) );
			} else {
				delete_post_meta( $post_id, '_cahnrswp_sidebar' );
			}
		}
		if ( 'page' == $post->post_type ) {
			// Check nonce.
			if ( ! isset( $_POST['cahnrswp_program_info_nonce'] ) ) {
				return $post_id;
			}
			$nonce = $_POST['cahnrswp_program_info_nonce'];
			if ( ! wp_verify_nonce( $nonce, 'cahnrswp_program_info' ) ) {
				return $post_id;
			}
			// Sanitize and save program info.
			if ( isset( $_POST['_cahnrswp_program_specialist'] ) ) {
				update_post_meta( $post_id, '_cahnrswp_program_specialist', sanitize_text_field( $_POST['_cahnrswp_program_specialist'] ) );
			} else {
				delete_post_meta( $post_id, '_cahnrswp_program_specialist' );
			}
			if ( isset( $_POST['_cahnrswp_program_phone'] ) ) {
				update_post_meta( $post_id, '_cahnrswp_program_phone', sanitize_text_field( $_POST['_cahnrswp_program_phone'] ) );
			} else {
				delete_post_meta( $post_id, '_cahnrswp_program_phone' );
			}
			if ( isset( $_POST['_cahnrswp_program_email'] ) ) {
				update_post_meta( $post_id, '_cahnrswp_program_email', sanitize_text_field( $_POST['_cahnrswp_program_email'] ) );
			} else {
				delete_post_meta( $post_id, '_cahnrswp_program_email' );
			}
			if ( isset( $_POST['_cahnrswp_program_icon'] ) ) {
				update_post_meta( $post_id, '_cahnrswp_program_icon', sanitize_text_field( $_POST['_cahnrswp_program_icon'] ) );
			} else {
				delete_post_meta( $post_id, '_cahnrswp_program_icon' );
			}
		}
	}

	/**
	 * Filter function to remove the tinymce emoji plugin.
	 *
	 * @param array $plugins The list of default TinyMCE plugins.
	 *
	 * @return array Modified list of default TinyMCE plugins.
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	/**
	 * Register the tinyMCE Table plugin.
	 *
	 * @param array $plugin_array The list of TinyMCE external plugins.
	 *
	 * @return array Modified list of TinyMCE external plugins.
	 */
	public function mce_external_plugins( $plugin_array ) {
		$plugin_array['table'] = get_stylesheet_directory_uri() . '/tinymce/table-plugin.min.js';
		return $plugin_array;
	}

	/**
	 * Add Table controls to tinyMCE editor.
	 *
	 * @param array $buttons The list of second-row TinyMCE buttons (Visual tab).
	 *
	 * @return array Modified list of second-row TinyMCE buttons.
	 */
	public function mce_buttons_2( $buttons ) {
		array_push( $buttons, 'table' );
		return $buttons;
	}

	/**
	 * Remove the Spine page templates.
	 *
	 * @param array $templates The list of page templates from the Spine theme.
	 *
	 * @return array Modified list of page templates.
	 */
	public function theme_page_templates( $templates ) {
		unset( $templates['templates/blank.php'] );
		unset( $templates['templates/halves.php'] );
		unset( $templates['templates/margin-left.php'] );
		unset( $templates['templates/margin-right.php'] );
		unset( $templates['templates/section-label.php'] );
		unset( $templates['templates/side-left.php'] );
		unset( $templates['templates/side-right.php'] );
		unset( $templates['templates/single.php'] );
		return $templates;
	}

	/**
	 * Add custom body classes.
	 *
	 * @param array $classes Current list of body classes.
	 *
	 * @return array Modified list of body classes.
	 */
	public function body_class( $classes ) {
		if ( get_post_meta( get_the_ID(), 'body_class', true ) ) {
			$classes[] = esc_attr( get_post_meta( get_the_ID(), 'body_class', true ) );
		}
		/*if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}*/
		$classes[] = 'spine-' . esc_attr( spine_get_option( 'spine_color' ) );
		return $classes;
	}

	/**
	 * Register sidebars used by the theme.
	 */
	public function widgets_init() {
		register_widget( 'County_Actions_Widget' );
		$widget_options = array(
			'name'          => 'Site Actions',
			'id'            => 'county-actions',
			'description'   => 'Displays the action links on the top of every page.',
		);
		register_sidebar( $widget_options );
	}

	/**
	 *
	 */
	public function county_pagebuilder_items( $items ) {

		$items['county_contact_form'] = array(
			'class'   => 'Item_County_Contact_Form_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/contact-form.php',
			'priority'  => 0,
		);
		$items['county_contact_info'] = array(
			'class'   => 'Item_County_Contact_Info_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/contact-info.php',
			'priority'  => 0,
		);
		$items['county_site_search_form'] = array(
			'class'   => 'Item_County_Site_Search_Form_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/search-form.php',
			'priority'  => 0,
		);
		$items['county_showcase'] = array(
			'class'   => 'Item_County_Showcase_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/showcase.php',
			'priority'  => 0,
		);
		$items['county_slideshow'] = array(
			'class'   => 'Item_County_Slideshow_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/slideshow.php',
			'priority'  => 0,
		);
		$items['county_social_media_feed'] = array(
			'class'   => 'Item_County_Social_Media_Feed_PB',
			'file_path' => __DIR__ . '/includes/shortcodes/social-media-feed.php',
			'priority'  => 0,
		);

		return $items;
	}

}

new WSU_Extension_Property_Theme();