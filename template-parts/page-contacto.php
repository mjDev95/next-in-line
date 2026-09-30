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

            <!-- Columna izquierda: descripción + contactos -->
            <div class="col-12 col-md-5">
                <p class="nil-page__intro mb-lg">
                    <?php esc_html_e( '¿Te gustaría formar parte de Next In Line Management? Escríbenos.', 'hello-elementor-child' ); ?>
                </p>

                <div class="row gy-4">
                    <div class="col-12">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'José Miguel Tapia', 'hello-elementor-child' ); ?></p>
                        <a href="mailto:josemiguel@nextinlinemanagement.com" class="nil-page__email">
                            josemiguel@nextinlinemanagement.com
                        </a>
                    </div>

                    <div class="col-12">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Armando Cantorán', 'hello-elementor-child' ); ?></p>
                        <a href="mailto:armando@nextinlinemanagement.com" class="nil-page__email">
                            armando@nextinlinemanagement.com
                        </a>
                    </div>

                    <div class="col-12 col-lg-6">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Teléfono', 'hello-elementor-child' ); ?></p>
                        <a href="tel:5579252559" class="nil-page__email d-inline-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            55 7925 2559
                        </a>
                    </div>

                    <div class="col-12 col-lg-6">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Instagram', 'hello-elementor-child' ); ?></p>
                        <a href="https://www.instagram.com/nextinlinemanagement?igsh=MWtsdXI1NXNvcnBxeA%3D%3D&utm_source=qr"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="nil-page__social d-inline-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            <span class="text-uppercase h6 mb-0">@nextinlinemanagement</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Separador vertical visible solo en desktop -->
            <div class="col-12 col-md-1 d-none d-md-flex justify-content-center">
                <div class="nil-page__vline"></div>
            </div>

            <!-- Columna derecha: Dirección + Mapa -->
            <div class="col-12 col-md-6 d-flex flex-column">
                <div class="mb-lg">
                    <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Dirección', 'hello-elementor-child' ); ?></p>
                    <p class="nil-page__email mb-0">
                        Av. Insurgentes Sur 863-Piso 7, Oficina 01, Nápoles, Benito Juárez, 03010 Ciudad de México, CDMX
                    </p>
                </div>

                <div class="nil-map-wrapper mb-md" style="flex-grow: 1; min-height: 350px;">
                    
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3763.497553353929!2d-99.1755356247847!3d19.39083318188147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1ff1208181551%3A0x4111b60882a45334!2sAv.%20de%20los%20Insurgentes%20Sur%20863%2C%20N%C3%A1poles%2C%20Benito%20Ju%C3%A1rez%2C%2003810%20Ciudad%20de%20M%C3%A9xico%2C%20CDMX!5e0!3m2!1ses!2smx"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="<?php esc_attr_e( 'Ubicación en Google Maps', 'hello-elementor-child' ); ?>">
                    </iframe>
                </div>
                <a  style="max-width: 250px;" href="https://www.google.com/maps/dir/?api=1&destination=Av.+Insurgentes+Sur+863-Piso+7,+Oficina+01,+N%C3%A1poles,+Benito+Ju%C3%A1rez,+03010+Ciudad+de+M%C3%A9xico,+CDMX"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-auto nil-btn nil-btn--directions text-center text-uppercase nil-btn-back d-flex justify-content-center align-items-center gap-2">
                    <?php esc_html_e( 'Cómo llegar', 'hello-elementor-child' ); ?>
                </a>
            </div>

        </div><!-- .row -->

    </div><!-- .container -->
</main>
