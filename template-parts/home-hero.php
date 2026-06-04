<?php
/**
 * Template part: Home Hero
 * Panels are driven by the "Menú Pantalla Completa (Header)" nav menu
 * so the admin only needs to configure one place.
 */

get_header();

$home_video = get_post_meta( (int) get_option( 'page_on_front' ), 'nil_home_bg_video', true );

// ── Resolve panels from the fullscreen nav menu ────────────────────────────
$menu_locations = get_nav_menu_locations();
$menu_id        = isset( $menu_locations['nil-fullscreen-menu'] ) ? (int) $menu_locations['nil-fullscreen-menu'] : 0;
$menu_items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

// Top-level items only
$panels = array_values(
	array_filter( $menu_items, function ( $item ) {
		return (int) $item->menu_item_parent === 0;
	} )
);
?>

<header class="nil-home" role="banner">

	<!-- ── Logo ── -->
	<div class="nil-home-logo">
		<?php
		$_nil_custom_logo_id = (int) get_theme_mod( 'custom_logo', 0 );
		$_nil_logo_id        = $_nil_custom_logo_id ?: (int) get_option( 'nil_header_logo_id', 0 );
		$_nil_logo_url       = $_nil_logo_id ? wp_get_attachment_image_url( $_nil_logo_id, 'full' ) : '';
		if ( $_nil_logo_url ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( $_nil_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nil-home-logo-text">
				<?php bloginfo( 'name' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<?php if ( $home_video ) : ?>
		<video class="nil-home-bg-video" autoplay muted loop playsinline>
			<source src="<?php echo esc_url( $home_video ); ?>" type="video/mp4">
		</video>
	<?php endif; ?>

	<div class="nil-home-video-overlay"></div>

	<?php if ( ! empty( $panels ) ) : ?>

	<div class="nil-categories-hero">

		<?php foreach ( $panels as $item ) :

			// If the item links to a tipo-modelo term, pull the count
			$count = 0;
			if ( $item->type === 'taxonomy' && $item->object === 'tipo-modelo' ) {
				$term  = get_term( (int) $item->object_id, 'tipo-modelo' );
				$count = ( $term && ! is_wp_error( $term ) ) ? (int) $term->count : 0;
			}
		?>

			<a href="<?php echo esc_url( $item->url ); ?>"
			   class="nil-cat-panel"
			   title="<?php echo esc_attr( $item->attr_title ? $item->attr_title : $item->title ); ?>">

				<div class="nil-cat-overlay"></div>

				<div class="nil-cat-content">
					<?php if ( $count ) : ?>
						<span class="nil-cat-count">
							<?php echo absint( $count ); ?> <?php esc_html_e( 'Modelos', 'hello-elementor-child' ); ?>
						</span>
					<?php endif; ?>
					<h2 class="nil-cat-name"><?php echo esc_html( strtoupper( $item->title ) ); ?></h2>
					<span class="nil-cat-cta"><?php esc_html_e( 'Ver todos', 'hello-elementor-child' ); ?> &rarr;</span>
				</div>

			</a>

		<?php endforeach; ?>

	</div>

	<?php endif; ?>

</header>

<?php get_footer( 'home' ); ?>
