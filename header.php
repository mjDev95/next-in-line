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
<div id="nil-page-transition" aria-hidden="true">
	<div class="nil-pt-rounded-wrap top" aria-hidden="true">
		<div class="nil-pt-rounded"></div>
	</div>
	<div class="nil-pt-rounded-wrap bottom" aria-hidden="true">
		<div class="nil-pt-rounded"></div>
	</div>
	<div class="nil-pt-logo" aria-hidden="true">
		<?php
		$pt_logo_id  = (int) get_option( 'nil_header_logo_id', 0 );
		$pt_logo_src = $pt_logo_id ? wp_get_attachment_image_url( $pt_logo_id, 'full' ) : '';
		if ( $pt_logo_src ) : ?>
			<img src="<?php echo esc_url( $pt_logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php else : ?>
			<!-- Placeholder SVG — reemplazar con logo real -->
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 46" fill="none" aria-hidden="true">
				<path d="M4 6 L4 40 L30 6 L30 40" stroke="white" stroke-width="5"
				      stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		<?php endif; ?>
	</div>
</div>

<?php if ( ! is_front_page() ) : ?>
<!-- ── Site bar ─────────────────────────────────────────────────────────── -->
<header class="nil-site-bar" role="banner">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-bar-brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php
		$logo_id = (int) get_option( 'nil_header_logo_id', 0 );
		$logo_src = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
		if ( $logo_src ) :
		?>
			<img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<?php else : ?>
			<span><?php bloginfo( 'name' ); ?></span>
		<?php endif; ?>
	</a>

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

		<footer class="nil-fn-footer">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-fn-footer-brand">
				<?php bloginfo( 'name' ); ?>
			</a>

			<?php
			$instagram = get_option( 'nil_header_instagram', '' );
			$facebook  = get_option( 'nil_header_facebook', '' );
			if ( $instagram || $facebook ) :
			?>
			<div class="nil-fn-footer-social">
				<?php if ( $instagram ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
						<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
						<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
					</svg>
				</a>
				<?php endif; ?>
				<?php if ( $facebook ) : ?>
				<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
					</svg>
				</a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</footer>

	</div>
</nav>
<?php endif; ?>
