/* global gsap, ScrollTrigger, Swiper */
(function ($) {
    'use strict';

    document.addEventListener("DOMContentLoaded", () => {
        
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
        }

        // ─────────────────────────────────────────
        // 🏎️ MOTOR FASE 1: ASYNC FETCH + GSAP BATCH
        // ─────────────────────────────────────────
        gsap.set(".nil-batch-item", { autoAlpha: 0, y: 40 });

        ScrollTrigger.batch(".nil-batch-item", {
            interval: 0.1,
            // Ampliamos el margen para que empiece a descargar un poco antes de que sea visible
            start: "top 110%", 
            onEnter: (batch) => {
                
                // Recorremos cada tarjeta del lote
                batch.forEach((card, index) => {
                    const media = card.querySelector('.nil-lazy-media');
                    if (!media) return;

                    const srcTarget = media.getAttribute('data-src');
                    if (!srcTarget) return;

                    // Si es IMAGEN: Descarga asíncrona controlada
                    if (media.tagName === 'IMG') {
                        const tempImg = new Image();
                        tempImg.src = srcTarget; // Inicia la descarga en background
                        
                        tempImg.onload = () => {
                            media.src = srcTarget; // Reemplazamos el pixel transparente por la foto real
                            media.classList.add('nil-media-loaded');
                            
                            // Animamos SOLO cuando ya cargó
                            gsap.to(card, {
                                autoAlpha: 1,
                                y: 0,
                                delay: index * 0.15, // Stagger manual basado en su posición en el lote
                                duration: 0.8,
                                ease: "power3.out",
                                overwrite: true
                            });
                        };
                    } 
                    // Si es VIDEO
                    else if (media.tagName === 'VIDEO') {
                        media.src = srcTarget;
                        media.load();
                        media.classList.add('nil-media-loaded');

                        gsap.to(card, {
                            autoAlpha: 1,
                            y: 0,
                            delay: index * 0.15,
                            duration: 0.8,
                            ease: "power3.out",
                            overwrite: true
                        });
                    }
                });
            },
            once: true
        });


        // ─────────────────────────────────────────
        // 💎 MOTOR FASE 2: LIGHTBOX FERRARI + GSAP (Sigue igual)
        // ─────────────────────────────────────────
        const $overlay = $('#nil-lightbox-overlay');
        const $closeBtn = $('#nil-lightbox-close');
        let swiperInstance = null;

        if (!$overlay.length) return;

        function cargarMediaLightbox(slide) {
            const $slide = $(slide);
            const $holder = $slide.find('.nil-lightbox-media-holder');
            
            if ($holder.hasClass('nil-media-processed')) return;

            const mediaUrl = $slide.data('lightbox-src');
            const mediaType = $slide.data('media-type');
            const mediaAlt = $slide.data('media-alt') || 'Model Portafolio';

            if (!mediaUrl) return;

            $holder.empty().addClass('nil-media-processed');

            if (mediaType === 'image') {
                const $img = $('<img>', {
                    src: mediaUrl,
                    alt: mediaAlt,
                    title: mediaAlt, 
                    class: 'w-100 h-100 object-fit-contain'
                });
                $holder.append($img);
                
                $img.on('load', function() { 
                    $img.addClass('nil-loaded'); 
                });

            } else if (mediaType === 'video') {
                const $wrap  = $('<div>', { class: 'nil-video-lb-wrap' });
                const $video = $('<video>', {
                    src: mediaUrl,
                    class: 'w-100 h-100 object-fit-contain',
                    autoplay: true,
                    playsinline: true,
                    preload: 'metadata'
                });
                const $overlay = $('<div>', { class: 'nil-video-lb-overlay' });
                const $hint    = $('<div>', { class: 'nil-video-lb-hint' });

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

        $('[data-index]').on('click', function(e) {
            e.preventDefault();
            const startIndex = parseInt($(this).data('index'), 10);

            $('body').addClass('nil-lightbox-active').css('overflow', 'hidden'); 

            $overlay.removeClass('d-none');
            gsap.to($overlay, { opacity: 1, duration: 0.4, ease: 'power2.out', onComplete: function() {
                initSwiperLightbox(startIndex);
            }});

            if (window.NilCursor) NilCursor.show('drag');
        });

        function cerrarLightbox() {
            $('body').css('overflow', '');

            gsap.to($overlay, { 
                opacity: 0, 
                duration: 0.4, 
                ease: 'power2.inOut', 
                onComplete: function() {
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