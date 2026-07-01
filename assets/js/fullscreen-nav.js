/* global gsap */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		var btn      = document.getElementById( 'nil-hamburger-btn' );
		var nav      = document.getElementById( 'nil-fullscreen-nav' );
		var topItems = nav ? nav.querySelectorAll( '.nil-fn-menu > .menu-item' ) : [];
		var subItems = nav ? nav.querySelectorAll( '.nil-fn-menu .sub-menu .menu-item' ) : [];
		var footer   = nav ? nav.querySelector( '.nil-fn-footer' ) : null;

		// ── Admin bar offset (read actual height from DOM) ──────────────────────
		var adminBar = document.getElementById( 'wpadminbar' );
		if ( adminBar ) {
			var adminH  = adminBar.offsetHeight + 'px';
			var siteBar = document.querySelector( '.nil-site-bar' );
			if ( siteBar ) { siteBar.style.top = adminH; }
			if ( nav )     { nav.style.top     = adminH; }
		}

		if ( ! btn || ! nav ) {
			return;
		}

		function lockScroll() {
			var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

			document.documentElement.style.overflow = 'hidden';
			document.body.style.overflow = 'hidden';

			if ( scrollbarWidth > 0 ) {
				document.body.style.paddingRight = scrollbarWidth + 'px';
			}
		}

		function unlockScroll() {
			document.documentElement.style.overflow = '';
			document.body.style.overflow = '';
			document.body.style.paddingRight = '';
		}

		// ── GSAP timeline ───────────────────────────────────────────────────────

		var pendingUrl = null;
		var isOpen     = false;

		var tl = gsap.timeline( {
			paused: true,

			onStart: function () {
				nav.style.visibility    = 'visible';
				nav.style.pointerEvents = 'all';
				nav.setAttribute( 'aria-hidden', 'false' );
				
			},

			onReverseComplete: function () {
				nav.style.visibility    = 'hidden';
				nav.style.pointerEvents = 'none';
				nav.setAttribute( 'aria-hidden', 'true' );
				
				if ( pendingUrl ) {
					var url = pendingUrl;
					pendingUrl = null;
					if ( typeof window.nilNavigate === 'function' ) {
						window.nilNavigate( url );
					} else {
						window.location.href = url;
					}
				}
			},
		} );

		// 1. Overlay reveals left → right via clip-path
		tl.fromTo(
			nav,
			{ clipPath: 'inset(0 100% 0 0)' },
			{ clipPath: 'inset(0 0% 0 0)', duration: 0.65, ease: 'power3.inOut' }
		);

		// Ejecutar lock al avanzar y unlock al reproducir en reversa.
		tl.to( {}, {
			duration: 0,
			onComplete: lockScroll,
			onReverseComplete: unlockScroll,
		}, 0.58 );

		// 2. Top-level menu items stagger in from left
		if ( topItems.length ) {
			tl.fromTo(
				topItems,
				{ x: -70, opacity: 0 },
				{ x: 0, opacity: 1, duration: 0.55, stagger: 0.09, ease: 'power3.out' },
				'-=0.25'
			);
		}

		// 3. Sub-menu items (slightly faster, smaller offset)
		if ( subItems.length ) {
			tl.fromTo(
				subItems,
				{ x: -30, opacity: 0 },
				{ x: 0, opacity: 1, duration: 0.35, stagger: 0.05, ease: 'power2.out' },
				'-=0.4'
			);
		}

		// 4. Footer fades up
		if ( footer ) {
			tl.fromTo(
				footer,
				{ opacity: 0, y: 18 },
				{ opacity: 1, y: 0, duration: 0.4, ease: 'power2.out' },
				'-=0.3'
			);
		}

		// ── Hamburger line animation (GSAP, synced with tl) ────────────────────

		var lines = btn.querySelectorAll( '.nil-ham-line' );

		if ( lines.length === 3 ) {
			// Insert at position 0 — runs together with the clip-path tween
			tl.to( lines[ 0 ], { y: 10,  rotation: 45,  duration: 0.35, ease: 'power2.inOut' }, 0 )
			  .to( lines[ 1 ], { opacity: 0, scaleX: 0,    duration: 0.2,  ease: 'power2.in'   }, 0 )
			  .to( lines[ 2 ], { y: -10, rotation: -45, duration: 0.35, ease: 'power2.inOut' }, 0 );
		}

		// ── Open / Close ────────────────────────────────────────────────────────

		function openMenu() {
			if ( isOpen ) { return; }
			isOpen = true;
			// Pre-posicionar en estado invisible antes de que el nav se vuelva visible (evita parpadeo)
			gsap.set( topItems, { x: -70, opacity: 0 } );
			gsap.set( subItems, { x: -30, opacity: 0 } );
			if ( footer ) { gsap.set( footer, { opacity: 0, y: 18 } ); }
			btn.setAttribute( 'aria-expanded', 'true' );
			btn.classList.add( 'is-open' );
			tl.restart();
		}

		function closeMenu() {
			if ( ! isOpen ) { return; }

			isOpen = false;
			btn.setAttribute( 'aria-expanded', 'false' );
			btn.classList.remove( 'is-open' );
			tl.reverse();
		}

		// ── Close buttons (× and footer) ──────────────────────────────────────
		var closeBtns = nav.querySelectorAll( '.nil-fn-close, .nil-js-close-trigger' );
		if ( closeBtns.length ) {
			closeBtns.forEach( function ( btn ) {
				btn.addEventListener( 'click', closeMenu );
			} );
		}

		btn.addEventListener( 'click', function () {
			if ( isOpen ) {
				closeMenu();
			} else {
				openMenu();
			}
		} );

		// Close on Escape
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && isOpen ) {
				closeMenu();
			}
		} );

		// Close (with reverse animation) when a nav link is followed
		nav.querySelectorAll( '.nil-fn-menu a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var href = this.getAttribute( 'href' );
				// Let browser handle modifier-key clicks, anchor-only links, empty hrefs
				if ( ! href || href === '#' || href.charAt( 0 ) === '#' ||
					 e.metaKey || e.ctrlKey || e.shiftKey || e.altKey ) {
					closeMenu();
					return;
				}
				e.preventDefault();
				pendingUrl = href;
				closeMenu();
			} );
		} );

	} );
} )();
