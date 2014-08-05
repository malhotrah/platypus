<?php do_action( 'whisper_entry_top' ); ?>

<div class="post-body-container">
	<?php whisper_format_icon( true ); ?>

	<div class="post-body">
		<?php whisper_entry_title(); ?>
		<?php whisper_entry_info(); ?>
		<?php whisper_entry_content(); ?>
		<?php whisper_entry_meta(); ?>
	</div>
</div>

<?php do_action( 'whisper_entry_bottom' ); ?>