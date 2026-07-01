<?php
/**
 * Template Name: Contacto
 *
 * @package HelloElementorChild
 */

get_header();
?>

<main class="nil-simple-page-wrapper py-2xl">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                
                <h1 class="h2 text-uppercase text-center mb-lg"><?php esc_html_e( 'Contacto', 'hello-elementor-child' ); ?></h1>

                <div class="nil-contact-section">
                    <a href="https://www.instagram.com/nextinlinemanagement?igsh=MWtsdXI1NXNvcnBxeA%3D%3D&utm_source=qr" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-lg">
                        <i data-feather="instagram"></i>
                        <span class="text-uppercase h6">@nextinlinemanagement</span>
                    </a>

                    <p class="mb-lg"><?php esc_html_e( '¿Te gustaría formar parte de Next In Line Management? Escríbenos.', 'hello-elementor-child' ); ?></p>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6 mb-md">
                            <p class="text-uppercase my-0">José Miguel Tapia</p>
                            <a href="mailto:josemiguel@nextinlinemanagement.com">josemiguel@nextinlinemanagement.com</a>
                        </div>
                        <div class="col-12 col-md-6 mb-md">
                            <p class="text-uppercase my-0">Armando Cantorán</p>
                            <a href="mailto:armando@nextinlinemanagement.com">armando@nextinlinemanagement.com</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<?php get_footer( 'modelos' ); ?>