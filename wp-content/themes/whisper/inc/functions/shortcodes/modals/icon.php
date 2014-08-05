<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Icon', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<?php FITSC_Helper::icons( 'class' ); ?>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Size', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="size" size="3"> px
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'class', 'size' );
			$text .= ']';
			echo $text;
			?></pre>
	</div>
</div>
