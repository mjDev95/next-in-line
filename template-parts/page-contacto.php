<?php
/**
 * Template Part: Contacto
 * Incluido desde template-contacto.php (raíz del tema).
 *
 * @package HelloElementorChild
 */
?>

<main class="nil-page nil-page--contacto py-2xl">
    <div class="container">

        <!-- Eyebrow + Título -->
        <div class="row mb-lg">
            <div class="col-12">
                <h1 class="h1 text-uppercase mb-0"><?php esc_html_e( 'Contacto', 'hello-elementor-child' ); ?></h1>
            </div>
        </div>

        <hr class="nil-page__divider mb-lg">

        <!-- Bloque principal -->
        <div class="row gy-4">

            <!-- Columna izquierda: descripción + Instagram -->
            <div class="col-12 col-md-5">
                <p class="nil-page__intro mb-lg">
                    <?php esc_html_e( '¿Te gustaría formar parte de Next In Line Management? Escríbenos.', 'hello-elementor-child' ); ?>
                </p>

                <a href="https://www.instagram.com/nextinlinemanagement?igsh=MWtsdXI1NXNvcnBxeA%3D%3D&utm_source=qr"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="nil-page__social d-inline-flex align-items-center gap-2">
                    <i data-feather="instagram"></i>
                    <span class="text-uppercase h6 mb-0">@nextinlinemanagement</span>
                </a>
            </div>

            <!-- Separador vertical visible solo en desktop -->
            <div class="col-12 col-md-1 d-none d-md-flex justify-content-center">
                <div class="nil-page__vline"></div>
            </div>

            <!-- Columna derecha: contactos -->
            <div class="col-12 col-md-6">
                <div class="row gy-3">

                    <div class="col-12">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'José Miguel Tapia', 'hello-elementor-child' ); ?></p>
                        <a href="mailto:josemiguel@nextinlinemanagement.com" class="nil-page__email">
                            josemiguel@nextinlinemanagement.com
                        </a>
                    </div>

                    <div class="col-12">
                        <hr class="nil-page__divider--light my-0">
                    </div>

                    <div class="col-12">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Armando Cantorán', 'hello-elementor-child' ); ?></p>
                        <a href="mailto:armando@nextinlinemanagement.com" class="nil-page__email">
                            armando@nextinlinemanagement.com
                        </a>
                    </div>

                </div>
            </div>

        </div><!-- .row -->

    </div><!-- .container -->
</main>
