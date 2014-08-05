<div class="fitsc-config">
	<div class="fitsc-field">

		<label class="fitsc-label"><?php _e( 'Choose Social Network', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="search" type="text" placeholder="<?php _e( 'Search Icon...', 'whisper' ); ?>">
			<div class="fitsc-icons">
				<?php $name = uniqid(); ?>
				<label class="fitsc-icon" ng-repeat="social in socials | filter: search">
					<i class="{{social.class}}"></i>
					<input ng-model="$parent.class" type="radio" name="<?php echo $name; ?>" value="{{social.class}}" class="hidden">
				</label>
			</div>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'URL', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="url" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Title', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="title" type="text">
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'class', 'url', 'title' );
			$text .= ']';
			echo $text;
			?></pre>
	</div>
</div>
