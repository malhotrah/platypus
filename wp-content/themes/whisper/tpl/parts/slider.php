<?php
if ( !is_singular() || !( $slider = whisper_meta( 'slider' ) ) )
	return;

echo '<div class="slider-container">';
echo do_shortcode( $slider );
echo '</div>';