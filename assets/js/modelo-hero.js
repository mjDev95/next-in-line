/* global gsap, ScrollTrigger */
(function () {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        const heroWrapper = document.querySelector(".nil-hero-scroll-wrapper");
        const heroSection = document.querySelector(".nil-modelo-hero"); // ⚡ Llamamos al contenedor
        const photoTarget = document.querySelector(".nil-modelo-photo-target");
        const innerImg = photoTarget ? photoTarget.querySelector("img") : null;
        const textLeft = document.querySelector(".nil-modelo-hero-left");
        const textRight = document.querySelector(".nil-modelo-hero-right");
        const siteBar = document.querySelector(".nil-site-bar");
        const photoBox = document.querySelector(".nil-modelo-photo-box");

        if (!heroWrapper || !photoTarget || !photoBox) return;

        gsap.registerPlugin(ScrollTrigger);

        // ══════════════════════════════════════════════════════════════════
        // ⚡ BLINDAJE DE ALTURA: Congelar en píxeles absolutos
        // Esto mata el efecto de "estiramiento" al hacer scroll
        // ══════════════════════════════════════════════════════════════════
        if (heroSection) {
            heroSection.style.height = window.innerHeight + "px";
        }

        // ── SECUESTRO INICIAL DEL HEADER ──
        if (siteBar) {
            gsap.set(siteBar, { yPercent: -100, autoAlpha: 0 });
        }

        // ⚡ ESTADO INICIAL (Visibles abajo con zoom masivo editorial)
        if (textLeft) {
            gsap.set(textLeft, { 
                y: "32vh", 
                scale: 1.4,             
                transformOrigin: "left bottom", 
                opacity: 1  
            });
        }

        if (textRight) {
            gsap.set(textRight, { 
                y: "32vh", 
                scale: 1.25,            
                transformOrigin: "right bottom", 
                opacity: 1  
            });
        }

        // ⚡ Matemáticas relativas al contenedor (Coordenadas Locales)
        function getClipPathTarget() {
            const boxRect = photoBox.getBoundingClientRect();
            const targetRect = photoTarget.getBoundingClientRect();

            const top = boxRect.top - targetRect.top;
            const left = boxRect.left - targetRect.left;
            const right = targetRect.right - boxRect.right;
            const bottom = targetRect.bottom - boxRect.bottom;

            return `inset(${top}px ${right}px ${bottom}px ${left}px)`;
        }

        // ══════════════════════════════════════════════════════════════════
        // 1. INTRO CINEMÁTICA AUTOMÁTICA
        // ══════════════════════════════════════════════════════════════════
        const tlIntro = gsap.timeline({
            delay: 1 
        });

        // Recorte automático de la foto (Fijado)
        tlIntro.to(photoTarget, {
            clipPath: () => getClipPathTarget(), 
            duration: 1.2,
            ease: "power4.inOut"
        }, "start");
        
        if (innerImg) {
            tlIntro.to(innerImg, {
                yPercent: -8,   
                scale: 1.0,     
                duration: 1.2,
                ease: "power4.inOut"
            }, "start");
        }

        // Subida de textos
        if (textRight) {
            tlIntro.to(textRight, { y: 0, scale: 1, duration: 0.9, ease: "power3.out" }, "start+=0.2");
        }
        if (textLeft) {
            tlIntro.to(textLeft, { y: 0, scale: 1, duration: 1.0, ease: "power3.out" }, "start+=0.35");
        }

        // ══════════════════════════════════════════════════════════════════
        // 2. TRIGGER DEL HEADER
        // ══════════════════════════════════════════════════════════════════
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