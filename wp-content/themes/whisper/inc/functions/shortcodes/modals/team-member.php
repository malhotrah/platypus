<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Name', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="name" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Position', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="position" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Photo URL', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="photo" type="url">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Biography', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<textarea ng-model="bio"></textarea>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Phone', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="phone" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Email', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="email" type="email">
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'name', 'position', 'photo', 'phone', 'email' );
			$text .= "]{{bio}}[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>
