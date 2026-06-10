(function ($) {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        
        // ─────────────────────────────────────────
        // 🏎️ MOTOR FASE 1: LA CUADRÍCULA ASÍNCRONA
        // ─────────────────────────────────────────
        const lazyMedia = document.querySelectorAll(".nil-lazy-media");

        if (lazyMedia.length && typeof IntersectionObserver !== "undefined") {
            const mediaObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const srcTarget = element.getAttribute("data-src");

                        if (!srcTarget) return;

                        if (element.tagName === "IMG") {
                            element.src = srcTarget;
                            if (element.complete) {
                                element.classList.add("nil-media-loaded");
                            } else {
                                element.addEventListener("load", () => {
                                    element.classList.add("nil-media-loaded");
                                });
                            }
                        } else if (element.tagName === "VIDEO") {
                            const source = element.querySelector("source");
                            if (source) source.src = srcTarget;
                            element.src = srcTarget;
                            element.load(); 

                            if (element.readyState >= 2) {
                                element.classList.add("nil-media-loaded");
                            } else {
                                element.addEventListener("loadeddata", () => {
                                    element.classList.add("nil-media-loaded");
                                });
                            }
                        }
                        observer.unobserve(element);
                    }
                });
            }, {
                rootMargin: "0px 0px 300px 0px" // Anticipación de scroll editorial
            });

            lazyMedia.forEach(media => mediaObserver.observe(media));
        }

        // ─────────────────────────────────────────
        // 💎 MOTOR FASE 2: LIGHTBOX FERRARI + GSAP
        // ─────────────────────────────────────────
        const $overlay = $('#nil-lightbox-overlay');
        const $closeBtn = $('#nil-lightbox-close');
        let swiperInstance = null;

        if (!$overlay.length) return;

