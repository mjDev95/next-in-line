<?php

add_action( 'init', 'nil_post_type' );

function nil_post_type() {

	add_rewrite_tag( '%tipo_modelo%', '([^/]+)', 'tipo-modelo=' );

	$labels_modelos = array(
		'name'                  => __( 'Modelos', 'hello-elementor-child' ),
		'singular_name'         => __( 'Modelo', 'hello-elementor-child' ),
		'all_items'             => __( 'Todos los modelos', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Añadir nuevo modelo', 'hello-elementor-child' ),
		'add_new'               => __( 'Añadir nuevo', 'hello-elementor-child' ),
		'new_item'              => __( 'Nuevo modelo', 'hello-elementor-child' ),
		'edit_item'             => __( 'Editar modelo', 'hello-elementor-child' ),
		'update_item'           => __( 'Actualizar modelo', 'hello-elementor-child' ),
		'view_item'             => __( 'Ver modelo', 'hello-elementor-child' ),
		'view_items'            => __( 'Ver modelos', 'hello-elementor-child' ),
		'search_items'          => __( 'Buscar modelos', 'hello-elementor-child' ),
		'not_found'             => __( 'No se encontraron modelos', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No se encontraron modelos en la papelera', 'hello-elementor-child' ),
		'featured_image'        => __( 'Imagen destacada', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Establecer imagen destacada', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Eliminar imagen destacada', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Usar como imagen destacada', 'hello-elementor-child' ),
	);

	register_post_type( 'modelos', array(
		'labels'              => $labels_modelos,
		'hierarchical'        => true,
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => false, // Desactiva el archivo del post type
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'order', 'custom-fields', 'taxonomies' ),
		'taxonomies'          => array( 'tipo-modelo' ),
		'show_in_rest'        => true,
		'rewrite'             => array( 'slug' => '%tipo_modelo%', 'with_front' => false ),
	) );

	$labels_tipo_modelo = array(
		'name'                       => __( 'Tipos de Modelo', 'hello-elementor-child' ),
		'singular_name'              => __( 'Tipo de Modelo', 'hello-elementor-child' ),
		'new_item_name'              => __( 'Nuevo tipo de modelo', 'hello-elementor-child' ),
		'add_new_item'               => __( 'Añadir nuevo tipo de modelo', 'hello-elementor-child' ),
		'edit_item'                  => __( 'Editar tipo de modelo', 'hello-elementor-child' ),
		'update_item'                => __( 'Actualizar tipo de modelo', 'hello-elementor-child' ),
		'view_item'                  => __( 'Ver tipo de modelo', 'hello-elementor-child' ),
		'separate_items_with_commas' => __( 'Separar tipos con comas', 'hello-elementor-child' ),
		'add_or_remove_items'        => __( 'Añadir o eliminar tipos', 'hello-elementor-child' ),
		'choose_from_most_used'      => __( 'Elegir de los más usados', 'hello-elementor-child' ),
		'popular_items'              => __( 'Tipos populares', 'hello-elementor-child' ),
		'search_items'               => __( 'Buscar tipos de modelo', 'hello-elementor-child' ),
		'not_found'                  => __( 'No se encontraron tipos', 'hello-elementor-child' ),
		'no_terms'                   => __( 'Sin tipos de modelo', 'hello-elementor-child' ),
	);

	register_taxonomy( 'tipo-modelo', array( 'modelos' ), array(
		'labels'             => $labels_tipo_modelo,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_nav_menus'  => true,
		'show_tagcloud'      => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'show_in_rest'       => true,
		'publicly_queryable' => true,
		'query_var'          => 'tipo_modelo', // sin guión: PHP parse_str convierte - a _
		'rewrite'            => false,        // manejamos rewrite manualmente
	) );
}

add_action( 'init', 'nil_modelos_rewrite_rules', 20 );
function nil_modelos_rewrite_rules() {
	$terms = get_terms( array( 'taxonomy' => 'tipo-modelo', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}
	$slugs = array_map( function( $t ) { return preg_quote( $t->slug, '/' ); }, $terms );
	$group = '(' . implode( '|', $slugs ) . ')';

	// Single modelo: /{term-slug}/{post-slug}/
	add_rewrite_rule( "^{$group}/([^/]+)/?$", 'index.php?modelos=$matches[2]', 'top' );

	// Archivo taxonomía: /{term-slug}/ (con paginación)
	// Usamos tipo_modelo (underscore) porque PHP parse_str convierte - a _
	add_rewrite_rule( "^{$group}/page/([0-9]+)/?$", 'index.php?tipo_modelo=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( "^{$group}/?$",               'index.php?tipo_modelo=$matches[1]', 'top' );
}

// Genera la URL correcta de cada término: /{term-slug}/
add_filter( 'term_link', 'nil_tipo_modelo_link', 10, 3 );
function nil_tipo_modelo_link( $url, $term, $taxonomy ) {
	if ( $taxonomy !== 'tipo-modelo' ) {
		return $url;
	}
	return home_url( '/' . $term->slug . '/' );
}

// Sin este hook WP busca post_type=post en el archivo de taxonomía
// y no encuentra los modelos.
add_action( 'pre_get_posts', 'nil_taxonomy_post_type' );
function nil_taxonomy_post_type( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'tipo-modelo' ) ) {
		$query->set( 'post_type', 'modelos' );
	}
}

add_filter( 'post_type_link', 'nil_modelos_permalink', 10, 2 );
function nil_modelos_permalink( $link, $post ) {
	if ( $post->post_type !== 'modelos' ) {
		return $link;
	}
	$terms = get_the_terms( $post->ID, 'tipo-modelo' );
	$slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : 'modelo';
	return str_replace( '%tipo_modelo%', $slug, $link );
}

add_action( 'created_tipo-modelo', 'nil_flush_modelos_rewrite' );
add_action( 'deleted_tipo-modelo', 'nil_flush_modelos_rewrite' );
add_action( 'edited_tipo-modelo',  'nil_flush_modelos_rewrite' );
function nil_flush_modelos_rewrite() {
	flush_rewrite_rules();
}

add_filter( 'elementor/utils/get_public_post_types', 'nil_exclude_modelos_from_elementor' );
function nil_exclude_modelos_from_elementor( $post_types ) {
	unset( $post_types['modelos'] );
	return $post_types;
}

add_filter( 'use_block_editor_for_post_type', 'nil_disable_block_editor_modelos', 10, 2 );
function nil_disable_block_editor_modelos( $use_block_editor, $post_type ) {
	if ( $post_type === 'modelos' ) {
		return false;
	}
	return $use_block_editor;
}

function crear_metabox_datos_modelo() {
	add_meta_box( 'datos_modelo', __( 'Datos del Modelo', 'hello-elementor-child' ), 'mostrar_metabox_datos_modelo', 'modelos', 'side', 'high' );
}

function mostrar_metabox_datos_modelo( $post ) {
	$height = get_post_meta( $post->ID, 'height', true );
	$suit   = get_post_meta( $post->ID, 'suit', true );
	$collar = get_post_meta( $post->ID, 'collar', true );
	$waist  = get_post_meta( $post->ID, 'waist', true );
	$inseam = get_post_meta( $post->ID, 'inseam', true );
	$shoe   = get_post_meta( $post->ID, 'shoe', true );
	$hair   = get_post_meta( $post->ID, 'hair', true );
	$eyes   = get_post_meta( $post->ID, 'eyes', true );
	?>
	<p>
		<label><?php _e( 'HEIGHT', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="height" value="<?php echo esc_attr( $height ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'SUIT', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="suit" value="<?php echo esc_attr( $suit ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'COLLAR', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="collar" value="<?php echo esc_attr( $collar ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'WAIST', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="waist" value="<?php echo esc_attr( $waist ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'INSEAM', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="inseam" value="<?php echo esc_attr( $inseam ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'SHOE', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="shoe" value="<?php echo esc_attr( $shoe ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'HAIR', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="hair" value="<?php echo esc_attr( $hair ); ?>" style=" width: 100%; ">
	</p>
	<p>
		<label><?php _e( 'EYES', 'hello-elementor-child' ); ?></label><br>
		<input type="text" name="eyes" value="<?php echo esc_attr( $eyes ); ?>" style=" width: 100%; ">
	</p>
	<?php
}

function guardar_campos_datos_modelo( $post_id ) {
	if ( isset( $_POST['height'] ) ) {
		update_post_meta( $post_id, 'height', sanitize_text_field( $_POST['height'] ) );
	}
	if ( isset( $_POST['suit'] ) ) {
		update_post_meta( $post_id, 'suit', sanitize_text_field( $_POST['suit'] ) );
	}
	if ( isset( $_POST['collar'] ) ) {
		update_post_meta( $post_id, 'collar', sanitize_text_field( $_POST['collar'] ) );
	}
	if ( isset( $_POST['waist'] ) ) {
		update_post_meta( $post_id, 'waist', sanitize_text_field( $_POST['waist'] ) );
	}
	if ( isset( $_POST['inseam'] ) ) {
		update_post_meta( $post_id, 'inseam', sanitize_text_field( $_POST['inseam'] ) );
	}
	if ( isset( $_POST['shoe'] ) ) {
		update_post_meta( $post_id, 'shoe', sanitize_text_field( $_POST['shoe'] ) );
	}
	if ( isset( $_POST['hair'] ) ) {
		update_post_meta( $post_id, 'hair', sanitize_text_field( $_POST['hair'] ) );
	}
	if ( isset( $_POST['eyes'] ) ) {
		update_post_meta( $post_id, 'eyes', sanitize_text_field( $_POST['eyes'] ) );
	}
}

add_action( 'add_meta_boxes', 'crear_metabox_datos_modelo' );
add_action( 'save_post', 'guardar_campos_datos_modelo' );

add_filter( 'hidden_meta_boxes', 'nil_force_visible_metaboxes', 10, 2 );
function nil_force_visible_metaboxes( $hidden, $screen ) {
	if ( isset( $screen->post_type ) && $screen->post_type === 'modelos' ) {
		$hidden = array_diff( $hidden, array( 'datos_modelo', 'galeria_fotos', 'galeria_videos' ) );
	}
	return $hidden;
}
