<div class="fitsc-config">
	<div class="fitsc-field">

		<label class="fitsc-label"><?php _e( 'Social Network', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="network">
				<?php
				$options = array(
					'pinterest'  => __( 'Pinterest', 'whisper' ),
					'deviantart' => __( 'Deviant Art', 'whisper' ),
					'flickr'     => __( 'Flickr', 'whisper' ),
					'dribbble'   => __( 'Dribbble', 'whisper' ),
					'youtube'    => __( 'Youtube', 'whisper' ),
					'newsfeed'   => __( 'Newsfeed', 'whisper' ),
					'instagram'  => __( 'Instagram', 'whisper' ),
				);
				FITSC_Helper::options( $options );
				?>
			</select>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Username', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="username" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Limit', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="limit" type="number">
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'network', 'username', 'limit' );
			$text .= ']';
			echo $text;
			?></pre>
	</div>
</div>
