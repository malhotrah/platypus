<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Type', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="type" ng-init="type = ''">
				<?php
				FITSC_Helper::options( array(
					''     => __( 'Simple', 'whisper' ),
					'icon' => __( 'Icon', 'whisper' ),
				) );
				?>
			</select>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'type' );
			$text .= ']';
			echo $text;
			?></pre>
	</div>
</div>
