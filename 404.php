<?php get_header(); ?>

<main class="nil-404-wrapper d-flex align-items-center py-xl">
    <div class="container">

        <div class="row align-items-center">

            <div class="col-12 col-lg-5">
                
                <span class="nil-404-eyebrow text-uppercase d-block mb-sm">
                    <?php _e( 'Página no encontrada', 'nil' ); ?>
                </span>

                <h1 class="nil-404-title text-uppercase mb-xs">404</h1>

                <p class="nil-404-subtitle text-uppercase text-start mb-md">
                    <?php _e( 'La página que buscas ha sido movida, archivada o no existe.', 'nil' ); ?>
                </p>

                <p class="nil-404-description mb-lg">
                    <?php _e( 'Explora nuestras divisiones, descubre nuevo talento o regresa al inicio para continuar navegando.', 'nil' ); ?>
                </p>

                <div class="d-flex flex-wrap justify-content-start align-items-center">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-btn nil-btn-primary text-uppercase mt-sm">
                        <?php _e( 'Volver al inicio', 'nil' ); ?>
                    </a>
                </div>
                
            </div>


        </div> 
    </div> 
</main>
<div class="d-none">
<?php get_footer( 'modelos' ); ?>
</div>
