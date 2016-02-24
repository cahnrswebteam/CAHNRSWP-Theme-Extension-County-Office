<?php

class County_Actions_Widget extends WP_Widget {

	/**
	 * Register widget.
	 */
	public function __construct() {
		parent::__construct(
			'county_actions_widget', // Base ID
			'Action Buttons', // Name
			array(
				'description' => 'Sitewide action buttons',
			)
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$class = is_front_page()? '' : ' class="secondary"' ;
		?>
		<nav<?php echo $class; ?>>
			<ul>
				<?php if ( ! empty( $instance['first_action_url'] ) && ! empty( $instance['first_action_name'] ) ) :
				?><li><a href="<?php echo esc_url( $instance['first_action_url'] ); ?>"><?php echo esc_html( $instance['first_action_name'] ); ?></a></li><?php
				endif;
				if ( ! empty( $instance['second_action_url'] ) && ! empty( $instance['second_action_name'] ) ) :
				?><li><a href="<?php echo esc_url( $instance['second_action_url'] ); ?>"><?php echo esc_html( $instance['second_action_name'] ); ?></a></li><?php
    		endif;
				if ( ! empty( $instance['third_action_url'] ) && ! empty( $instance['third_action_name'] ) ) :
				?><li><a href="<?php echo esc_url( $instance['third_action_url'] ); ?>"><?php echo esc_html( $instance['third_action_name'] ); ?></a></li><?php
    		endif; ?>
			</ul>
		</nav>
    <?php
	}

	/**
	 * Display the form used to update the widget.
	 *
	 * @param array $instance The instance of the current widget form being displayed.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$first_action_url   = ( ! empty( $instance['first_action_url'] ) ) ? $instance['first_action_url'] : '';
		$first_action_name  = ( ! empty( $instance['first_action_name'] ) ) ? $instance['first_action_name'] : '';
		$second_action_url  = ( ! empty( $instance['second_action_url'] ) ) ? $instance['second_action_url'] : '';
		$second_action_name = ( ! empty( $instance['second_action_name'] ) ) ? $instance['second_action_name'] : '';
		$third_action_url   = ( ! empty( $instance['third_action_url'] ) ) ? $instance['third_action_url'] : '';
		$third_action_name  = ( ! empty( $instance['third_action_name'] ) ) ? $instance['third_action_name'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'first_action_name' ); ?>">First Action Name</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'first_action_name' ); ?>" name="<?php echo $this->get_field_name( 'first_action_name' ); ?>" type="text" value="<?php echo esc_attr( $first_action_name ); ?>" /><br />
			<label for="<?php echo $this->get_field_id( 'first_action_url' ); ?>">First Action URL</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'first_action_url' ); ?>" name="<?php echo $this->get_field_name( 'first_action_url' ); ?>" type="text" value="<?php echo esc_attr( $first_action_url ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'second_action_name' ); ?>">Second Action Name</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'second_action_name' ); ?>" name="<?php echo $this->get_field_name( 'second_action_name' ); ?>" type="text" value="<?php echo esc_attr( $second_action_name ); ?>" /><br />
			<label for="<?php echo $this->get_field_id( 'second_action_url' ); ?>">Second Action URL</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'second_action_url' ); ?>" name="<?php echo $this->get_field_name( 'second_action_url' ); ?>" type="text" value="<?php echo esc_attr( $second_action_url ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'third_action_name' ); ?>">Third Action Name</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'third_action_name' ); ?>" name="<?php echo $this->get_field_name( 'third_action_name' ); ?>" type="text" value="<?php echo esc_attr( $third_action_name ); ?>" /><br />
			<label for="<?php echo $this->get_field_id( 'third_action_url' ); ?>">Third Action URL</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'third_action_url' ); ?>" name="<?php echo $this->get_field_name( 'third_action_url' ); ?>" type="text" value="<?php echo esc_attr( $third_action_url ); ?>" />
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new instance of the widget being saved.
	 * @param array $old_instance Previous instance of the current widget.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['first_action_url']   = ( ! empty( $new_instance['first_action_url'] ) ) ? sanitize_text_field( $new_instance['first_action_url'] ) : '';
		$instance['first_action_name']  = ( ! empty( $new_instance['first_action_name'] ) ) ? sanitize_text_field( $new_instance['first_action_name'] ) : '';
		$instance['second_action_url']  = ( ! empty( $new_instance['second_action_url'] ) ) ? sanitize_text_field( $new_instance['second_action_url'] ) : '';
		$instance['second_action_name'] = ( ! empty( $new_instance['second_action_name'] ) ) ? sanitize_text_field( $new_instance['second_action_name'] ) : '';
		$instance['third_action_url']   = ( ! empty( $new_instance['third_action_url'] ) ) ? sanitize_text_field( $new_instance['third_action_url'] ) : '';
		$instance['third_action_name']  = ( ! empty( $new_instance['third_action_name'] ) ) ? sanitize_text_field( $new_instance['third_action_name'] ) : '';

		return $instance;
	}
}