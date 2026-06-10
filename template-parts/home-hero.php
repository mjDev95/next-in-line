<?php
/**
 * Template part: Home Hero
 * Panels are driven by the "Menú Pantalla Completa (Header)" nav menu
 */

get_header();

$home_video = get_post_meta( (int) get_option( 'page_on_front' ), 'nil_home_bg_video', true );

$menu_locations = get_nav_menu_locations();
$menu_id        = isset( $menu_locations['nil-fullscreen-menu'] ) ? (int) $menu_locations['nil-fullscreen-menu'] : 0;
$menu_items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

$panels = array_values(
    array_filter( $menu_items, function ( $item ) {
        return (int) $item->menu_item_parent === 0;
    } )
);
?>

<header class="position-relative overflow-hidden w-100" role="banner" style="height: 100vh; height: 100dvh;">

    <?php if ( $home_video ) : ?>
        <video class="position-absolute inset-0 w-100 h-100 object-fit-cover z-index-0" autoplay muted loop playsinline>
            <source src="<?php echo esc_url( $home_video ); ?>" type="video/mp4">
        </video>
    <?php endif; ?>

    <div class="nil-home-video-overlay position-absolute inset-0 z-index-1"></div>

    <?php if ( ! empty( $panels ) ) : ?>

    <div class="nil-categories-hero position-relative z-index-2 w-100 h-100 d-flex flex-column justify-content-between p-md p-lg-0">

        <div class="nil-home-logo position-relative w-100 z-index-10 text-center text-lg-start mb-auto">
            <?php
            $_nil_custom_logo_id = (int) get_theme_mod( 'custom_logo', 0 );
            $_nil_logo_id        = $_nil_custom_logo_id ?: (int) get_option( 'nil_header_logo_id', 0 );
            $_nil_logo_url       = $_nil_logo_id ? wp_get_attachment_image_url( $_nil_logo_id, 'full' ) : '';
            if ( $_nil_logo_url ) : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="d-inline-block">
                    <img src="<?php echo esc_url( $_nil_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="height: clamp(50px, 10vw, 78px); width: auto;">
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-white text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.25em; text-decoration: none;">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <?php foreach ( $panels as $item ) : ?>

            <div class="nil-cat-panel d-flex flex-column justify-content-center justify-content-lg-end text-center">

                <a href="<?php echo esc_url( $item->url ); ?>" class="nil-cat-link d-inline-block text-white px-lg px-lg-0" style="text-decoration: none;">
                    
                    <h2 class="nil-cat-name text-uppercase my-0 text-white fw-normal">
                        <?php echo esc_html( $item->title ); ?>
                    </h2>
                    
                    <span class="nil-cat-cta d-none d-lg-block text-uppercase mt-sm ">
                        <?php esc_html_e( 'Ver todos', 'hello-elementor-child' ); ?> &rarr;
                    </span>

                </a>

            </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>

</header>

<?php get_footer( 'home' ); ?>