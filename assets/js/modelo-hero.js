/* global gsap, ScrollTrigger */
(function () {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        const heroWrapper = document.querySelector(".nil-hero-scroll-wrapper");
        const photoTarget = document.querySelector(".nil-modelo-photo-target");
        const innerImg = photoTarget ? photoTarget.querySelector("img") : null;
        const textLeft = document.querySelector(".nil-modelo-hero-left");
        const textRight = document.querySelector(".nil-modelo-hero-right");
        const siteBar = document.querySelector(".nil-site-bar");
        const photoBox = document.querySelector(".nil-modelo-photo-box");

        if (!heroWrapper || !photoTarget || !photoBox) return;

        gsap.registerPlugin(ScrollTrigger);

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

        // FUNCIÓN DE RECORTE: Mide la columna real del sistema grid
        function getClipPathTarget() {
            const rect = photoBox.getBoundingClientRect();
            return `inset(${rect.top}px ${window.innerWidth - rect.right}px ${window.innerHeight - rect.bottom}px ${rect.left}px)`;
        }

        // ══════════════════════════════════════════════════════════════════
        // ⚡ 1. INTRO CINEMÁTICA AUTOMÁTICA (Con 1 segundo de Delay)
        // ══════════════════════════════════════════════════════════════════
        const tlIntro = gsap.timeline({
            delay: 1 // ⚡ CLAVE: La página carga, se mantiene estática 1 segundo y luego despega sola
        });

        // Recorte automático de la foto
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

        // Subida de textos y contra-zoom automático
        if (textRight) {
            tlIntro.to(textRight, { y: 0, scale: 1, duration: 0.9, ease: "power3.out" }, "start+=0.2");
        }
        if (textLeft) {
            tlIntro.to(textLeft, { y: 0, scale: 1, duration: 1.0, ease: "power3.out" }, "start+=0.35");
        }


        // ══════════════════════════════════════════════════════════════════
        // 2. TRIGGER DEL HEADER (Scroll Natural Libre)
        // ══════════════════════════════════════════════════════════════════
        if (siteBar) {
            ScrollTrigger.create({
                trigger: heroWrapper,
                start: "bottom top", 
                invalidateOnRefresh: true,
                markers: { startColor: "blue", endColor: "orange", fontSize: "14px", label: "Aparición Header" },
                onEnter: () => gsap.to(siteBar, { yPercent: 0, autoAlpha: 1, duration: 0.6, ease: "power3.out" }),
                onLeaveBack: () => gsap.to(siteBar, { yPercent: -100, autoAlpha: 0, duration: 0.4, ease: "power2.in" })
            });
        }
    });
})();