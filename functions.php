<?php
/**
 * Hello Elementor Child — functions.php
 */

require_once get_stylesheet_directory() . '/inc/custom-posts.php';
require_once get_stylesheet_directory() . '/inc/custom-fields.php';
require_once get_stylesheet_directory() . '/inc/grace-period.php';

add_action( 'after_setup_theme', 'nil_theme_setup' );
function nil_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	register_nav_menus( array(
		'nil-fullscreen-menu' => __( 'Menú Pantalla Completa (Header)', 'hello-elementor-child' ),
		'nil-social-menu' => __( 'Menú Social (Footer)', 'hello-elementor-child' ),
    ) );
}
/**
 * Script crítico en <head>:
 * - Añade la clase nil-js antes del primer paint (evita FOUC del page-transition overlay).
 * - Detecta visita previa via sessionStorage y añade nil-preloader-skip.
 * - En primera visita a la Home añade nil-preloader-active (el overlay permanece oculto).
 */
add_action( 'wp_head', 'nil_head_inline_script', 1 );
function nil_head_inline_script() {
    $is_home = is_front_page() ? 'true' : 'false';
    echo "<script>(function(){
    var d=document.documentElement;
    d.classList.add('nil-js');
    if({$is_home}){d.classList.add('nil-preloader-active');}
})();</script>\n";
}

add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles' );
function hello_elementor_child_enqueue_styles() {
    // Helper: usa filemtime para que el navegador siempre descargue la versión más reciente
    $v = function ( $rel ) {
        $path = get_stylesheet_directory() . $rel;
        return file_exists( $path ) ? filemtime( $path ) : wp_get_theme()->get( 'Version' );
    };

    wp_enqueue_style( 'hello-elementor-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'hello-elementor-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [ 'hello-elementor-style' ],
        wp_get_theme()->get( 'Version' )
    );
    wp_enqueue_style(
        'bootstrap-layout-lite',
        get_stylesheet_directory_uri() . '/assets/css/bootstrap-layout-lite.css',
        [ 'hello-elementor-child-style' ],
        $v( '/assets/css/bootstrap-layout-lite.css' )
    );
    wp_enqueue_style(
        'hello-elementor-child-global',
        get_stylesheet_directory_uri() . '/assets/css/global.css',
        [ 'hello-elementor-child-style' ],
        $v( '/assets/css/global.css' )
    );
    
    if ( is_front_page() || is_post_type_archive( 'modelos' ) || is_singular( 'modelos' ) || is_tax( 'tipo-modelo' ) ) {
        wp_enqueue_style(
            'nil-modelos',
            get_stylesheet_directory_uri() . '/assets/css/modelos.css',
            [],
            $v( '/assets/css/modelos.css' )
        );
    }

    // ── LIBRERÍAS DE TERCEROS (GSAP + PAGE TRANSITION EN TODO EL SITIO) ──
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], null, true );

    // ── FEATHER ICONS (iconos minimalistas para el cursor global y uso futuro) ──
    wp_enqueue_script( 'feather-icons', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js', [], '4.29.2', true );

    // ── CURSOR PERSONALIZADO GLOBAL ──
    wp_enqueue_script(
        'nil-cursor',
        get_stylesheet_directory_uri() . '/assets/js/nil-cursor.js',
        array( 'jquery', 'gsap', 'feather-icons' ),
        $v( '/assets/js/nil-cursor.js' ),
        true
    );
    
    if ( is_singular( 'modelos' ) ) {
        wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap' ), null, true );
        
        // ⚡ EXTENSIÓN PREMIUM: Encolamos Swiper JS Core solo en el perfil individual de los modelos
        wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
        wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );
    }

    wp_enqueue_style(
        'nil-page-transition',
        get_stylesheet_directory_uri() . '/assets/css/page-transition.css',
        [],
        $v( '/assets/css/page-transition.css' )
    );
    wp_enqueue_script(
        'nil-page-transition',
        get_stylesheet_directory_uri() . '/assets/js/page-transition.js',
        array( 'gsap' ),
        $v( '/assets/js/page-transition.js' ),
        true
    );

    // ⚡ MOTOR DE LA GALERÍA EDITORIAL: Carga diferida, Swiper interactivo y cursor magnético de GSAP
    if ( is_singular( 'modelos' ) ) {
        wp_enqueue_script(
            'nil-gallery-lazy',
            get_stylesheet_directory_uri() . '/assets/js/gallery-lazy.js',
            array( 'jquery', 'gsap', 'gsap-scrolltrigger', 'swiper-js', 'nil-cursor' ),
            $v( '/assets/js/gallery-lazy.js' ),
            true
        );
    }

    // Preloader + hero: solo en la Home
    if ( is_front_page() ) {
        wp_enqueue_style(
            'nil-preloader',
            get_stylesheet_directory_uri() . '/assets/css/preloader.css',
            [],
            $v( '/assets/css/preloader.css' )
        );
        wp_enqueue_script(
            'nil-preloader',
            get_stylesheet_directory_uri() . '/assets/js/preloader.js',
            array( 'gsap' ),
            $v( '/assets/js/preloader.js' ),
            true
        );
        wp_enqueue_script(
            'nil-home-hero',
            get_stylesheet_directory_uri() . '/assets/js/home-hero.js',
            array( 'gsap' ),
            $v( '/assets/js/home-hero.js' ),
            true
        );
    }

    if ( ! is_front_page() ) {
        wp_enqueue_style(
            'nil-fullscreen-nav',
            get_stylesheet_directory_uri() . '/assets/css/fullscreen-nav.css',
            [],
            $v( '/assets/css/fullscreen-nav.css' )
        );
        wp_enqueue_script(
            'nil-fullscreen-nav',
            get_stylesheet_directory_uri() . '/assets/js/fullscreen-nav.js',
            array( 'gsap', 'nil-page-transition' ),
            $v( '/assets/js/fullscreen-nav.js' ),
            true
        );
    }

    if ( is_singular( 'modelos' ) ) {
        wp_enqueue_script(
            'nil-modelo-hero',
            get_stylesheet_directory_uri() . '/assets/js/modelo-hero.js',
            array( 'gsap', 'gsap-scrolltrigger' ),
            $v( '/assets/js/modelo-hero.js' ),
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
