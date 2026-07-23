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
                        <a href="tel:5579252559" class="nil-page__email">
                            <i data-feather="phone"></i>
                            55 7925 2559
                        </a>
                    </div>

                    <div class="col-12 col-lg-6">
                        <p class="nil-page__label text-uppercase mb-sm"><?php esc_html_e( 'Instagram', 'hello-elementor-child' ); ?></p>
                        <a href="https://www.instagram.com/nextinlinemanagement?igsh=MWtsdXI1NXNvcnBxeA%3D%3D&utm_source=qr"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="nil-page__social d-inline-flex align-items-center gap-2">
                            <i data-feather="instagram"></i>
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
                <a  style="max-width: 250px;" href="https://www.google.com/maps/dir/?api=1&destination=Av.+Insurgentes+Sur+863-Piso+7,+Oficina+01,+Nápoles,+Benito+Juárez,+03010+Ciudad+de+México,+CDMX"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-auto nil-btn text-center text-uppercase nil-btn-back d-flex justify-content-center align-items-center gap-2">
                    <?php esc_html_e( 'Cómo llegar', 'hello-elementor-child' ); ?>
                </a>
            </div>

        </div><!-- .row -->

    </div><!-- .container -->
</main>
