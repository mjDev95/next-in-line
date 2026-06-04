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

        // ⚡ MODIFICADO: Agregamos zoom inicial (scale) masivo en el piso de la pantalla
        if (textLeft) {
            gsap.set(textLeft, { 
                y: "32vh", 
                scale: 1.4,             // Arranca gigante
                transformOrigin: "left bottom", // Crece desde la esquina inferior izquierda
                opacity: 1  
            });
        }

        if (textRight) {
            gsap.set(textRight, { 
                y: "32vh", 
                scale: 1.25,            // Arranca un poco más grande
                transformOrigin: "right bottom", // Crece desde la esquina inferior derecha
                opacity: 1  
            });
        }

        // FUNCIÓN DE RECORTE: Mide la columna real del sistema grid
        function getClipPathTarget() {
            const rect = photoBox.getBoundingClientRect();
            return `inset(${rect.top}px ${window.innerWidth - rect.right}px ${window.innerHeight - rect.bottom}px ${rect.left}px)`;
        }

        // ══════════════════════════════════════════════════════════════════
        // TIMELINE MASTER (Scrubbed)
        // ══════════════════════════════════════════════════════════════════
        const tlMaster = gsap.timeline({
            scrollTrigger: {
                trigger: heroWrapper,
                start: "top top",
                end: "+=120%",      
                pin: true,          
                pinSpacing: true,   
                scrub: 1, // ⚡ Reducido a 1 para que tenga una respuesta más directa y limpia
                fastScrollEnd: true, // ⚡ CLAVE: Sincroniza y suaviza el escape del pin al pasar el 'end'
                preventOverlaps: true, // ⚡ Evita que la animación colisione con las secciones de abajo
                invalidateOnRefresh: true,
                markers: { startColor: "green", endColor: "red", fontSize: "14px", label: "Hero Master" }
            }
        });

        // 1. La foto inicia su recorte de inmediato con el dedo
        tlMaster.to(photoTarget, {
            clipPath: () => getClipPathTarget(), 
            ease: "none" 
        }, "start");
        
        if (innerImg) {
            tlMaster.to(innerImg, {
                yPercent: -8,   
                scale: 1.0,     
                ease: "none"
            }, "start");
        }

        // 2. ⚡ ANIMACIÓN MIXTA (POSICIÓN + ZOOM) POR TIEMPO AUTÓNOMA
        tlMaster.call(() => {
            const isScrollingDown = tlMaster.scrollTrigger.direction === 1;

            if (isScrollingDown) {
                // Al bajar, suben a su origen (y:0) y reducen su escala a su tamaño real (scale:1)
                gsap.to(textRight, { y: 0, scale: 1, duration: 0.8, ease: "power3.out" });
                gsap.to(textLeft, { y: 0, scale: 1, duration: 0.9, ease: "power3.out", delay: 0.15 });
            } else {
                // Al regresar arriba, vuelven a expandirse y bajan suavemente
                gsap.to(textRight, { y: "32vh", scale: 1.25, duration: 0.6, ease: "power2.inOut" });
                gsap.to(textLeft, { y: "32vh", scale: 1.4, duration: 0.6, ease: "power2.inOut" });
            }
        }, null, "start+=0.05");

        // ══════════════════════════════════════════════════════════════════
        // TRIGGER 3: REVELADO SUTIL DEL HEADER
        // ══════════════════════════════════════════════════════════════════
        if (siteBar) {
            ScrollTrigger.create({
                trigger: heroWrapper,
                start: "bottom top", 
                markers: { startColor: "blue", endColor: "orange", fontSize: "14px", label: "Aparición Header" },
                onEnter: () => gsap.to(siteBar, { yPercent: 0, autoAlpha: 1, duration: 0.6, ease: "power3.out" }),
                onLeaveBack: () => gsap.to(siteBar, { yPercent: -100, autoAlpha: 0, duration: 0.4, ease: "power2.in" })
            });
        }
    });
})();