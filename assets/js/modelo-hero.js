/* global gsap, ScrollTrigger */
(function () {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        const heroWrapper = document.querySelector(".nil-hero-scroll-wrapper");
        const heroSection = document.querySelector(".nil-modelo-hero"); 
        const photoTarget = document.querySelector(".nil-modelo-photo-target");
        const innerImg = photoTarget ? photoTarget.querySelector("img") : null;
        const textLeft = document.querySelector(".nil-modelo-hero-left");
        const textRight = document.querySelector(".nil-modelo-hero-right");
        const siteBar = document.querySelector(".nil-site-bar");
        const photoBox = document.querySelector(".nil-modelo-photo-box");
        const statsWrapper = document.querySelector(".nil-hero-stats-wrapper");

        if (!heroWrapper || !photoTarget || !photoBox || !heroSection) return;

        gsap.registerPlugin(ScrollTrigger);

        // ── SOLUCIÓN 1: BLINDAJE DE ALTURA RESPONSIVO ──
        // En escritorio forzamos el alto. En móvil usamos minHeight para que el contenido no se encime.
        if (window.innerWidth > 768) {
            heroSection.style.height = window.innerHeight + "px";
        } else {
            // 100svh respeta la barra de navegación dinámica en iOS/Android
            heroSection.style.minHeight = "100svh"; 
        }

        // ── SECUESTRO INICIAL DEL HEADER ──
        if (siteBar) {
            gsap.set(siteBar, { yPercent: -100, autoAlpha: 0 });
        }

        // Capturamos los H1 para animar el color sin afectar los transforms del contenedor padre
        const h1Left = textLeft ? textLeft.querySelector("h1") : null;
        const h1Right = textRight ? textRight.querySelector("h1") : null;

        // ── SOLUCIÓN 2: ESTADO INICIAL DE TEXTOS (Visibles abajo, con zoom y en BLANCO) ──
        if (textLeft) {
            gsap.set(textLeft, { y: "32vh", scale: 1.4, transformOrigin: "left bottom", opacity: 1 });
            if (h1Left) gsap.set(h1Left, { color: "#ffffff" });
        }
        if (textRight) {
            gsap.set(textRight, { y: "32vh", scale: 1.25, transformOrigin: "right bottom", opacity: 1 });
            if (h1Right) gsap.set(h1Right, { color: "#ffffff" });
        }

        // ══════════════════════════════════════════════════════════════════
        // ⚡ CÁLCULO DE GEOMETRÍA PARA LA OPCIÓN B (Encogimiento Real)
        // ══════════════════════════════════════════════════════════════════
        const heroRect = heroSection.getBoundingClientRect();
        const boxRect = photoBox.getBoundingClientRect();

        const targetTop    = boxRect.top - heroRect.top;
        const targetLeft   = boxRect.left - heroRect.left;
        const targetWidth  = boxRect.width;
        const targetHeight = boxRect.height;

        // Aseguramos el estado inicial a pantalla completa antes de arrancar
        gsap.set(photoTarget, {
            position: "absolute",
            top: 0,
            left: 0,
            width: "100%",
            height: "100%"
        });

        // ══════════════════════════════════════════════════════════════════
        // 1. INTRO CINEMÁTICA AUTOMÁTICA
        // ══════════════════════════════════════════════════════════════════
        const tlIntro = gsap.timeline({
            delay: 1 
        });

        // ⚡ ENCOGIMIENTO REAL
        tlIntro.to(photoTarget, {
            top: targetTop,
            left: targetLeft,
            width: targetWidth,
            height: targetHeight,
            duration: 1.2,
            ease: "power4.inOut"
        }, "start");
        
        if (innerImg) {
            tlIntro.to(innerImg, {
                scale: 1.0, 
                duration: 1.2,
                ease: "power4.inOut"
            }, "start");
        }

        // Subida, reescalado de textos y TRANSICIÓN DE COLOR a negro
        if (textRight) {
            tlIntro.to(textRight, { y: 0, scale: 1, duration: 0.9, ease: "power3.out" }, "start+=0.2");
            if (h1Right) {
                tlIntro.to(h1Right, { color: "#000000", duration: 0.9, ease: "power3.out" }, "start+=0.2");
            }
        }
        if (textLeft) {
            tlIntro.to(textLeft, { y: 0, scale: 1, duration: 1.0, ease: "power3.out" }, "start+=0.35");
            if (h1Left) {
                tlIntro.to(h1Left, { color: "#000000", duration: 1.0, ease: "power3.out" }, "start+=0.35");
            }
        }

        // Aparición de las estadísticas
        if (statsWrapper) {
            tlIntro.to(statsWrapper, { opacity: 1, y: 0, duration: 1.0, ease: "power3.out" }, "start+=0.5");
        }

        // ── TRIGGER DEL HEADER ──
        if (siteBar) {
            ScrollTrigger.create({
                trigger: heroWrapper,
                start: "bottom top", 
                invalidateOnRefresh: true,
                onEnter: () => gsap.to(siteBar, { yPercent: 0, autoAlpha: 1, duration: 0.6, ease: "power3.out" }),
                onLeaveBack: () => gsap.to(siteBar, { yPercent: -100, autoAlpha: 0, duration: 0.4, ease: "power2.in" })
            });
        }
    });
})();