/* global gsap, ScrollTrigger */
(function () {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        const heroWrapper = document.querySelector(".nil-hero-scroll-wrapper");
        const photoTarget = document.querySelector(".nil-modelo-photo-target");
        const innerImg = photoTarget ? photoTarget.querySelector("img") : null;
        const textLeft = document.querySelector(".nil-modelo-hero-left");
        const textRight = document.querySelector(".nil-modelo-hero-right");

        // Seleccionamos TU header con la clase exacta
        const siteBar = document.querySelector(".nil-site-bar");
        
        // ⚡ AÑADIDO: Seleccionamos tu placeholder maestro invisible
        const placeholder = document.querySelector(".nil-modelo-photo-placeholder");

        // Si NO estamos en la página del modelo, abortamos para no romper el header en el resto del sitio
        if (!heroWrapper || !photoTarget) return;

        gsap.registerPlugin(ScrollTrigger);

        // ── SECUESTRO INICIAL DEL HEADER ──
        if (siteBar) {
            // Lo ocultamos inmediatamente moviéndolo un 100% hacia arriba de su propia altura
            gsap.set(siteBar, { yPercent: -100, autoAlpha: 0 });
        }

        // ⚡ AÑADIDO: FUNCIÓN MATEMÁTICA PARA EL RECORTE DINÁMICO ──
        // Esta función lee el tamaño y posición exactos del placeholder, respetando el "gap: clamp()"
        function getClipPathTarget() {
            if (!placeholder) return "inset(14% 32% 14% 32%)"; // Respaldo
            
            // Medimos el cuadro invisible (el destino final perfecto)
            const pRect = placeholder.getBoundingClientRect();
            // Medimos el cuadro gigante de la foto actual
            const tRect = photoTarget.getBoundingClientRect();
            
            // Restamos las coordenadas de uno contra el otro para sacar los bordes exactos
            const top = pRect.top - tRect.top;
            const left = pRect.left - tRect.left;
            const right = tRect.right - pRect.right;
            const bottom = tRect.bottom - pRect.bottom;
            
            // Usamos Math.max(0, ...) por si algún redondeo del navegador da negativos
            return `inset(${Math.max(0, top)}px ${Math.max(0, right)}px ${Math.max(0, bottom)}px ${Math.max(0, left)}px)`;
        }

        // ══════════════════════════════════════════════════════════════════
        // TIMELINE 1: ANIMACIÓN DEL HERO (RECORTE Y PARALLAX)
        // ══════════════════════════════════════════════════════════════════
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: heroWrapper,
                start: "top top",
                end: "+=120%",      
                pin: true,          
                pinSpacing: true,   
                scrub: 1.2,
                invalidateOnRefresh: true, // Fundamental para recalcular si el usuario redimensiona la ventana
                // MARCADORES AÑADIDOS PARA EL HERO
                markers: { startColor: "green", endColor: "red", fontSize: "14px", label: "Animación Hero" }
            }
        });

        // ⚡ CORREGIDO: Cambiamos el valor estático por la función matemática
        tl.to(photoTarget, {
            clipPath: () => getClipPathTarget(), 
            ease: "none" 
        }, "start")
        
        if (innerImg) {
            tl.to(innerImg, {
                yPercent: -8,   
                scale: 1.0,     
                ease: "none"
            }, "start");
        }

        if (textLeft || textRight) {
            const targets = [];
            if (textLeft) targets.push(textLeft);
            if (textRight) targets.push(textRight);

            tl.to(targets, {
                opacity: 1,
                y: 0,
                duration: 0.4,
                stagger: 0.1,
                ease: "power2.out"
            }, "-=0.4"); 
        }

        // ══════════════════════════════════════════════════════════════════
        // TRIGGER 2: REVELADO SUTIL DEL HEADER (.nil-site-bar)
        // ══════════════════════════════════════════════════════════════════
        if (siteBar) {
            ScrollTrigger.create({
                trigger: heroWrapper,
                // Inicia cuando la parte baja del hero toca el techo de la pantalla
                start: "bottom top", 
                
                // MARCADORES AÑADIDOS PARA EL HEADER
                markers: { startColor: "blue", endColor: "orange", fontSize: "14px", label: "Aparición Header" },
                
                onEnter: () => {
                    // Revela el header hacia abajo
                    gsap.to(siteBar, { 
                        yPercent: 0, 
                        autoAlpha: 1, 
                        duration: 0.6, 
                        ease: "power3.out" 
                    });
                },
                
                onLeaveBack: () => {
                    // Si el usuario regresa al hero, el header se vuelve a ocultar elegantemente
                    gsap.to(siteBar, { 
                        yPercent: -100, 
                        autoAlpha: 0, 
                        duration: 0.4, 
                        ease: "power2.in" 
                    });
                }
            });
        }
    });
})();