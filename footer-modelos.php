

<?php
/**
 * Footer personalizado para modelos.
 * Se carga mediante get_footer( 'modelos' ).
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer id="site-footer-modelos" class="nil-footer-modelos">
		<div class="container py-sm">
			<div class="row align-items-center">
				<div class="col-6 col-lg-3 mb-md">
					<div class="nil-footer-logo img-fluid">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
						} else {
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-footer-logo-link">
								<?php bloginfo( 'name' ); ?>
							</a>
							<?php
						}
						?>
					</div>
					<p class="mb-0 d-none">
						<?php esc_html_e( 'Agencia internacional de modelos y talento.', 'hello-elementor-child' ); ?>
					</p>
				</div>

				<div class="col-12 col-lg-9 text-lg-end">
					<p class="mb-0">
						© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'Derechos reservados.', 'hello-elementor-child' ); ?>
					</p>
				</div>
			</div>
		</div>
	</footer>

<?php get_template_part( 'template-parts/cursor' ); ?>

<?php wp_footer(); ?>
</body>
</html>