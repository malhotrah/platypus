<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">

	<title><?php wp_title( '-', true, 'right' ); ?></title>

	<!--[if lt IE 9]>
	<script src="//cdn.jsdelivr.net/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="//cdn.jsdelivr.net/respond/1.3.0/respond.min.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="wrapper">

		<header id="header">
			<div class="clearfix inner">
				<?php $tag = is_front_page() ? 'h1' : 'div'; ?>
				<<?php echo $tag; ?> id="logo" class="left">
					<a href="<?php echo HOME_URL; ?>" title="<?php esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php
						$logo = fitwp_option( 'logo' );
						if ( !$logo )
							$logo = THEME_URL . 'img/logo.png';

						$size = '';
						if ( $width = fitwp_option( 'logo_width' ) )
							$size .= " width='$width'";
						if ( $height = fitwp_option( 'logo_height' ) )
							$size .= " height='$height'";

						echo "<img alt='logo' src='$logo'$size>";
						?>
					</a>
				</<?php echo $tag; ?>>

				<div id="nav-container" class="right">

					<?php do_action( 'whisper_before_nav' ); ?>

					<nav id="nav">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container'      => '',
						) );
						?>
					</nav>

					<?php do_action( 'whisper_after_nav' ); ?>
				</div>
			</div>
		</header>

		<div class="top-shadow"></div>