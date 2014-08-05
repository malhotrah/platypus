<?php
/*
 * Template Name: Contact
 */
?>
<?php get_header(); ?>

<?php
$form = whisper_meta( 'form' );

if ( !$form )
{
	$error = false;
	$sent = false;
	if ( isset( $_POST['submit'] ) )
	{
		// Check fields
		$fields = array( 'first_name', 'last_name', 'address', 'message', 'spam_check' );
		foreach ( $fields as $field )
		{
			if ( empty( $_POST[$field] ) || trim( $_POST[$field] ) == '' )
				$error = true;
		}

		// Check for spam using Honeypot technique
		if ( !$error && !empty( $_POST['email'] ) )
			$error = true;

		// Check for spam using math
		if ( !$error )
		{
			if ( false === strpos( $_POST['spam_check'], '.' ) )
			{
				$error = true;
			}
			else
			{
				$spam_check = array_map( 'intval', explode( '.', $_POST['spam_check'] ) );
				if ( count( $spam_check ) != 3 || ( $spam_check[0] != $spam_check[1] * 2 + $spam_check[2] * 3 - 7 ) )
					$error = true;
			}
		}

		// Check valid email
		if ( !$error && !is_email( $_POST['address'] ) )
			$error = true;

		// Send email
		if ( !$error )
		{
			$first_name = trim( $_POST['first_name'] );
			$last_name = trim( $_POST['last_name'] );

			// Use 'address' instead of 'email' to prevent spam. Honeypot technique.
			$email = trim( $_POST['address'] );
			$message = trim( $_POST['message'] );

			$to = whisper_meta( 'email' );
			if ( !$to )
				$to = get_bloginfo( 'admin_email' );
			$subject = __( 'New Contact Email', 'whisper' );
			$body = "First Name: $first_name \n\n";
			$body .= "Last Name: $last_name \n\n";
			$body .= "Email: $email \n\n";
			$body .= "Message: $message \n\n";

			$headers = 'From: ' . get_bloginfo( 'name' ) . "\r\n" . 'Reply-To: ' . $email;
			$sent = wp_mail( $to, $subject, $body );
			$error = !$sent;
		}
	}
}
?>

<?php if ( have_posts() ) : the_post(); ?>

	<div class="grid_4 alpha">

		<?php
		echo whisper_meta( 'location', array(
			'type'         => 'map',
			'width'        => '100%',
			'height'       => '470px',
			'marker_title' => whisper_meta( 'address' ),
			'info_window'  => whisper_meta( 'address' ),
			'js_options'   => array(
				'disableDefaultUI' => true,
				'zoom'             => 15,
			)
		) );
		?>

	</div>

	<div class="grid_4">

		<h3><?php _e( 'How can we help you?', 'whisper' ); ?></h3>

		<?php
		if ( $form )
		{
			echo do_shortcode( $form );
		}
		else
		{
			?>

			<?php if ( $error ) : ?>
				<div class="fitsc-box fitsc-error">
					<p><?php _e( 'Please check if you have filled all the fields with valid information. Thank you.', 'whisper' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $sent ) : ?>
				<div class="fitsc-box fitsc-success">
					<p><?php _e( 'Email Successfully Sent!<br>Thank you for using my contact form! I will be in touch with you soon.', 'whisper' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo get_permalink(); ?>" class="contact-form">

				<fieldset>
					<label for="name"><span class="text-color">*</span> <?php _e( 'First Name:', 'whisper' ); ?></label>
					<input type="text" name="first_name" required>
				</fieldset>

				<fieldset>
					<label for="lastname"><span class="text-color">*</span> <?php _e( 'Last Name:', 'whisper' ); ?></label>
					<input type="text" name="last_name" required>
				</fieldset>

				<fieldset>
					<label for="email"><span class="text-color">*</span> <?php _e( 'Email:', 'whisper' ); ?></label>
					<input type="email" name="address" required>
				</fieldset>

				<fieldset>
					<label for="message"><span class="text-color">*</span> <?php _e( 'Message:', 'whisper' ); ?></label>
					<textarea rows="5" name="message" required></textarea>
				</fieldset>

				<?php
				// Simple spam detect
				$op1 = rand( 1, 100 );
				$op2 = rand( 1, 100 );
				$op3 = $op1 * 2 + $op2 * 3 - 7; // Random math
				?>
				<input type="hidden" name="spam_check" value="<?php echo "{$op3}.{$op1}.{$op2}"; ?>">

				<?php // Honeypot spam detect ?>
				<input type="text" name="email" style="display:none">

				<input type="submit" class="fitsc-button" value="<?php _e( 'Submit', 'whisper' ); ?>" name="submit">

			</form>

			<?php
		}
		?>

	</div>

	<div class="grid_4 omega">
		<?php the_content(); ?>

		<h3><?php _e( 'Contact Information', 'whisper' ); ?></h3>

		<ul class="contact-info">
			<li>
				<span class="text-color"><b><?php _e( 'T:', 'whisper' ); ?></b></span>
				<?php echo whisper_meta( 'phone' ); ?>
			</li>
			<li>
				<span class="text-color"><b><?php _e( 'E:', 'whisper' ); ?></b></span>
				<?php echo whisper_meta( 'email' ); ?>
			</li>
			<li class="address"><span><?php echo whisper_meta( 'address' ); ?></span></li>
		</ul>
	</div>

<?php endif; ?>