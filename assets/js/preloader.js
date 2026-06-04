/* global gsap */
( function () {
	'use strict';

	var preloader = document.getElementById( 'nil-preloader' );

	// No existe (visita previa ocultó el div) o GSAP no está disponible
	if ( ! preloader || typeof gsap === 'undefined' ) { return; }

	var wordEl    = preloader.querySelector( '.nil-pl-word' );
	var bottomArc = preloader.querySelector( '.nil-pl-rounded-wrap.bottom' );
	var ARC_HEIGHT = window.innerWidth > 540 ? '10vh' : '5vh';

	if ( ! wordEl ) { return; }

	// Palabras de la secuencia — ajustar a gusto
	var WORDS = [ 'NEXT', 'IN', 'LINE', 'MANAGEMENT' ];

	// ── Salida del preloader ─────────────────────────────────────────────────

	function exitPreloader() {
		var exitTl = gsap.timeline( {
			onComplete: function () {
				// Notificar primero para que page-transition.js fije el inline transform
				// antes de que removamos la clase (evita flash del overlay)
				document.dispatchEvent( new CustomEvent( 'nil:preloaderDone' ) );

				// Quitar clase que bloqueaba el page-transition overlay
				document.documentElement.classList.remove( 'nil-preloader-active' );

				// Limpiar DOM
				if ( preloader.parentNode ) {
					preloader.parentNode.removeChild( preloader );
				}
			},
		} );

		if ( bottomArc ) {
			exitTl.set( bottomArc, { height: ARC_HEIGHT, scaleY: 1 }, 0 );
		}

		exitTl.to(
			preloader,
			{ yPercent: -100, duration: 0.8, ease: 'power4.inOut' },
			0.1
		);

		if ( bottomArc ) {
			exitTl.to(
				bottomArc,
				{ scaleY: 0, duration: 1, ease: 'power4.inOut' },
				0.1
			);
		}
	}

	// ── Secuencia de texto ───────────────────────────────────────────────────

	var textTl = gsap.timeline( { onComplete: exitPreloader } );

	WORDS.forEach( function ( word, i ) {
		var offset = i * 0.62;

		// Cambiar contenido del span
		textTl.set( wordEl, { innerText: word }, offset );

		// Entrar: desenfoque → nitidez
		textTl.fromTo(
			wordEl,
			{ opacity: 0, filter: 'blur(14px)' },
			{ opacity: 1, filter: 'blur(0px)', duration: 0.22, ease: 'power2.out' },
			offset
		);

		// Salir: nitidez → desenfoque
		var exitOffset = offset + 0.42;
		if ( i < WORDS.length - 1 ) {
			textTl.to(
				wordEl,
				{ opacity: 0, filter: 'blur(10px)', duration: 0.18, ease: 'power2.in' },
				exitOffset
			);
		} else {
			// Última palabra: se apaga suavemente antes del exit
			textTl.to(
				wordEl,
				{ opacity: 0, filter: 'blur(6px)', duration: 0.28, ease: 'power2.in' },
				exitOffset
			);
		}
	} );

} )();
