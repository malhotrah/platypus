<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Type', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="type" ng-init="type = 'big'">
				<?php
				FITSC_Helper::options( array(
					'big'    => __( 'Big', 'whisper' ),
					'hex'    => __( 'Hexagon', 'whisper' ),
					'small'  => __( 'Small', 'whisper' ),
					'simple' => __( 'Simple', 'whisper' ),
				) );
				?>
			</select>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Icon', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<?php FITSC_Helper::icons( 'icon' ); ?>
		</div>
	</div>
	<div class="fitsc-field" ng-show="type != 'simple'">
		<label class="fitsc-label"><?php _e( 'Title', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="title" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Content', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<textarea ng-model="content"></textarea>
		</div>
	</div>
	<div class="fitsc-field" ng-show="type != 'small'">
		<label class="fitsc-label"><?php _e( 'URL', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="url" type="text">
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'type', 'title', 'icon', 'url' );
			$text .= "]{{content}}[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>
