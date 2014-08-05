<div class="fitsc-config">
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Text', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="text" ng-init="text = '<?php _e( 'Click me', 'fitsc' ); ?>'" type="text">
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Link', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<input ng-model="link" type="text" ng-init="link = '#'">
			<span class="wp_themeSkin"><span class="mce_link mceIcon"></span></span>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Color', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<?php FITSC_Helper::color_schemes( 'color' );  ?>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Size', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<select ng-model="size">
				<?php
				FITSC_Helper::options( array(
					'small' => __( 'Small', 'fitsc' ),
					''      => __( 'Default', 'fitsc' ),
					'large' => __( 'Large', 'fitsc' ),
				) );
				?>
			</select>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Icon', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<?php FITSC_Helper::icons( 'icon' );  ?>
		</div>
	</div>
	<div class="fitsc-field">
		<label class="fitsc-label"><?php _e( 'Icon Position', 'fitsc' ); ?></label>
		<div class="fitsc-controls">
			<select ng-model="icon_position">
				<?php
				FITSC_Helper::options( array(
					''      => __( 'Left', 'fitsc' ),
					'right' => __( 'Right', 'fitsc' ),
				) );
				?>
			</select>
		</div>
	</div>

	<?php include FITSC_INC . 'tpl/advanced.php'; ?>

	<div ng-show="advanced">
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Align', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<select ng-model="align">
					<?php
					FITSC_Helper::options( array(
						''       => __( 'None', 'fitsc' ),
						'left'   => __( 'Left', 'fitsc' ),
						'center' => __( 'Center', 'fitsc' ),
						'right'  => __( 'Right', 'fitsc' ),
					) );
					?>
				</select>
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Full Width', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<?php FITSC_Helper::checkbox_angular( 'full' ); ?>
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Custom Background Color', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<input ng-model="background" type="text" colorpicker>
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Custom Text Color', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<input ng-model="text_color" type="text" colorpicker>
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Link Target', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<select ng-model="target">
					<?php
					FITSC_Helper::options( array(
						''       => __( 'Default', 'fitsc' ),
						'_blank' => __( 'New window/tab', 'fitsc' ),
					) );
					?>
				</select>
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Nofollow', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<?php FITSC_Helper::checkbox_angular( 'nofollow' ); ?>
			</div>
		</div>

		<hr>

		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'ID', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<input ng-model="id" type="text">
			</div>
		</div>
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Class', 'fitsc' ); ?></label>
			<div class="fitsc-controls">
				<input ng-model="class" type="text">
			</div>
		</div>
	</div>
</div>

<div class="fitsc-preview">
	<div class="fitsc-preview-shortcode">
		<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'fitsc' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode";
			$text .= FITSC_Helper::shortcode_atts(
				'link',
				'color',
				'size',
				'icon',
				'icon_position',

				'align',
				'full',
				'background',
				'text_color',
				'target',
				'nofollow',
				'id',
				'class'
			);
			$text .= "]{{text}}[/$shortcode]";
			echo $text;
			?></pre>
	</div>
</div>
