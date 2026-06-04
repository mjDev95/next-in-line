/* global gsap */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		if ( typeof gsap === 'undefined' ) { return; }

		var hero     = document.querySelector( '.nil-home' );
		var logo     = document.querySelector( '.nil-home-logo' );
		var panels   = document.querySelectorAll( '.nil-cat-panel' );
		var contents = document.querySelectorAll( '.nil-cat-content' );

		if ( ! hero || ! contents.length ) { return; }

		panels.forEach( function ( panel ) {
			panel.addEventListener( 'click', function ( e ) {
				var href = panel.getAttribute( 'href' );
				if (
					! href ||
					href === '#' ||
					href.charAt( 0 ) === '#' ||
					e.metaKey ||
					e.ctrlKey ||
					e.shiftKey ||
					e.altKey ||
					panel.target === '_blank'
				) {
					return;
				}

				if ( panel.hostname && panel.hostname !== window.location.hostname ) {
					return;
				}

				e.preventDefault();
				e.stopPropagation();

				if ( typeof window.nilNavigate === 'function' ) {
					window.nilNavigate( panel.href );
				} else {
					window.location.href = panel.href;
				}
			}, true );
		} );

		// Estado inicial: la cortina/preloader cubre la página, así evitamos flash.
		if ( logo ) {
			gsap.set( logo, { opacity: 0, y: 24 } );
		}
		gsap.set( contents, { opacity: 0, y: 36 } );

		function animateHero() {
			if ( hero.classList.contains( 'is-animated-in' ) ) { return; }
			hero.classList.add( 'is-animated-in' );

			var tl = gsap.timeline( { defaults: { duration: 0.9, ease: 'expo.out' } } );

			if ( logo ) {
				tl.to(
					logo,
					{
						opacity: 1,
						y: 0,
					},
					0
				);
			}

			tl.to(
				contents,
				{
					opacity: 1,
					y: 0,
					stagger: 0.08,
				},
				0.14
			);
		}

		// Caso 1: visita directa a la home (preloader termina)
		document.addEventListener( 'nil:preloaderDone', animateHero );

		// Caso 2: navegar de vuelta a la home desde otra página (page transition termina)
		document.addEventListener( 'nil:heroReady', animateHero );

	} );

} )();
