<?php
/**
 * Hello Elementor Child — functions.php
 */

require_once get_stylesheet_directory() . '/inc/custom-posts.php';
require_once get_stylesheet_directory() . '/inc/custom-gallery.php';
require_once get_stylesheet_directory() . '/inc/custom-fields.php';
require_once get_stylesheet_directory() . '/inc/grace-period.php';

add_action( 'after_setup_theme', 'nil_theme_setup' );
function nil_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	register_nav_menus( array(
		'nil-fullscreen-menu' => __( 'Menú Pantalla Completa (Header)', 'hello-elementor-child' ),
	) );
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

	// GSAP + page transition: todas las páginas
	wp_enqueue_script(
		'gsap',
		'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
		[],
		null,
		true
	);
	wp_enqueue_style(
		'nil-page-transition',
		get_stylesheet_directory_uri() . '/assets/css/page-transition.css',
		[],
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_script(
		'nil-page-transition',
		get_stylesheet_directory_uri() . '/assets/js/page-transition.js',
		array( 'gsap' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
	// Marcar que JS está disponible (para el clip-path inicial del overlay)
	wp_add_inline_script( 'gsap', 'document.documentElement.classList.add("nil-js");', 'before' );

	if ( ! is_front_page() ) {
		wp_enqueue_style(
			'nil-fullscreen-nav',
			get_stylesheet_directory_uri() . '/assets/css/fullscreen-nav.css',
			[],
			wp_get_theme()->get( 'Version' )
		);
		wp_enqueue_script(
			'nil-fullscreen-nav',
			get_stylesheet_directory_uri() . '/assets/js/fullscreen-nav.js',
			array( 'gsap', 'nil-page-transition' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
}

// ── SEO: title + meta description (fallback when Yoast SEO is not active) ──────
if ( ! defined( 'WPSEO_VERSION' ) ) {

	add_filter( 'pre_get_document_title', 'nil_seo_title', 10 );
	function nil_seo_title( $title ) {
		$site = get_bloginfo( 'name' );

		if ( is_singular( 'modelos' ) ) {
			$post     = get_queried_object();
			$terms    = get_the_terms( $post->ID, 'tipo-modelo' );
			$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
			return $category
				? sprintf( '%1$s — %2$s | %3$s', $post->post_title, $category, $site )
				: sprintf( '%1$s | %2$s', $post->post_title, $site );
		}

		if ( is_tax( 'tipo-modelo' ) ) {
			$term = get_queried_object();
			/* translators: 1: term name 2: site name */
			return sprintf( __( 'Modelos %1$s | %2$s', 'hello-elementor-child' ), $term->name, $site );
		}

		if ( is_post_type_archive( 'modelos' ) ) {
			/* translators: %s: site name */
			return sprintf( __( 'Modelos | %s', 'hello-elementor-child' ), $site );
		}

		return $title;
	}

	add_action( 'wp_head', 'nil_seo_meta_description', 1 );
	function nil_seo_meta_description() {
		if ( is_singular( 'modelos' ) ) {
			$post = get_queried_object();
			$desc = '';
			if ( $post->post_excerpt ) {
				$desc = $post->post_excerpt;
			} elseif ( $post->post_content ) {
				$desc = wp_trim_words( wp_strip_all_tags( $post->post_content ), 25, '...' );
			}
			if ( ! $desc ) {
				$terms    = get_the_terms( $post->ID, 'tipo-modelo' );
				$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
				$desc     = $category
					? sprintf(
						/* translators: 1: model name 2: category name */
						__( 'Perfil de %1$s — modelo en la categoría %2$s. Next In Line Management.', 'hello-elementor-child' ),
						$post->post_title, $category
					)
					: sprintf(
						/* translators: %s: model name */
						__( 'Perfil de %s. Next In Line Management.', 'hello-elementor-child' ),
						$post->post_title
					);
			}
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
			return;
		}

		if ( is_tax( 'tipo-modelo' ) ) {
			$term = get_queried_object();
			$desc = $term->description
				? wp_trim_words( wp_strip_all_tags( $term->description ), 25, '...' )
				: sprintf(
					/* translators: %s: term name */
					__( 'Descubre los modelos de la categoría %s en Next In Line Management.', 'hello-elementor-child' ),
					$term->name
				);
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
			return;
		}

		if ( is_post_type_archive( 'modelos' ) ) {
			printf(
				'<meta name="description" content="%s">' . "\n",
				esc_attr( __( 'Explora todos los modelos de Next In Line Management. Encuentra el talento perfecto para tu proyecto.', 'hello-elementor-child' ) )
			);
		}
	}
}
