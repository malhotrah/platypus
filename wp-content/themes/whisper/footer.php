<footer id="footer">

	<div id="footer-sidebars" class="container_12">
		<?php
		$footer_columns = fitwp_option( 'footer_columns' );
		$class = 'grid_' . ( 12 / $footer_columns );
		?>
		<?php for ( $i = 1; $i <= $footer_columns; $i++ ): ?>
			<div class="footer-widget <?php echo $class; ?>">
				<?php
				if ( !dynamic_sidebar( "footer-$i" ) )
					printf( __( 'This is the Footer Sidebar %s (widget area). Please go to <a href="%s">Appearance &rarr; Widgets</a> to add widgets to this area', 'whisper' ), $i, admin_url( 'widgets.php' ) );
				?>
			</div>
		<?php endfor; ?>
	</div><!-- #footer-sidebars -->

	<div id="footer-text">
		<div class="container_12">
			<?php
			if ( $copyright = fitwp_option( 'footer_copyright' ) )
				echo '<div class="grid_6">' . do_shortcode( $copyright ) . '</div>';

			$phone = fitwp_option( 'footer_info_phone' );
			$email = antispambot( fitwp_option( 'footer_info_email' ) );
			if ( $phone || $email )
			{
				echo '<div id="contact-info" class="grid_6">';
				if ( $phone )
					echo "<span class='phone'>$phone</span>";
				if ( $email )
					echo "<span class='mail'><a href='mailto:$email'>$email</a></span>";
				echo '</div>';
			}
			?>
		</div>
	</div><!-- .copyright-container -->

</footer>

</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>
