<?php
class Item_County_Programs_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_programs';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'County Programs';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'Highlight up to six county programs.';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function item( $atts ) {
		return $this->county_programs_display( $atts, 'public' );
	}

	/**
	 * Editor markup.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function editor( $atts ) {
		return $this->county_programs_display( $atts, 'editor' );
	}

	/**
	 * Output for display and editor views.
	 *
	 * @param array  $atts Shortcode attributes/field values.
	 * @param string $view Front or back end.
	 *
	 * @return string
	 */
	public function county_programs_display( $atts, $view ) {

		$defaults = array(
			'title' => 'County Programs',
			'pages' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		//if ( empty( $atts['pages'] ) ) {
		//	return ( 'public' === $view ) ? '' : '<p>Click to select programs</p>';
		//}

		ob_start();
		?>
    <h3 class="county-programs-title"><?php echo esc_html( $atts['title'] ); ?></h3>
		<ul class="county-programs">
		<?php
			//foreach ( $atts['pages'] as $page ) {
			foreach ( array( 5, 11, 7, 268 ) as $program_page ) {
				$class = get_post_meta( $program_page, '_cahnrswp_program_icon', true );
				$href = get_the_permalink( $program_page );
				$title = get_the_title( $program_page );
				?><li class="<?php echo $class; ?>">
					<a href="<?php echo $href; ?>">
						<?php echo $title; ?>
					</a>
				</li><?php
			}
		?>
		</ul>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @param array $atts Shortcode attributes/field values.
	 *
	 * @return string
	 *
	 * @todo Store selection values as an array.
	 */
	public function form( $atts ) {

		$program_pages = array(
			'' => 'Select',
		);

		$get_program_pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'templates/program.php'
		) );

		foreach ( $get_program_pages as $page ) {
			$program_pages[ $page->ID ] = $page->post_title;
		}

		$html  = Forms_PB::text_field( $this->get_name_field( 'title' ), $atts['title'], 'Title', 'cpb-field-one-column' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );
		$html .= Forms_PB::select_field( $this->get_name_field( 'pages' ), $atts['pages'], $program_pages, 'Page' );

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return array
	 */
	public function clean( $atts ) {

		$clean = array();
		$clean['title'] = ( ! empty( $atts['title'] ) ) ? sanitize_text_field( $atts['title'] ) : 'County Programs';
		$clean['pages'] = ( ! empty( $atts['pages'] ) ) ? sanitize_text_field( $atts['pages'] ) : '';

		return $clean;

	}

}