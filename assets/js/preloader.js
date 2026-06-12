/* global gsap */
( function () {
    'use strict';

    var preloader = document.getElementById( 'nil-preloader' );

    if ( ! preloader || typeof gsap === 'undefined' ) { return; }

    var bottomArc = preloader.querySelector( '.nil-pl-rounded-wrap.bottom' );
    var logoEl    = preloader.querySelector( '.nil-pl-logo' );
    var ARC_HEIGHT = window.innerWidth > 540 ? '10vh' : '5vh';

    // ⚡ 1. INICIALIZACIÓN ABSOLUTA DE GSAP
    // Le decimos a GSAP que cuelgue la curva por debajo de la pantalla (99%)
    // Esto evita cualquier bug de lectura de matrices CSS.
    if ( bottomArc ) {
        gsap.set( bottomArc, { 
            height: ARC_HEIGHT, 
            yPercent: 99, 
            scaleY: 1, 
            transformOrigin: "top center" 
        });
    }

    var isPageLoaded = false;

    window.addEventListener( 'load', function() {
        isPageLoaded = true;
    } );

    if ( document.readyState === 'complete' ) {
        isPageLoaded = true;
    }

    function exitPreloader() {
        var plLogoImg = preloader.querySelector( 'img' ) || preloader.querySelector( 'svg' );
        var headerLogoImg = document.querySelector( '.nil-home-logo img' );

        var exitTl = gsap.timeline( {
            delay: 0.05, 
            onComplete: function () {
                if (headerLogoImg) { gsap.set(headerLogoImg, { opacity: 1, visibility: 'visible' }); }
                if (plLogoImg) { gsap.set(plLogoImg, { visibility: 'hidden' }); }

                document.dispatchEvent( new CustomEvent( 'nil:preloaderDone' ) );
                document.documentElement.classList.remove( 'nil-preloader-active' );

                if ( preloader.parentNode ) {
                    preloader.parentNode.removeChild( preloader );
                }
                
                if ( plLogoImg && plLogoImg.parentNode === document.body ) {
                    plLogoImg.parentNode.removeChild( plLogoImg );
                }
            },
        } );

        if ( logoEl ) { 
            gsap.killTweensOf( logoEl ); 
            gsap.set( logoEl, { opacity: 1 } );
        }

        if ( plLogoImg && headerLogoImg ) {
            var plRect = plLogoImg.getBoundingClientRect();
            var headRect = headerLogoImg.getBoundingClientRect();

            document.body.appendChild( plLogoImg );
            
            gsap.set( plLogoImg, {
                position: 'fixed',
                top: plRect.top,
                left: plRect.left,
                width: plRect.width,
                height: plRect.height,
                margin: 0,
                zIndex: 10001,
                x: 0,
                y: 0,
                scale: 1,
                transformOrigin: "top left"
            });

            var moveX = headRect.left - plRect.left;
            var moveY = headRect.top - plRect.top;
            var scaleTarget = headRect.width / plRect.width;

            exitTl.to( plLogoImg, {
                x: moveX,
                y: moveY,
                scale: scaleTarget,
                duration: 0.8,
                ease: 'power3.inOut'
            }, 0 ); 
        }

        // ⚡ 2. LA CURVA SE APLANA
        if ( bottomArc ) {
            // Ya no usamos "set" aquí porque lo hicimos al inicio. 
            // Solo animamos la escala a 0 exactamente como en page-transition.
            exitTl.to( bottomArc, { scaleY: 0, duration: 0.85, ease: 'power3.inOut' }, 0.2 );
        }

        exitTl.to(
            preloader,
            { yPercent: -100, duration: 0.8, ease: 'power3.inOut' },
            0 
        );
    }

    if ( logoEl ) {
        gsap.fromTo( logoEl, 
            { opacity: 0.35 }, 
            { opacity: 1, duration: 1.2, yoyo: true, repeat: -1, ease: 'power1.inOut' } 
        );
    }

    function checkLoadState() {
        if ( isPageLoaded ) {
            exitPreloader();
        } else {
            setTimeout( checkLoadState, 100 );
        }
    }

    setTimeout( checkLoadState, 1000 );

} )();