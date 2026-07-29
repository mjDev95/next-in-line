/**
 * nil-directions.js
 *
 * Mejora progresiva del botón "Cómo llegar":
 * - Solicita la ubicación del usuario con la Geolocation API.
 * - Si el permiso es concedido, abre Google Maps con ruta desde
 *   la ubicación actual hasta la oficina.
 * - Si el permiso es denegado, ocurre un error, o el navegador
 *   no soporta geolocalización, abre el destino sin origen
 *   (comportamiento por defecto del enlace original).
 *
 * No requiere Google Maps JavaScript API ni API Key.
 * Funciona sin JavaScript deshabilitado (el href del botón
 * sigue apuntando al destino como fallback nativo).
 *
 * @package HelloElementorChild
 * @since   1.0.0
 */

( function () {

	'use strict';

	/** Destino fijo de la oficina (URL-encoded). */
	const DESTINATION =
		'Av.+Insurgentes+Sur+863-Piso+7,+Oficina+01,+N%C3%A1poles,+Benito+Ju%C3%A1rez,+03010+Ciudad+de+M%C3%A9xico,+CDMX';

	/** URL de fallback (sin origen) — idéntica al href original del botón. */
	const FALLBACK_URL =
		'https://www.google.com/maps/dir/?api=1&destination=' + DESTINATION;

	/**
	 * Construye la URL de Google Maps con origen y destino.
	 *
	 * @param {number} lat Latitud del usuario.
	 * @param {number} lng Longitud del usuario.
	 * @returns {string} URL completa de navegación.
	 */
	function buildRouteUrl( lat, lng ) {
		return (
			'https://www.google.com/maps/dir/?api=1' +
			'&origin=' + encodeURIComponent( lat + ',' + lng ) +
			'&destination=' + DESTINATION +
			'&travelmode=driving'
		);
	}

	/**
	 * Abre una URL en una nueva pestaña de forma segura.
	 *
	 * @param {string} url URL a abrir.
	 */
	function openUrl( url ) {
		const a = document.createElement( 'a' );
		a.href     = url;
		a.target   = '_blank';
		a.rel      = 'noopener noreferrer';
		a.click();
	}

	/**
	 * Manejador del clic en el botón "Cómo llegar".
	 * Intercepta el comportamiento por defecto e invoca la Geolocation API.
	 *
	 * @param {MouseEvent} event
	 */
	function handleDirectionsClick( event ) {
		// Solo interceptamos si el navegador soporta geolocalización.
		if ( ! ( 'geolocation' in navigator ) ) {
			// El navegador no soporta geolocalización: el href nativo se encarga.
			return;
		}

		event.preventDefault();

		// Indicador visual de carga (accesibilidad).
		const btn = /** @type {HTMLAnchorElement} */ ( event.currentTarget );
		const originalText = btn.textContent;
		btn.setAttribute( 'aria-busy', 'true' );

		navigator.geolocation.getCurrentPosition(
			// ── Éxito ──────────────────────────────────────────────────────
			function ( position ) {
				btn.setAttribute( 'aria-busy', 'false' );
				const url = buildRouteUrl(
					position.coords.latitude,
					position.coords.longitude
				);
				openUrl( url );
			},
			// ── Error o permiso denegado ────────────────────────────────────
			function () {
				btn.setAttribute( 'aria-busy', 'false' );
				openUrl( FALLBACK_URL );
			},
			{
				enableHighAccuracy: false,
				timeout: 8000,
				maximumAge: 60000,
			}
		);
	}

	/**
	 * Inicializa el módulo cuando el DOM está listo.
	 */
	function init() {
		const btn = document.querySelector( '.nil-btn--directions' );
		if ( ! btn ) {
			return;
		}
		btn.addEventListener( 'click', handleDirectionsClick );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

} )();
