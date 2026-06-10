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
		<i data-feather="eye"></i>
	</span>

	<?php /* Estado: flechas drag — lightbox / sliders */ ?>
	<span class="nil-cursor-state nil-cursor-state--drag">
		<span class="nil-cursor-arrow nil-cursor-arrow--prev">
			<i data-feather="arrow-left"></i>
		</span>
		<span class="nil-cursor-arrow nil-cursor-arrow--next">
			<i data-feather="arrow-right"></i>
		</span>
	</span>

</div>
