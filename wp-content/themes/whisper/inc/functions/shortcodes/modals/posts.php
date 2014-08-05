<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Show Section Title?', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="title_wrap" type="checkbox" ng-true-value="1" ng-false-value="" ng-init="title_wrap = '1'">
		</div>
	</div>
	<div class="fitsc-field" ng-show="title_wrap">
		<label class="fitsc-label"><?php _e( 'Section Title', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="title" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Number Of Posts', 'whisper' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="number" ng-init="number = 3">
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
		</div>
	</div>

	<?php include FITSC_INC . 'tpl/advanced.php'; ?>

	<div ng-show="advanced">
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Content Limit (words)', 'whisper' ); ?></label>

			<div class="fitsc-controls">
				<input ng-model="content_limit" type="text">
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'More Text', 'whisper' ); ?></label>

			<div class="fitsc-controls">
				<input ng-model="more" type="text">
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Total Columns', 'whisper' ); ?></label>

			<div class="fitsc-controls">
				<select ng-model="total_columns">
					<?php
					for ( $i = 1; $i <= 12; $i++ )
					{
						echo "<option value='$i'>$i</option>";
					}
					?>
				</select>
				<p class="description"><?php _e( 'What is your content width (in 12-grid columns)? (usually 4, 6, 8, 9, 12)', 'whisper' ); ?></p>
			</div>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'whisper' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'title_wrap', 'title', 'number', 'content_limit', 'more', 'total_columns' );
			$text .= ']';
			echo $text;
			?></pre>
	</div>
</div>
