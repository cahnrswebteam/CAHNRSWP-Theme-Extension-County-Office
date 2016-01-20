<?php
class Item_County_Contact_Form_PB extends Item_PB {

	/**
	 * @var string Shortcode tag.
	 */
	public $slug = 'county_contact_form';

	/**
	 * @var string Name for displaying in Pagebuilder interface.
	 */
	public $name = 'Contact Form';

	/**
	 * @var string Description for displaying in Pagebuilder interface.
	 */
	public $desc = 'Adds a contact form';

	/**
	 * @var string Size of GUI for Pagebuilder.
	 */
	public $form_size = 'small';

	/**
	 * Display markup.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function item( $atts ) {

		$defaults = array(
			'recipient' => '',
			'subject'   => 'Contact form submission from the ' .  get_bloginfo('name') . ' website',
			'thanks'    => "Thank you for your interest! We will respond to your message as soon as we're able.",
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['recipient'] ) ) {
			return '';
		}

		ob_start();
		if ( $_POST['submit'] ) {
			$this->process_form_submission( $atts['recipient'], $atts['subject'], $atts['thanks'] );
		} else {
			$this->contact_form();
		}
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Form markup.
	 */
	public function contact_form() {
		?>
		<form action="#contact-form-<?php echo get_the_ID(); ?>"  id="contact-form-<?php echo get_the_ID(); ?>" method="post" class="county-contact-form <?php if ( isset( $_POST['submit'] ) ) { echo ' error'; } ?>">

			<h2>Contact Us</h2>

			<?php if ( isset( $_POST['submit'] ) ) : ?>
			<p class="error">Sorry, it looks like you forgot some required fields.</p>
			<?php endif; ?>

			<p>
				<label>
					<span class="screen-reader-text">Your name</span>
					<input type="text" pattern="[a-zA-Z ]+" name="contact-name" placeholder="Your name" class="basic-field" required="required" value="<?php echo ( isset( $_POST['contact-name'] ) ? esc_attr( $_POST['contact-name'] ) : '' ); ?>" />
				</label>
			</p>

			<p>
				<label>
					<span class="screen-reader-text">Your email address</span>
					<input type="email" id="contact-email" name="contact-email" placeholder="Your email address" class="basic-field" required="required" value="<?php echo ( isset( $_POST['contact-email'] ) ? esc_attr( $_POST['contact-email'] ) : '' ); ?>" />
				</label>
			</p>

			<p><label for="contact-message">How can we help?</label><br />
			<textarea id="contact-message" name="contact-message"><?php echo ( isset( $_POST['contact-message'] ) ? stripslashes( wp_kses_post( $_POST['contact-message'] ) ) : '' ); ?></textarea></p>

			<input type="submit" id="submit" name="submit" value="Submit" />

		</form>
		<?php
	}

	/**
	 * If the submit button is clicked, send an email.
	 */
	public function process_form_submission( $recipient, $subject, $thanks_message ) {

		if ( $_POST['contact-name'] && $_POST['contact-email'] && $_POST['contact-message'] ) :

			// sanitize form values
			$name    = sanitize_text_field( $_POST['contact-name'] );
			$email   = sanitize_email( $_POST['contact-email'] );
			$content = wp_kses_post( $_POST['contact-message'] );

			$to = sanitize_email( $recipient );
			$subject = sanitize_text_field( $subject );
			$message = $content;
			$headers = "From: $name <$email>" . "\r\n";

			// If email has been processed for sending, display a success message.
			add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
			if ( wp_mail( $to, $subject, $message, $headers ) ) {
				echo '<p id="contact-form-' . get_the_ID() . '"><strong>' . $thanks_message . '</strong></p>';
			} else {
				echo '<p id="contact-form-' . get_the_ID() . '" class="error">An unexpected error occurred.</p>'; // This could be more helpful
			}
			remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );

		else :

			$this->contact_form();

		endif;

	}

	/**
	 * Filter for sending HTML emails.
	 */
	public function set_html_content_type() {
		return 'text/html';
	}

	/**
	 * Editor markup
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function editor( $atts ) {

		ob_start();
		?>
		<div class="county-contact-form">
			<h2>Contact Us</h2>
			<div>Your name</div>
			<div>Your email address</div>
			<p>How can we help?</p>
			<textarea id="contact-message" name="contact-message"></textarea>
			<div class="county-contact-form-submit">Submit</div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;

	}

	/**
	 * Pagebuilder GUI fields.
	 *
	 * @param $atts Shortcode attributes/field values.
	 *
	 * @return string
	 */
	public function form( $atts ) {

		$html = Forms_PB::text_field( $this->get_name_field( 'recipient' ), $atts['recipient'], 'Mail to' );

		$html .= Forms_PB::text_field( $this->get_name_field( 'subject' ), $atts['subject'], 'Email subject' );

		$html .= Forms_PB::text_field( $this->get_name_field( 'thanks' ), $atts['thanks'], 'Thanks message' );

		return $html;

	}

	/**
	 * Sanitize input data.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return array
	 */
	public function clean( $atts ) {

		$clean = array();

		if ( ! empty( $atts['recipient'] ) ) {
			$clean['recipient'] = sanitize_text_field( $atts['recipient'] );
		}

		if ( ! empty( $atts['subject'] ) ) {
			$clean['subject'] = sanitize_text_field( $atts['subject'] );
		}

		if ( ! empty( $atts['thanks'] ) ) {
			$clean['thanks'] = sanitize_text_field( $atts['thanks'] );
		}

		return $clean;

	}

}