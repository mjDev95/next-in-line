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

        // ── BLINDAJE DE ALTURA ──
        heroSection.style.height = window.innerHeight + "px";

        // ── SECUESTRO INICIAL DEL HEADER ──
        if (siteBar) {
            gsap.set(siteBar, { yPercent: -100, autoAlpha: 0 });
        }

        // ── ESTADO INICIAL DE TEXTOS (Visibles abajo con zoom editorial) ──
        if (textLeft) {
            gsap.set(textLeft, { y: "32vh", scale: 1.4, transformOrigin: "left bottom", opacity: 1 });
        }
        if (textRight) {
            gsap.set(textRight, { y: "32vh", scale: 1.25, transformOrigin: "right bottom", opacity: 1 });
        }

        // ══════════════════════════════════════════════════════════════════
        // ⚡ CÁLCULO DE GEOMETRÍA PARA LA OPCIÓN B (Encogimiento Real)
        // Mide el molde central con respecto al contenedor padre absoluto
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

        // ⚡ ENCOGIMIENTO REAL: Animamos las propiedades físicas del contenedor
        tlIntro.to(photoTarget, {
            top: targetTop,
            left: targetLeft,
            width: targetWidth,
            height: targetHeight,
            duration: 1.2,
            ease: "power4.inOut"
        }, "start");
        
        // Suavizamos la escala interna de la imagen para acompañar el encogimiento
        if (innerImg) {
            tlIntro.to(innerImg, {
                scale: 1.0, // Regresa a su escala natural
                duration: 1.2,
                ease: "power4.inOut"
            }, "start");
        }

        // Subida y reescalado de textos de los costados
        if (textRight) {
            tlIntro.to(textRight, { y: 0, scale: 1, duration: 0.9, ease: "power3.out" }, "start+=0.2");
        }
        if (textLeft) {
            tlIntro.to(textLeft, { y: 0, scale: 1, duration: 1.0, ease: "power3.out" }, "start+=0.35");
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