// Inyector diferido para el Lightbox (Lector nativo de atributos de datos)
        function cargarMediaLightbox(slide) {
            const $slide = $(slide);
            const $holder = $slide.find('.nil-lightbox-media-holder');
            
            if ($holder.hasClass('nil-media-processed')) return; // Evitar duplicar cargas

            const mediaUrl = $slide.data('lightbox-src');
            const mediaType = $slide.data('media-type');
            const mediaAlt = $slide.data('media-alt') || 'Model Portafolio';

            if (!mediaUrl) return;

            $holder.empty().addClass('nil-media-processed');

            // ── CASO A: IMÁGENES EN EL LIGHTBOX ──
            if (mediaType === 'image') {
                const $img = $('<img>', {
                    src: mediaUrl,
                    alt: mediaAlt,
                    class: 'w-100 h-100 object-fit-contain'
                });
                $holder.append($img);
                
                $img.on('load', function() { 
                    $img.addClass('nil-loaded'); 
                });

            // ── CASO B: VIDEOS EN EL LIGHTBOX ──
            // El <video> nativo captura todos los eventos, bloqueando el drag de Swiper.
            // Solución: overlay transparente encima que deja burbujear los eventos a Swiper
            // y detecta taps cortos para play/pause.
            } else if (mediaType === 'video') {
                const $wrap  = $('<div>', { class: 'nil-video-lb-wrap' });
                const $video = $('<video>', {
                    src: mediaUrl,
                    class: 'w-100 h-100 object-fit-contain',
                    autoplay: true,
                    playsinline: true,
                    preload: 'metadata'
                    // Sin 'controls': el overlay maneja la interacción
                });
                const $overlay = $('<div>', { class: 'nil-video-lb-overlay' });
                const $hint    = $('<div>', { class: 'nil-video-lb-hint' });

                // Tap corto → play / pause
                let _tapX, _tapY, _tapT;
                $overlay
                    .on('pointerdown', function (e) {
                        _tapX = e.clientX;
                        _tapY = e.clientY;
                        _tapT = Date.now();
                    })
                    .on('pointerup', function (e) {
                        const dx = Math.abs(e.clientX - _tapX);
                        const dy = Math.abs(e.clientY - _tapY);
                        if (dx < 12 && dy < 12 && (Date.now() - _tapT) < 280) {
                            const v = $video[0];
                            if (v.paused) {
                                v.play();
                                $hint.text('\u25B6').addClass('nil-video-lb-hint--flash');
                            } else {
                                v.pause();
                                $hint.text('\u23F8').addClass('nil-video-lb-hint--flash');
                            }
                            $hint.one('animationend', function () {
                                $hint.removeClass('nil-video-lb-hint--flash');
                            });
                        }
                    });

                $wrap.append($video, $overlay, $hint);
                $holder.append($wrap);

                $video.on('loadeddata', function () {
                    $video.addClass('nil-loaded');
                });
            }
        }

        // Inicializador de Swiper
        function initSwiperLightbox(initialIndex) {
            swiperInstance = new Swiper('.nil-lightbox-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                initialSlide: initialIndex,
                speed: 600,
                pagination: {
                    el: '.nil-lightbox-pagination',
                    type: 'custom',
                    renderCustom: function (swiper, current, total) {
                        const pad = (num) => num < 10 ? '0' + num : num;
                        return pad(current) + ' <span class="text-muted">/</span> ' + pad(total);
                    }
                },
                on: {
                    init: function () {
                        cargarMediaLightbox(this.slides[this.activeIndex]);
                        if (this.slides[this.activeIndex + 1]) cargarMediaLightbox(this.slides[this.activeIndex + 1]);
                        if (this.slides[this.activeIndex - 1]) cargarMediaLightbox(this.slides[this.activeIndex - 1]);
                    },
                    slideChange: function () {
                        $('.nil-lightbox-media-holder video').each(function() { this.pause(); });
                        cargarMediaLightbox(this.slides[this.activeIndex]);
                        if (this.slides[this.activeIndex + 1]) cargarMediaLightbox(this.slides[this.activeIndex + 1]);
                        if (this.slides[this.activeIndex - 1]) cargarMediaLightbox(this.slides[this.activeIndex - 1]);
                    }
                }
            });
        }

        // Disparador de Apertura
        $('[data-index]').on('click', function(e) {
            e.preventDefault();
            const startIndex = parseInt($(this).data('index'), 10);

            // Inyectamos la clase de estado al body para desaparecer el header de inmediato
            $('body').addClass('nil-lightbox-active').css('overflow', 'hidden'); 

            $overlay.removeClass('d-none');
            gsap.to($overlay, { opacity: 1, duration: 0.4, ease: 'power2.out', onComplete: function() {
                initSwiperLightbox(startIndex);
            }});

            if (window.NilCursor) NilCursor.show('drag');
        });

        // Disparador de Cierre (Sincronizado)
        function cerrarLightbox() {
            // Liberamos el scroll nativo, pero dejamos la clase del header intacta
            $('body').css('overflow', '');

            gsap.to($overlay, { 
                opacity: 0, 
                duration: 0.4, 
                ease: 'power2.inOut', 
                onComplete: function() {
                    // ⚡ CLAVE: El header se revela HASTA QUE el overlay es 100% invisible
                    $('body').removeClass('nil-lightbox-active');
                    
                    $overlay.addClass('d-none');
                    if (swiperInstance) {
                        swiperInstance.destroy(true, true);
                        swiperInstance = null;
                    }
                    $('.nil-lightbox-media-holder').empty().removeClass('nil-media-processed');
                }
            });

            if (window.NilCursor) NilCursor.hide();
        }

        $closeBtn.on('click', cerrarLightbox);

        // ── DETECCIÓN DE DIRECCIÓN DE ARRASTRE ──
        let dragStartX = null;
        $overlay.on('pointerdown', function (e) {
            if ($(e.target).closest('#nil-lightbox-close, video').length) return;
            dragStartX = e.clientX;
        });
        $overlay.on('pointermove', function (e) {
            if (dragStartX === null || !window.NilCursor) return;
            const delta = e.clientX - dragStartX;
            if (Math.abs(delta) > 8) {
                NilCursor.setDragDir(delta < 0 ? 'next' : 'prev');
            }
        });
        $overlay.on('pointerup pointercancel', function () {
            dragStartX = null;
            if (window.NilCursor) NilCursor.setDragDir(null);
        });

        // Clic navegable por cuadrantes laterales
        $overlay.on('click', function(e) {
            if ($(e.target).closest('#nil-lightbox-close, video, controls').length || !swiperInstance) return;

            const windowWidth = $(window).width();
            if (e.clientX < windowWidth / 2) {
                swiperInstance.slidePrev();
            } else {
                swiperInstance.slideNext();
            }
        });
    });
})(jQuery);