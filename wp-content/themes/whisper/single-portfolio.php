<?php
$display = whisper_meta( 'display' );
if ( !in_array( $display, array( 'default', 'simple' ) ) )
	$display = 'default';
get_template_part( 'tpl/portfolio-single', $display );