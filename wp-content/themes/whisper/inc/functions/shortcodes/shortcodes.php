<?php
if ( is_admin() )
	require THEME_DIR . 'inc/functions/shortcodes/admin.php';
else
	require THEME_DIR . 'inc/functions/shortcodes/frontend.php';