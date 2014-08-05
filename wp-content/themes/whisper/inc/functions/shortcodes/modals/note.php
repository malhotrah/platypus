<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Type', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<select ng-model="type">
				<option value=""><?php _e( 'No Icon', 'whisper' ); ?></option>
				<option value="icon"><?php _e( 'With Icon', 'whisper' ); ?></option>
			</select>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Content', 'whisper' ); ?></label>
		<div class="fitsc-controls">
			<textarea ng-model="content"></textarea>
			<p class="description"><?php _e( 'You can use HTML here as well.', 'whisper' ); ?></p>
			<p class="description"><?php _e( 'Use &lt;span class="text-color"&gt;TEXT&lt;/span&gt; to highlight text.', 'whisper' ); ?></p>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'type' );
			$text .= "]{{content}}[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>
