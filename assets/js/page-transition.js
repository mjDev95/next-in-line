/* global gsap */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var overlay = document.getElementById( 'nil-page-transition' );
		if ( ! overlay || typeof gsap === 'undefined' ) { return; }

		var DUR_ENTER  = 0.6;  // reveal: overlay sale hacia la derecha
		var DUR_EXIT   = 0.5;  // cover:  overlay entra desde la izquierda
		var EASE       = 'power3.inOut';
		var isAnimating = false;

		// ── Admin bar offset (lee altura real del DOM) ─────────────────────────
		var adminBar = document.getElementById( 'wpadminbar' );
		if ( adminBar ) {
			overlay.style.top = adminBar.offsetHeight + 'px';
		}

		// ── ENTER: nueva página cargó — retira el overlay hacia la derecha ─────
		gsap.fromTo(
			overlay,
			{ clipPath: 'inset(0 0% 0 0)' },
			{
				clipPath: 'inset(0 0% 0 100%)',
				duration: DUR_ENTER,
				ease: EASE,
				delay: 0.05,
				onComplete: function () {
					isAnimating = false;
					overlay.style.pointerEvents = 'none';
				},
			}
		);

		// ── EXIT: cubre la página antes de navegar ─────────────────────────────
		function navigateTo( url ) {
			if ( isAnimating ) { return; }
			isAnimating = true;
			overlay.style.pointerEvents = 'all';

			gsap.fromTo(
				overlay,
				{ clipPath: 'inset(0 100% 0 0)' },
				{
					clipPath: 'inset(0 0% 0 0)',
					duration: DUR_EXIT,
					ease: EASE,
					onComplete: function () {
						window.location.href = url;
					},
				}
			);
		}

		// Exponer globalmente para que fullscreen-nav.js lo use
		window.nilNavigate = navigateTo;

		// ── Interceptar clicks en enlaces internos ─────────────────────────────
		document.addEventListener( 'click', function ( e ) {
			var link = e.target.closest( 'a[href]' );
			if ( ! link ) { return; }

			// Dejar pasar clicks con teclas modificadoras (abrir en nueva pestaña etc.)
			if ( e.metaKey || e.ctrlKey || e.shiftKey || e.altKey ) { return; }

			// Dejar pasar enlaces que abren en nueva pestaña
			if ( link.target === '_blank' ) { return; }

			var href = link.getAttribute( 'href' );
			if ( ! href ) { return; }

			// Dejar pasar anclas, protocolos especiales y rutas del admin de WP
			if (
				href === '#' ||
				href.charAt( 0 ) === '#' ||
				href.indexOf( 'mailto:' ) === 0 ||
				href.indexOf( 'tel:' ) === 0 ||
				href.indexOf( 'javascript:' ) === 0 ||
				href.indexOf( '/wp-admin' ) !== -1 ||
				href.indexOf( '/wp-login' ) !== -1
			) { return; }

			// Dejar pasar enlaces externos
			if ( link.hostname && link.hostname !== window.location.hostname ) { return; }

			e.preventDefault();
			navigateTo( link.href ); // .href es la URL absoluta
		} );

		// ── Restaurar desde bfcache (botón atrás/adelante del navegador) ──────
		window.addEventListener( 'pageshow', function ( e ) {
			if ( e.persisted ) {
				isAnimating = false;
				overlay.style.pointerEvents = 'none';
				gsap.fromTo(
					overlay,
					{ clipPath: 'inset(0 0% 0 0)' },
					{ clipPath: 'inset(0 0% 0 100%)', duration: DUR_ENTER, ease: EASE }
				);
			}
		} );

	} );

} )();
