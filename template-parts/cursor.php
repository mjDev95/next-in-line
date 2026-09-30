<?php
/**
 * Template Part: Cursor personalizado global.
 *
 * Incluir en los footers donde se necesite el cursor interactivo:
 *   get_template_part( 'template-parts/cursor' );
 *
 * El sistema de estados es controlado por assets/js/nil-cursor.js
 * y los estilos viven en assets/css/global.css.
 *
 * ── ESTADOS DISPONIBLES (data-state) ───────────────────────
 *   "eye"  → Ícono de ojo.    Trigger: hover sobre .nil-gallery-item
 *   "drag" → Flechas ‹ ›.    Trigger: lightbox / sliders abiertos
 *
 * ── AÑADIR UN NUEVO ESTADO EN EL FUTURO ────────────────────
 *   1. HTML:  Añade un <span class="nil-cursor-state nil-cursor-state--nombre"> aquí.
 *   2. CSS:   Añade los estilos para #nil-custom-cursor[data-state="nombre"] en global.css.
 *   3. JS:    Llama NilCursor.show('nombre') desde el script correspondiente,
 *             o usa NilCursor.register('.mi-selector', 'nombre') para registrar una zona hover.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="nil-custom-cursor" aria-hidden="true">

	<?php /* Estado: ojo — galería de fotos */ ?>
	<span class="nil-cursor-state nil-cursor-state--eye">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
	</span>

	<?php /* Estado: flechas drag — lightbox / sliders */ ?>
	<span class="nil-cursor-state nil-cursor-state--drag">
		<span class="nil-cursor-arrow nil-cursor-arrow--prev">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
		</span>
		<span class="nil-cursor-arrow nil-cursor-arrow--next">
			<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
		</span>
	</span>

</div>
