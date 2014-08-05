<?php
if ( post_password_required() )
	return;
?>
<div class="comments">

	<?php if ( have_comments() ) : ?>

		<h3 class="comments-title"><?php comments_number( __( 'Comments', 'whisper' ), __( 'Comment (1)', 'whisper' ), __( 'Comments (%)', 'whisper' ) ); ?></h3>

		<ul class="comment-list">
			<?php wp_list_comments( array( 'callback' => 'whisper_comment' ) ); ?>
		</ul>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="navigation comment-navigation" role="navigation">
				<h5 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'whisper' ); ?></h5>

				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'whisper' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'whisper' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( !comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'whisper' ); ?></p>
		<?php endif; ?>

	<?php endif; ?><!-- have_comments -->

	<?php
	if ( comments_open() )
	{
		$commenter = wp_get_current_commenter();
		$aria_req = get_option( 'require_name_email' ) ? " aria-required='true'" : '';
		$comment_args = array(
			'title_reply'          => __( 'Leave a comment', 'whisper' ),
			'id_submit'            => 'comment-reply',
			'label_submit'         => __( 'Submit', 'whisper' ),
			'fields'               => apply_filters( 'comment_form_default_fields', array(
				'author' => '<fieldset class="name-container">
								<label for="author"><span class="text-color">*</span> ' . __( 'Name:', 'whisper' ) . '</label>
								<input type="text" id="author" class="tb-my-input" name="author" tabindex="1" value="' . esc_attr( $commenter['comment_author'] ) . '" size="32"' . $aria_req . '>
							</fieldset>',
				'email'  => '<fieldset class="email-container">
								<label for="email"><span class="text-color">*</span> ' . __( 'Email:', 'whisper' ) . '</label>
								<input type="text" id="email" class="tb-my-input" name="email" tabindex="2" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="32"' . $aria_req . '>
							</fieldset>'
			) ),
			'comment_field'        => '<fieldset class="message">
									<label for="comment"><span class="text-color">*</span> ' . __( 'Message:', 'whisper' ) . '</label>
									<textarea id="comment-message" name="comment" rows="8" tabindex="4"></textarea>
								</fieldset>',
			'comment_notes_after'  => '',
			'comment_notes_before' => '<p>' . __( 'Hey, so you decided to leave a comment! That\'s great. Just fill in the required fields and hit submit. Note that your comment will need to be reviewed before its published.', 'whisper' ) . '</p>',
		);
		comment_form( $comment_args );
	}
	?>

</div>