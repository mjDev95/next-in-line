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

        // ── SECUESTRO INICIAL DEL HEADER ──
        if (siteBar) {
            gsap.set(siteBar, { yPercent: -100, autoAlpha: 0 });
        }

        // Capturamos los H1
        const h1Left = textLeft ? textLeft.querySelector("h1") : null;
        const h1Right = textRight ? textRight.querySelector("h1") : null;

        // ── ESTADO INICIAL DE TEXTOS (Visibles abajo, con zoom y en BLANCO) ──
        if (textLeft) {
            gsap.set(textLeft, { y: "32vh", scale: 1.4, transformOrigin: "left bottom", opacity: 1 });
            if (h1Left) gsap.set(h1Left, { color: "#ffffff" });
        }
        if (textRight) {
            gsap.set(textRight, { y: "32vh", scale: 1.25, transformOrigin: "right bottom", opacity: 1 });
            if (h1Right) gsap.set(h1Right, { color: "#ffffff" });
        }

        // La foto SIEMPRE debe ser absoluta para iniciar
        gsap.set(photoTarget, {
            position: "absolute",
            top: 0,
            left: 0,
            width: "100%",
            height: "100%",
            zIndex: 1 
        });

        // ══════════════════════════════════════════════════════════════════
        // ⚡ CÁLCULO DE GEOMETRÍA DINÁMICO (Funciones en lugar de valores estáticos)
        // ══════════════════════════════════════════════════════════════════
        const getTargetTop    = () => photoBox.getBoundingClientRect().top - heroSection.getBoundingClientRect().top;
        const getTargetLeft   = () => photoBox.getBoundingClientRect().left - heroSection.getBoundingClientRect().left;
        const getTargetWidth  = () => photoBox.getBoundingClientRect().width;
        const getTargetHeight = () => photoBox.getBoundingClientRect().height;

        // ══════════════════════════════════════════════════════════════════
        // 1. INTRO CINEMÁTICA AUTOMÁTICA
        // ══════════════════════════════════════════════════════════════════
        const tlIntro = gsap.timeline({ delay: 1 });

        // ⚡ ENCOGIMIENTO REAL (Pasamos las funciones para que lea los valores actuales)
        tlIntro.to(photoTarget, {
            top: getTargetTop,
            left: getTargetLeft,
            width: getTargetWidth,
            height: getTargetHeight,
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

        // Subida, reescalado y transiciones de color
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

        // Aparición de estadísticas
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

        // ══════════════════════════════════════════════════════════════════
        // 🛠️ ADAPTACIÓN RESPONSIVA AL CAMBIAR TAMAÑO DE PANTALLA
        // ══════════════════════════════════════════════════════════════════

        let resizeTimer;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (tlIntro.progress() === 1) {
                    // ⚡ CAMBIO: Usamos gsap.to en lugar de gsap.set para interpolar suavemente
                    gsap.to(photoTarget, {
                        top: getTargetTop(),
                        left: getTargetLeft(),
                        width: getTargetWidth(),
                        height: getTargetHeight(),
                        duration: 0.5,        // Medio segundo de transición
                        ease: "power3.out"    // Curva suave que frena al llegar
                    });
                }
            }, 150);
        });

    });
})();