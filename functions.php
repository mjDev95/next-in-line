<?php
/**
 * Hello Elementor Child — functions.php
 */

require_once get_stylesheet_directory() . '/inc/custom-posts.php';
require_once get_stylesheet_directory() . '/inc/custom-gallery.php';

add_action( 'after_setup_theme', 'nil_theme_setup' );
function nil_theme_setup() {
	add_theme_support( 'post-thumbnails' );
}

add_action( 'admin_enqueue_scripts', 'nil_admin_scripts' );
function nil_admin_scripts( $hook ) {
	global $post;
	if ( ( $hook === 'post.php' || $hook === 'post-new.php' ) && isset( $post ) && $post->post_type === 'modelos' ) {
		wp_enqueue_media();
		wp_enqueue_script( 'nil-admin-gallery', get_stylesheet_directory_uri() . '/assets/js/admin-gallery.js', array( 'jquery' ), null, true );
	}
}

add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles' );
function hello_elementor_child_enqueue_styles() {
	wp_enqueue_style(
		'hello-elementor-style',
		get_template_directory_uri() . '/style.css'
	);
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'hello-elementor-style' ],
		wp_get_theme()->get( 'Version' )
	);
	if ( is_front_page() || is_post_type_archive( 'modelos' ) || is_singular( 'modelos' ) || is_tax( 'tipo-modelo' ) ) {
		wp_enqueue_style(
			'nil-modelos',
			get_stylesheet_directory_uri() . '/assets/css/modelos.css',
			[],
			wp_get_theme()->get( 'Version' )
		);
	}
}
