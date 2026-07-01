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
<header class="nil-site-bar position-fixed top-0 left-0 right-0 d-flex align-items-center justify-content-between py-md px-lg bg-white" role="banner">
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
	class="nil-fullscreen-nav position-fixed inset-0 w-auto d-flex overflow-x-hidden overflow-y-auto"
	id="nil-fullscreen-nav"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Menú principal', 'hello-elementor-child' ); ?>"
	aria-hidden="true"
>
	<button class="nil-fn-close" aria-label="<?php esc_attr_e( 'Cerrar menú', 'hello-elementor-child' ); ?>"></button>

	<div class="nil-fn-inner d-flex flex-column justify-content-between w-100 h-100">

		<div class="nil-fn-body flex-1 d-flex flex-column justify-content-center">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'nil-fullscreen-menu',
					'container'      => false,
					'menu_class'     => 'nil-fn-menu ps-0',
					'fallback_cb'    => '__return_false',
					'depth'          => 2,
				) );
			?>
				
			<?php
			if ( has_nav_menu( 'nil-corp-menu' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'nil-corp-menu',
					'container'      => false,
					'menu_class'     => 'nil-fn-menu mt-lg ps-0',
					'fallback_cb'    => '__return_false',
					'depth'          => 1,
				) );
			}
			?>
		</div>

		<div class="nil-fn-footer d-flex align-items-center justify-content-between flex-wrap gap-3 py-lg">
            <div class="nil-fn-footer-brand">
              	<button class="nil-fn-footer-btn nil-js-close-trigger h5" aria-label="<?php esc_attr_e( 'Regresar', 'hello-elementor-child' ); ?>"><?php esc_html_e( 'Regresar', 'hello-elementor-child' ); ?></button>
            </div>
            <div class="nil-fn-footer-social">
                <a href="https://www.instagram.com/nextinlinemanagement" target="_blank" rel="noopener noreferrer" class="nil-fn-social-link">
                    <i data-feather="instagram"></i>
                    <span>@nextinlinemanagement</span>
                </a>
            </div>
        </div>

	</div>
</nav>
<?php endif; ?>
