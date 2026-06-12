<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/preloader' ); ?>

<!-- ── Page transition overlay ────────────────────────────────────────────────── -->
<div id="nil-page-transition" class="position-fixed top-0 left-0 w-100 h-100 d-flex align-items-center justify-content-center" aria-hidden="true">
	<div class="nil-pt-rounded-wrap top" aria-hidden="true">
		<div class="nil-pt-rounded"></div>
	</div>
	<div class="nil-pt-rounded-wrap bottom" aria-hidden="true">
		<div class="nil-pt-rounded"></div>
	</div>
	<div class="nil-pl-logo position-relative z-index-2 d-block" aria-hidden="true">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logos/nil-light.svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    </div>
</div>

<?php if ( ! is_front_page() ) : ?>
<!-- ── Site bar ─────────────────────────────────────────────────────────── -->
<header class="nil-site-bar" role="banner">
	<?php
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		} else {
			bloginfo( 'name' );
		}
	?>

	<button
		class="nil-hamburger"
		id="nil-hamburger-btn"
		aria-label="<?php esc_attr_e( 'Abrir menú', 'hello-elementor-child' ); ?>"
		aria-expanded="false"
		aria-controls="nil-fullscreen-nav"
	>
		<span class="nil-ham-line"></span>
		<span class="nil-ham-line"></span>
		<span class="nil-ham-line"></span>
	</button>
</header>

<!-- ── Fullscreen nav overlay ───────────────────────────────────────────── -->
<nav
	class="nil-fullscreen-nav"
	id="nil-fullscreen-nav"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Menú principal', 'hello-elementor-child' ); ?>"
	aria-hidden="true"
>
	<button class="nil-fn-close" aria-label="<?php esc_attr_e( 'Cerrar menú', 'hello-elementor-child' ); ?>"></button>

	<div class="nil-fn-inner">

		<div class="nil-fn-body">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'nil-fullscreen-menu',
				'container'      => false,
				'menu_class'     => 'nil-fn-menu',
				'fallback_cb'    => '__return_false',
				'depth'          => 2,
			) );
			?>
		</div>

		<div class="nil-fn-footer">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-fn-footer-brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                <?php
                if ( ! empty( $logo_src ) ) : ?>
                    <img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                <?php else : ?>
                    <span><?php bloginfo( 'name' ); ?></span>
                <?php endif; ?>
            </a>

            <div class="nil-fn-footer-social">
                <?php
                // Inyectamos el menú nativo de WordPress
                if ( has_nav_menu( 'nil-social-menu' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'nil-social-menu',
                        'container'      => false,
                        'menu_class'     => 'nil-social-nav list-unstyled d-md-flex flex-wrap align-items-center m-0',
                        'fallback_cb'    => '__return_false',
                        'depth'          => 1,
                    ) );
                }
                ?>
            </div>
        </div>

	</div>
</nav>
<?php endif; ?>
