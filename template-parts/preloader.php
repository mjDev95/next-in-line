<?php
/**
 * Preloader inicial — solo Home, primera visita por sesión.
 * El JS comprueba sessionStorage; si ya visitó, la clase nil-preloader-skip
 * está en <html> y el preloader se oculta antes del primer paint.
 */
if ( ! is_front_page() ) {
    return;
}
?>
<div id="nil-preloader" class="position-fixed top-0 left-0 w-100 h-100 d-flex align-items-center justify-content-center">
    
    <div class="nil-pl-logo position-relative z-index-2 d-block" aria-hidden="true">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logos/nil-light.svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    </div>

    <div class="nil-pl-rounded-wrap overflow-hidden bottom position-absolute left-0 w-100" aria-hidden="true">
        <div class="nil-pl-rounded position-absolute bottom-0"></div>
    </div>

</div>