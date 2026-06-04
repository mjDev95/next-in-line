<?php
/**
 * Template part: Home Hero 2
 * Diagnostic version: only fullscreen links, no original hero classes.
 */

get_header();

$menu_locations = get_nav_menu_locations();
$menu_id        = isset( $menu_locations['nil-fullscreen-menu'] ) ? (int) $menu_locations['nil-fullscreen-menu'] : 0;
$menu_items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

$panels = array_values(
	array_filter( $menu_items, function ( $item ) {
		return (int) $item->menu_item_parent === 0;
	} )
);
?>

<main role="main" style="width:100vw;height:100vh;margin:0;padding:0;display:flex;align-items:stretch;background:#f3f3f3;">
	<?php if ( ! empty( $panels ) ) : ?>
		<?php foreach ( $panels as $item ) : ?>
			<a
				href="<?php echo esc_url( $item->url ); ?>"
				style="flex:1;display:flex;align-items:center;justify-content:center;color:#111;text-decoration:none;font:300 32px/1 sans-serif;text-transform:uppercase;"
			>
				<?php echo esc_html( $item->title ); ?>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<a
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
			style="flex:1;display:flex;align-items:center;justify-content:center;color:#111;text-decoration:none;font:300 32px/1 sans-serif;text-transform:uppercase;"
		>
			<?php bloginfo( 'name' ); ?>
		</a>
	<?php endif; ?>
</main>

<?php get_footer( 'home' ); ?>
