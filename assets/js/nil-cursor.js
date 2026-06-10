/**
 * NilCursor — Sistema de cursor personalizado global.
 *
 * ── API PÚBLICA ──────────────────────────────────────────────
 *   NilCursor.show(state)            Muestra el cursor con el estado indicado.
 *   NilCursor.hide()                 Oculta el cursor y limpia el estado.
 *   NilCursor.setDragDir(dir)        Activa la flecha de arrastre: 'prev' | 'next' | null.
 *   NilCursor.register(sel, state)   Registra una zona hover con un estado (para uso futuro).
 *
 * ── ESTADOS INCLUIDOS ────────────────────────────────────────
 *   'eye'  — Ícono de ojo (galería de fotos, elementos que se pueden "ver").
 *   'drag' — Flechas prev/next (lightbox, sliders, carruseles).
 *
 * ── AGREGAR UN NUEVO ESTADO ──────────────────────────────────
 *   1. Añade el HTML en template-parts/cursor.php.
 *   2. Añade los estilos CSS en assets/css/global.css.
 *   3. Llama NilCursor.register('.selector', 'nombre-estado') aquí abajo,
 *      o llama NilCursor.show('nombre-estado') desde el script que lo necesite.
 */

(function ($) {
    'use strict';

    window.NilCursor = (function () {

        let el        = null;
        let isReady   = false;

        // ── INIT ──────────────────────────────────────────────
        function init() {
            el = document.getElementById('nil-custom-cursor');
            if (!el) return;

            // No custom cursor en dispositivos táctiles / stylus (pointer: coarse)
            if (!window.matchMedia('(pointer: fine)').matches) return;

            // Inicializar Feather Icons dentro del cursor
            if (window.feather) {
                feather.replace({ 'stroke-width': 1.5, width: 20, height: 20 });
            }

            // Mostrar el elemento (CSS lo tiene display:none por defecto)
            el.style.display = 'flex';
            gsap.set(el, { scale: 0, opacity: 0 });

            // Seguimiento global del mouse
            document.addEventListener('mousemove', function (e) {
                gsap.to(el, {
                    x: e.clientX,
                    y: e.clientY,
                    duration: 0.08,
                    ease: 'power1.out',
                    overwrite: 'auto'
                });
            });

            // ── Zona por defecto: galería → estado "eye" ──
            $(document).on('mouseenter.nilcursor', '.nil-gallery-item', function () {
                show('eye');
            }).on('mouseleave.nilcursor', '.nil-gallery-item', function () {
                // Solo ocultar si seguimos en estado 'eye'.
                // Si el estado ya cambió a 'drag' (lightbox abierto desde el click),
                // no interrumpir esa transición.
                if (el && el.dataset.state === 'eye') hide();
            });

            isReady = true;
        }

        // ── SHOW ──────────────────────────────────────────────
        function show(state) {
            if (!isReady || !el) return;
            el.dataset.state = state;
            gsap.to(el, {
                scale: 1,
                opacity: 1,
                duration: 0.3,
                ease: 'back.out(1.7)',
                overwrite: 'auto'
            });
        }

        // ── HIDE ──────────────────────────────────────────────
        function hide() {
            if (!isReady || !el) return;
            gsap.to(el, {
                scale: 0,
                opacity: 0,
                duration: 0.2,
                ease: 'power2.in',
                overwrite: 'auto',
                onComplete: function () {
                    delete el.dataset.state;
                    delete el.dataset.dragDir;
                }
            });
        }

        // ── DRAG DIRECTION ────────────────────────────────────
        // dir: 'prev' (arrastra derecha) | 'next' (arrastra izquierda) | null (neutral)
        function setDragDir(dir) {
            if (!isReady || !el) return;
            if (dir) {
                el.dataset.dragDir = dir;
            } else {
                delete el.dataset.dragDir;
            }
        }

        // ── REGISTER (para uso futuro) ────────────────────────
        // Registra una zona hover y la asocia a un estado del cursor.
        // Uso: NilCursor.register('.mi-elemento', 'mi-estado')
        function register(selector, state) {
            $(document).on('mouseenter.nilcursor', selector, function () {
                show(state);
            }).on('mouseleave.nilcursor', selector, function () {
                if (el && el.dataset.state === state) hide();
            });
        }

        return { init, show, hide, setDragDir, register };

    })();

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap !== 'undefined') {
            NilCursor.init();
        }
    });

})(jQuery);
