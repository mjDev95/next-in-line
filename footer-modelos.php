

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
		<div class="py-sm">
			<div class="row align-items-center">
				<div class="col-md-3 col-lg-3">
					<button onclick="history.back()" class="nil-btn-back"><?php esc_html_e( 'Regresar', 'hello-elementor-child' ); ?></button>
				</div>

				<div class="col-md-9 text-lg-end">
					<p class="mb-0">
						&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'Todos los derechos reservados', 'hello-elementor-child' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
					</p>
				</div>
			</div>
		</div>
	</footer>

<?php get_template_part( 'template-parts/cursor' ); ?>

<?php wp_footer(); ?>
</body>
</html>