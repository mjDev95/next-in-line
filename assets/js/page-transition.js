/* global gsap */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var overlay   = document.getElementById( 'nil-page-transition' );
		if ( ! overlay || typeof gsap === 'undefined' ) { return; }

		// Garantizar que el overlay es el último hijo del body
		// (DOM order desempata cuando dos elementos comparten stacking context)
		document.body.appendChild( overlay );
		overlay.style.zIndex = '100000'; // inline > cualquier regla CSS del tema padre

		var topArc      = overlay.querySelector( '.nil-pt-rounded-wrap.top' );
		var bottomArc   = overlay.querySelector( '.nil-pt-rounded-wrap.bottom' );
		var ARC_HEIGHT  = window.innerWidth > 540 ? '10vh' : '5vh';
		var isAnimating = false;

		gsap.set( [ topArc, bottomArc ], { height: ARC_HEIGHT } );

		// ── ENTER: nueva página cargó — retirar overlay hacia arriba ──────────
		// Si el preloader está activo lo saltamos (él cubre el inicio)
		if ( ! document.documentElement.classList.contains( 'nil-preloader-active' ) ) {
			var tlEnter = gsap.timeline( {
				delay: 0.05,
				onComplete: function () {
					isAnimating = false;
					overlay.style.pointerEvents = 'none';
					gsap.set( overlay, { yPercent: 100 } );
					document.dispatchEvent( new CustomEvent( 'nil:heroReady' ) );
				},
			} );

			if ( topArc ) {
				tlEnter.set( topArc, { scaleY: 0 }, 0 );
			}
			if ( bottomArc ) {
				tlEnter.set( bottomArc, { scaleY: 1 }, 0 );
			}

			tlEnter.to(
				overlay,
				{ yPercent: -100, duration: 0.8, ease: 'power3.inOut' },
				0
			);

			if ( bottomArc ) {
				tlEnter.to(
					bottomArc,
					{ scaleY: 0, duration: 0.85, ease: 'power3.inOut' },
					0.2
				);
			}
		} else {
			// El preloader se encarga de la entrada; mantener overlay oculto
			overlay.style.pointerEvents = 'none';
			document.addEventListener( 'nil:preloaderDone', function () {
				// Fijar inline transform antes de que se elimine la clase CSS (evita flash)
				gsap.set( overlay, { yPercent: 100 } );
			} );
		}

		// ── EXIT: cubrir página antes de navegar ──────────────────────────────
		function navigateTo( url ) {
			if ( isAnimating ) { return; }
			isAnimating = true;
			overlay.style.pointerEvents = 'all';

			var tl = gsap.timeline( {
				onComplete: function () {
					window.location.href = url;
				},
			} );

			if ( topArc ) {
				tl.set( topArc, { scaleY: 0 }, 0 );
			}
			if ( bottomArc ) {
				tl.set( bottomArc, { scaleY: 1 }, 0 );
			}

			tl.set( overlay, { yPercent: 100 }, 0 );

			tl.to(
				overlay,
				{ yPercent: 0, duration: 0.5, ease: 'power4.in' },
				0
			);

			if ( topArc ) {
				tl.to(
					topArc,
					{ scaleY: 1, duration: 0.4, ease: 'power4.in' },
					0
				);
			}
		}

		// Exponer globalmente para que fullscreen-nav.js lo use
		window.nilNavigate = navigateTo;

		// ── Interceptar clicks en enlaces internos ─────────────────────────────
		document.addEventListener( 'click', function ( e ) {
			var link = e.target.closest( 'a[href]' );
			if ( ! link ) { return; }

			if ( e.metaKey || e.ctrlKey || e.shiftKey || e.altKey ) { return; }
			if ( link.target === '_blank' ) { return; }

			var href = link.getAttribute( 'href' );
			if ( ! href ) { return; }

			if (
				href === '#' ||
				href.charAt( 0 ) === '#' ||
				href.indexOf( 'mailto:' ) === 0 ||
				href.indexOf( 'tel:' ) === 0 ||
				href.indexOf( 'javascript:' ) === 0 ||
				href.indexOf( '/wp-admin' ) !== -1 ||
				href.indexOf( '/wp-login' ) !== -1
			) { return; }

			if ( link.hostname && link.hostname !== window.location.hostname ) { return; }

			e.preventDefault();
			navigateTo( link.href );
		} );

		// ── Restaurar desde bfcache (botón atrás/adelante del navegador) ──────
		window.addEventListener( 'pageshow', function ( e ) {
			if ( e.persisted ) {
				isAnimating = false;
				overlay.style.pointerEvents = 'none';
				gsap.fromTo(
					overlay,
					{ yPercent: 0 },
					{
						yPercent: -100,
						duration: 0.8,
						ease: 'power3.inOut',
						onComplete: function () {
							gsap.set( overlay, { yPercent: 100 } );
							document.dispatchEvent( new CustomEvent( 'nil:heroReady' ) );
						},
					}
				);
			}
		} );

	} );

} )();
