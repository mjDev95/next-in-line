<?php
/**
 * Preloader inicial — solo Home, primera visita por sesión.
 * El JS comprueba sessionStorage; si ya visitó, la clase nil-preloader-skip
 * está en <html> (inyectada por nil_head_inline_script) y el preloader
 * se oculta antes del primer paint.
 */
if ( ! is_front_page() ) {
	return;
}

$logo_id = (int) get_option( 'nil_header_logo_id', 0 );

// Fallback al logo personalizado de WordPress si la opción del tema está vacía.
if ( ! $logo_id ) {
	$logo_id = (int) get_theme_mod( 'custom_logo', 0 );
}

$logo_src = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
?>
<div id="nil-preloader" aria-hidden="true" role="presentation">

	<div class="nil-pl-logo" aria-hidden="true">
		<?php if ( $logo_src ) : ?>
			<img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php else : ?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 46" fill="none" aria-hidden="true">
				<path d="M4 6 L4 40 L30 6 L30 40" stroke="white" stroke-width="5"
				      stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		<?php endif; ?>
	</div>

	<div class="nil-pl-text-wrap" aria-hidden="true">
		<span class="nil-pl-word"></span>
	</div>

	<div class="nil-pl-rounded-wrap bottom" aria-hidden="true">
		<div class="nil-pl-rounded"></div>
	</div>

</div>
