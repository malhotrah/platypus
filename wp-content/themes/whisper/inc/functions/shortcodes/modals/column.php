<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Span', 'fitsc' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="span">
				<?php
				for ( $i = 1; $i <= 12; $i++ )
				{
					echo "<option value='$i'>$i</option>";
				}
				?>
			</select>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Custom Class', 'fitsc' ); ?></label>

		<div class="fitsc-controls">
			<input ng-model="class" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Total columns (optional)', 'fitsc' ); ?></label>

		<div class="fitsc-controls">
			<select ng-model="total" ng-init="total = 12">
				<?php
				for ( $i = 1; $i <= 12; $i++ )
				{
					echo "<option value='$i'>$i</option>";
				}
				?>
			</select>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'fitsc' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts( 'span', 'class', 'total' );
			$text .= "]%SELECTION%[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>