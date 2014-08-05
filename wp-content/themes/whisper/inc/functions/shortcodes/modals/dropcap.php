<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Type', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<select ng-model="type">
				<option value=""><?php _e( 'Normal', 'fitsc' ); ?></option>
				<option value="color"><?php _e( 'Color', 'fitsc' ); ?></option>
			</select>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'fitsc' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'type' );
			$text .= "]%SELECTION%[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>
