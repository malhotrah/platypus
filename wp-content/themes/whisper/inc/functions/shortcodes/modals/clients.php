<div ng-controller="Client">

	<div class="fitsc-config">
		<div class="fitsc-field">
			<label class="fitsc-label"><?php _e( 'Title', 'fitsc' ); ?></label>

			<div class="fitsc-controls">
				<input ng-model="title" type="text">
			</div>
		</div>
		<div ng-repeat="block in blocks">
			<h3><?php _e( 'Edit Client', 'fitsc' ); ?></h3>

			<div class="fitsc-field">
				<label class="fitsc-label"><?php _e( 'Name', 'fitsc' ); ?></label>

				<div class="fitsc-controls">
					<input ng-model="block.name" type="text">
				</div>
			</div>
			<div class="fitsc-field">
				<label class="fitsc-label"><?php _e( 'URL', 'fitsc' ); ?></label>

				<div class="fitsc-controls">
					<input ng-model="block.url" type="text">
				</div>
			</div>
			<div class="fitsc-field">
				<label class="fitsc-label"><?php _e( 'Image', 'fitsc' ); ?></label>

				<div class="fitsc-controls">
					<input ng-model="block.image" type="text">
				</div>
			</div>
			<div class="fitsc-field">
				<label class="fitsc-label"><?php _e( 'Intro', 'fitsc' ); ?></label>

				<div class="fitsc-controls">
					<textarea ng-model="block.intro"></textarea>
				</div>
			</div>
		</div>

		<a ng-click="add()" href="#" class="button"><?php _e( 'Add Client', 'fitsc' ); ?></a>
	</div>

	<div class="fitsc-preview">
		<div class="fitsc-preview-shortcode">
			<h4 class="fitsc-heading"><?php _e( 'Shortcode Preview', 'fitsc' ); ?></h4>
		<pre class="fitsc-preview-content fitsc-shortcode"><?php
			$text = "[$shortcode title={{title}}]";
			$text .= '<div ng-repeat="block in blocks">';
			$text .= '[client name="{{block.name}}" url="{{block.url}}" image="{{block.image}}"]{{block.intro}}[/client]';
			$text .= '</div>';
			$text .= "[/$shortcode]";
			echo $text;
			?></pre>
		</div>
	</div>

</div>